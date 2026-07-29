<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use App\Models\ScheduleOverride;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    /**
     * EXIBE A TELA DE AGENDAMENTO
     */
    public function create(Request $request, $service_slug = 'geral')
    {
        $services = Service::all();

        // Serviços pré-selecionados via URL (?services=1,2,3 ou slug legado)
        $selectedIds = [];
        if ($request->has('services')) {
            $selectedIds = array_filter(explode(',', $request->get('services')));
        } elseif ($service_slug !== 'geral') {
            $bySlug = Service::where('slug', $service_slug)->first();
            if ($bySlug) $selectedIds = [$bySlug->id];
        }

        $selectedServices = $selectedIds
            ? Service::whereIn('id', $selectedIds)->get()
            : collect();

        $totalDuration = $selectedServices->sum('duration') ?: 30;
        $slotsNeeded   = (int) ceil($totalDuration / 30);

        $date       = $request->get('date', Carbon::today()->format('Y-m-d'));
        $isToday    = Carbon::parse($date)->isToday();
        $now        = Carbon::now();

        $allSlots  = ScheduleOverride::getSlotsForDate($date);
        $isClosed  = empty($allSlots);

        // Agendamentos existentes no dia (pending só bloqueia se criado há menos de 10 min)
        $appointments = Appointment::whereDate('date', $date)
            ->where(function ($q) {
                $q->where('status', 'confirmed')
                  ->orWhere(function ($q2) {
                      $q2->where('status', 'pending')
                         ->where('created_at', '>=', Carbon::now()->subMinutes(10));
                  });
            })
            ->with('services', 'service')
            ->get();

        // Monta lista de slots já bloqueados (com suporte a múltiplos serviços e legado)
        $bookedSlots = [];
        foreach ($appointments as $app) {
            $appDuration  = $app->total_duration;
            $appSlots     = (int) ceil($appDuration / 30);
            for ($i = 0; $i < $appSlots; $i++) {
                $bookedSlots[] = Carbon::parse($app->time)->addMinutes($i * 30)->format('H:i');
            }
        }

        // Para cada slot candidato, verifica se todos os slots consecutivos necessários estão livres
        $slots = [];
        foreach ($allSlots as $time) {
            $available = true;
            for ($i = 0; $i < $slotsNeeded; $i++) {
                $checkTime = Carbon::parse($date . ' ' . $time)->addMinutes($i * 30)->format('H:i');

                if (!in_array($checkTime, $allSlots)) {
                    $available = false;
                    break;
                }
                if (in_array($checkTime, $bookedSlots)) {
                    $available = false;
                    break;
                }
                if ($isToday && Carbon::parse($date . ' ' . $checkTime)->addMinutes(1)->lessThan($now)) {
                    $available = false;
                    break;
                }
            }

            $slots[] = ['time' => $time, 'available' => $available];
        }

        return view('appointments.create', [
            'services'         => $services,
            'selectedServices' => $selectedServices,
            'selectedIds'      => $selectedIds,
            'slots'            => $slots,
            'selectedDate'     => $date,
            'isClosed'         => $isClosed,
            'totalDuration'    => $totalDuration,
            'slotsNeeded'      => $slotsNeeded,
        ]);
    }

    /**
     * PROCESSA O AGENDAMENTO DO CLIENTE (logado ou guest)
     */
    public function store(Request $request)
    {
        $rules = [
            'service_ids'   => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'date'          => 'required|date',
            'time'          => 'required',
            'payment_type'  => 'required|in:signal,full',
        ];

        if (! auth()->check()) {
            $rules['guest_name']     = 'required|string|max:255';
            $rules['guest_whatsapp'] = 'nullable|string|max:20';
            $rules['guest_birthday'] = 'nullable|date';
        }

        $rules['coupon_code'] = 'nullable|string|max:30';

        $request->validate($rules);

        $services      = Service::whereIn('id', $request->service_ids)->get();
        $totalDuration = $services->sum('duration');
        $slotsNeeded   = (int) ceil($totalDuration / 30);

        for ($i = 0; $i < $slotsNeeded; $i++) {
            $checkTime = Carbon::parse($request->date . ' ' . $request->time)
                ->addMinutes($i * 30)->format('H:i');

            $exists = Appointment::whereDate('date', $request->date)
                ->where('time', $checkTime)
                ->where(function ($q) {
                    $q->where('status', 'confirmed')
                      ->orWhere(function ($q2) {
                          $q2->where('status', 'pending')
                             ->where('created_at', '>=', Carbon::now()->subMinutes(10));
                      });
                })
                ->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'Ops! Um dos horários necessários já está ocupado. Escolha outro.');
            }
        }

        try {
            // Resolve o usuário (logado, guest com whatsapp, ou anônimo)
            if (auth()->check()) {
                $user       = auth()->user();
                $clientName = null;
            } else {
                $guestName     = $request->guest_name;
                $guestWhatsapp = $request->filled('guest_whatsapp')
                    ? preg_replace('/[^0-9]/', '', $request->guest_whatsapp)
                    : null;
                $guestBirthday = $request->filled('guest_birthday') ? $request->guest_birthday : null;

                if ($guestWhatsapp) {
                    $user = User::where('whatsapp', $guestWhatsapp)->first();
                    if ($user) {
                        if (! $user->birthday && $guestBirthday) {
                            $user->update(['birthday' => $guestBirthday]);
                        }
                    } else {
                        $user = User::create([
                            'name'     => $guestName,
                            'whatsapp' => $guestWhatsapp,
                            'birthday' => $guestBirthday,
                        ]);
                    }
                    $clientName = null;
                } else {
                    $user       = null;
                    $clientName = $guestName;
                }
            }

            // Valida cupom (se informado)
            $coupon = null;
            if ($request->filled('coupon_code')) {
                $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();
                if (! $coupon || ! $coupon->isValid()) {
                    return redirect()->back()->with('error', 'Cupom inválido ou já utilizado.');
                }
            }

            $amount = $request->payment_type === 'signal'
                ? 5.00
                : $services->sum(fn($s) => (float) $s->active_price);

            // Aplica desconto do cupom
            if ($coupon) {
                $amount = round($amount * (1 - $coupon->discount_percent / 100), 2);
                $amount = max($amount, 0.01); // Mercado Pago exige valor mínimo
            }

            $displayName = $user ? $user->name : ($request->guest_name ?? 'Cliente');
            $nameParts   = explode(' ', trim($displayName));
            $firstName   = $nameParts[0];
            $lastName    = count($nameParts) > 1 ? end($nameParts) : 'Silva';

            $payerEmail = $user?->email ?? null;
            if (! $payerEmail || ! filter_var($payerEmail, FILTER_VALIDATE_EMAIL)) {
                $identifier = $user?->whatsapp ?? $user?->id ?? Str::slug($displayName) . '_' . time();
                $payerEmail = 'cliente_' . preg_replace('/[^0-9a-z_]/', '', (string) $identifier) . '@nathandocorte.com';
            }

            MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
            $client = new PaymentClient();

            $serviceLabel = $services->pluck('name')->join(', ');
            $payment = $client->create([
                'transaction_amount' => $amount,
                'description'        => "Reserva Barber Nathan: {$serviceLabel}",
                'payment_method_id'  => 'pix',
                'payer'              => [
                    'email'      => $payerEmail,
                    'first_name' => $firstName,
                    'last_name'  => $lastName,
                ],
            ]);

            if (! $payment->id) {
                return redirect()->back()->with('error', 'Erro ao gerar pagamento no Mercado Pago.');
            }

            $appointment = Appointment::create([
                'user_id'        => $user?->id,
                'client_name'    => $clientName,
                'service_id'     => $services->first()->id,
                'date'           => $request->date,
                'time'           => $request->time,
                'status'         => 'pending',
                'deposit_amount' => $amount,
                'payment_id'     => $payment->id,
                'pix_code'       => $payment->point_of_interaction?->transaction_data?->qr_code,
                'pix_qr_64'      => $payment->point_of_interaction?->transaction_data?->qr_code_base64,
            ]);

            $appointment->services()->attach($request->service_ids);

            // Marca cupom como usado
            if ($coupon) {
                $coupon->increment('use_count');
                if ($coupon->max_uses > 0 && $coupon->use_count >= $coupon->max_uses) {
                    $coupon->update(['used' => true, 'used_at' => now()]);
                }
            }

            $settings           = Setting::pluck('value', 'key');
            $reviewCouponActive  = (bool) ($settings['review_coupon_active']  ?? true);
            $reviewCouponPercent = (int)  ($settings['review_coupon_percent'] ?? 5);

            return view('appointments.success', compact('appointment', 'reviewCouponActive', 'reviewCouponPercent'));

        } catch (\Exception $e) {
            $errorMsg = 'Erro ao processar pagamento.';

            if (method_exists($e, 'getApiResponse') && $e->getApiResponse()) {
                $content = $e->getApiResponse()->getContent();
                Log::error('Erro Detalhado MP: ' . json_encode($content));
                $errorMsg = $content['message']
                    ?? $content['cause'][0]['description']
                    ?? json_encode($content);
            } else {
                Log::error('Erro Geral MP: ' . $e->getMessage());
                $errorMsg = $e->getMessage();
            }

            $final = config('app.debug') ? $errorMsg : 'Erro técnico ao processar transação.';
            return redirect()->back()->with('error', 'Erro técnico: ' . $final);
        }
    }

    /**
     * AGENDAMENTO MANUAL (WALK-IN) pelo admin
     */
    public function storeAvulso(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'service_id'  => 'required|exists:services,id',
            'date'        => 'required|date',
            'time'        => 'required',
        ]);

        $service  = Service::findOrFail($validated['service_id']);
        $duration = $service->total_duration ?? $service->duration ?? 30;
        $slotsNeeded = (int) ceil($duration / 30);

        for ($i = 0; $i < $slotsNeeded; $i++) {
            $checkTime = Carbon::parse($validated['date'] . ' ' . $validated['time'])
                ->addMinutes($i * 30)->format('H:i');

            $exists = Appointment::whereDate('date', $validated['date'])
                ->where('time', $checkTime)
                ->where(function ($q) {
                    $q->where('status', 'confirmed')
                      ->orWhere(function ($q2) {
                          $q2->where('status', 'pending')
                             ->where('created_at', '>=', Carbon::now()->subMinutes(10));
                      });
                })
                ->exists();

            if ($exists) {
                return redirect()->back()->with('error', 'Horário já ocupado no banco de dados!');
            }
        }

        try {
            $appointment = Appointment::create([
                'user_id'        => null,
                'client_name'    => $validated['client_name'],
                'service_id'     => $validated['service_id'],
                'date'           => $validated['date'],
                'time'           => $validated['time'],
                'status'         => 'confirmed',
                'deposit_amount' => 0.00,
            ]);

            $appointment->services()->attach($validated['service_id']);

            return redirect()->back()->with('success', 'Agendamento registrado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status atualizado!');
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return redirect()->back()->with('success', 'Agendamento removido.');
    }
}
