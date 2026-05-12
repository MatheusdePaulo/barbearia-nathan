<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;
use Illuminate\Support\Facades\Log;

class AppointmentController extends Controller
{
    /**
     * EXIBE A TELA DE AGENDAMENTO
     */
    public function create(Request $request, $service_slug = 'geral')
    {
        $services = Service::all();
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
        ]);
    }

    /**
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
                return redirect()->back()->with('error', 'Erro ao gerar pagamento no Mercado Pago.');
            }

            $appointment = Appointment::create([
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
        }
    }

    /**
     * AGENDAMENTO MANUAL (WALK-IN)
     */
    public function storeAvulso(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'service_id'  => 'required|exists:services,id',
            'date'        => 'required|date',
            'time'        => 'required',
        ]);

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
