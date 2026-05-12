<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class AppointmentController extends Controller
{
    /**
     * EXIBE A TELA DE AGENDAMENTO (Lógica de horários mantida e blindada)
     */
    public function create(Request $request, $service_slug = 'geral')
    {
        $services = Service::all();
        $selectedService = Service::where('slug', $service_slug)->first();

        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $carbonDate = Carbon::parse($date);
        $isToday = $carbonDate->isToday();
        $now = Carbon::now();

        // Regra de Barbearia Fechada (Dom/Seg)
        $dayOfWeek = $carbonDate->dayOfWeek;
        $isClosed = ($dayOfWeek === 0 || $dayOfWeek === 1);

        // BUSCA HORÁRIOS OCUPADOS
        // AQUI O SEGREDO: Só bloqueia se o status for 'confirmed' ou 'pending'
        // Se marcou 'canceled' (Faltou), o horário não entra nesse array e fica LIVRE.
        $appointments = Appointment::whereDate('date', $date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->with('service')
            ->get();

        $bookedSlots = [];
        foreach ($appointments as $app) {
            $bookedSlots[] = $app->time;
            // Lógica de 1h para combos
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

            // TRAVA ANTI-RETROCESSO (Não permite agendar no passado)
            // Se a data for hoje, comparamos o horário do slot com o horário de agora
            if ($isToday) {
                $slotTime = Carbon::parse($date . ' ' . $time);
                if ($slotTime->copy()->addMinutes(1)->lessThan($now)) { // Margem de 1min
                    $isPast = true;
                }
            }

            $slots[] = [
                'time' => $time,
                // Disponível apenas se: Aberto E Não Ocupado E Não é Passado
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
     * MÉTODO PARA O CLIENTE AGENDAR - COM TRAVA DE SEGURANÇA
     */
    public function store(Request $request)
    {
        // 1. TRAVA DE SEGURANÇA: Verifica se o horário ainda está disponível no banco
        $exists = Appointment::whereDate('date', $request->date)
            ->where('time', $request->time)
            ->whereIn('status', ['confirmed', 'pending'])
            ->exists();

        if ($exists) {
            return redirect()->back()->with('error', 'Ops! Alguém acabou de reservar esse horário. Escolha outro.');
        }

        try {
            $service = Service::findOrFail($request->service_id);
            $amount = ($request->payment_type == 'signal') ? 5.00 : (float) $service->price;

            MercadoPagoConfig::setAccessToken(env('MERCADO_PAGO_TOKEN'));
            $client = new PaymentClient();

            $payment = $client->create([
                "transaction_amount" => $amount,
                "description" => "Reserva Barber Nathan: " . $service->name,
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => auth()->user()->email,
                    "first_name" => explode(' ', auth()->user()->name)[0],
                ]
            ]);

            if (!$payment->id) {
                return redirect()->back()->with('error', 'Erro ao gerar pagamento. Tente novamente.');
            }

            $appointment = Appointment::create([
                'user_id' => auth()->id(),
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
            return redirect()->back()->with('error', 'Erro técnico: ' . $e->getMessage());
        }
    }

    /**
     * AGENDAMENTO MANUAL (WALK-IN) - COM TRAVA DE SEGURANÇA
     */
    public function storeAvulso(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'service_id'  => 'required|exists:services,id',
            'date'        => 'required|date',
            'time'        => 'required',
        ]);

        // TRAVA DE SEGURANÇA NO ADMIN TAMBÉM
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
