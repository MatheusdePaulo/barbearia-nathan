<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
<<<<<<< HEAD
use App\Models\ScheduleOverride;
use App\Models\Coupon;
use App\Models\Setting;
use App\Models\User;
=======
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Facades\Log;
<<<<<<< HEAD
use Illuminate\Support\Str;
=======
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b

class AppointmentController extends Controller
{
    /**
     * EXIBE A TELA DE AGENDAMENTO
     */
    public function create(Request $request, $service_slug = 'geral')
    {
        $services = Service::all();
<<<<<<< HEAD

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
=======
        $selectedService = Service::where('slug', $service_slug)->first();

        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $carbonDate = Carbon::parse($date);
        $isToday = $carbonDate->isToday();
        $now = Carbon::now();

        $dayOfWeek = $carbonDate->dayOfWeek;
        $isClosed = ($dayOfWeek === 0 || $dayOfWeek === 1);

        $appointments = Appointment::whereDate('date', $date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->with('service')
            ->get();

        $bookedSlots = [];
        foreach ($appointments as $app) {
            $bookedSlots[] = $app->time;
            if ($app->service && (strpos($app->service->name, 'Combo') !== false || strpos($app->service->name, 'Progressiva') !== false)) {
                $bookedSlots[] = Carbon::parse($app->time)->addMinutes(30)->format('H:i');
            }
        }

        $allSlots = [
            '08:00', '08:30', '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30', '17:00', '17:30'
        ];

        $slots = [];
        foreach ($allSlots as $time) {
            $isBooked = in_array($time, $bookedSlots);
            $isPast = false;

            if ($isToday) {
                $slotTime = Carbon::parse($date . ' ' . $time);
                if ($slotTime->copy()->addMinutes(1)->lessThan($now)) {
                    $isPast = true;
                }
            }

            $slots[] = [
                'time' => $time,
                'available' => !$isClosed && !$isBooked && !$isPast
            ];
        }

        return view('appointments.create', [
            'services' => $services,
            'selectedService' => $selectedService,
            'slots' => $slots,
            'selectedDate' => $date,
            'isClosed' => $isClosed
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        ]);
    }

    /**
<<<<<<< HEAD
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
=======
     * MÉTODO PARA O CLIENTE AGENDAR
     */
    public function store(Request $request)
    {
        $exists = Appointment::whereDate('date', $request->date)
            ->where('time', $request->time)
            ->whereIn('status', ['confirmed', 'pending'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Ops! Alguém acabou de reservar esse horário. Escolha outro.');
        }

        try {
            $service = Service::findOrFail($request->service_id);
            $user = auth()->user();

            if ($request->payment_type == 'signal') {
                $amount = 5.00;
            } else {
                $amount = (float) ($service->is_promo ? $service->promo_price : $service->price);
            }

            // Lógica para separar Nome e Sobrenome (O MP exige last_name)
            $nameParts = explode(' ', trim($user->name));
            $firstName = $nameParts[0];
            $lastName = (count($nameParts) > 1) ? end($nameParts) : 'Silva';

            MercadoPagoConfig::setAccessToken(env('MERCADO_PAGO_TOKEN'));
            $client = new PaymentClient();

            $payment = $client->create([
                "transaction_amount" => $amount,
                "description" => "Reserva Barber Nathan: " . $service->name,
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => $user->email,
                    "first_name" => $firstName,
                    "last_name" => $lastName,
                ]
            ]);

            if (!$payment->id) {
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                return redirect()->back()->with('error', 'Erro ao gerar pagamento no Mercado Pago.');
            }

            $appointment = Appointment::create([
<<<<<<< HEAD
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
=======
                'user_id' => $user->id,
                'service_id' => $service->id,
                'date' => $request->date,
                'time' => $request->time,
                'status' => 'pending',
                'payment_id' => $payment->id,
                'pix_code' => $payment->point_of_interaction->transaction_data->qr_code,
                'pix_qr_64' => $payment->point_of_interaction->transaction_data->qr_code_base64,
            ]);

            return view('appointments.success', compact('appointment'));

        } catch (\Exception $e) {
            Log::error("Erro Mercado Pago: " . $e->getMessage());

            // Retorna o erro detalhado apenas se estiver em ambiente local
            $errorMsg = config('app.debug') ? $e->getMessage() : 'Erro ao processar pagamento.';
            return redirect()->back()->with('error', 'Erro técnico: ' . $errorMsg);
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        }
    }

    /**
<<<<<<< HEAD
     * AGENDAMENTO MANUAL (WALK-IN) pelo admin
=======
     * AGENDAMENTO MANUAL (WALK-IN)
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
     */
    public function storeAvulso(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'service_id'  => 'required|exists:services,id',
            'date'        => 'required|date',
            'time'        => 'required',
        ]);

<<<<<<< HEAD
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

=======
        $exists = Appointment::whereDate('date', $validated['date'])
            ->where('time', $validated['time'])
            ->whereIn('status', ['confirmed', 'pending'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Horário já ocupado no banco de dados!');
        }

        try {
            Appointment::create([
                'user_id'     => null,
                'client_name' => $validated['client_name'],
                'service_id'  => $validated['service_id'],
                'date'        => $validated['date'],
                'time'        => $validated['time'],
                'status'      => 'confirmed',
            ]);

>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
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
