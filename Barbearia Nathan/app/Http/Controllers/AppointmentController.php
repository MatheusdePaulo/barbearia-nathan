<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\MercadoPagoConfig;

class AppointmentController extends Controller
{
    /**
     * EXIBE A TELA DE AGENDAMENTO (Lógica de horários mantida 100%)
     */
    public function create(Request $request, $service_slug = 'geral')
    {
        $services = Service::all();
        $selectedService = Service::where('slug', $service_slug)->first();

        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $carbonDate = Carbon::parse($date);
        $isToday = $carbonDate->isToday();
        $now = Carbon::now();

        // Regra de Domingo e Segunda (Barbearia Fechada)
        $dayOfWeek = $carbonDate->dayOfWeek;
        $isClosed = ($dayOfWeek === 0 || $dayOfWeek === 1);

        // Busca horários ocupados
        $bookedSlots = Appointment::whereDate('date', $date)
            ->whereIn('status', ['confirmed', 'pending'])
            ->pluck('time')
            ->toArray();

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
                if ($slotTime->lessThan($now)) {
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
     * MÉTODO PARA O CLIENTE AGENDAR - INTEGRAÇÃO PIX REAL
     */
    public function store(Request $request)
    {
        try {
            $service = Service::findOrFail($request->service_id);
            $amount = ($request->payment_type == 'signal') ? 5.00 : (float) $service->price;

            // Configuração do Mercado Pago via .env
            MercadoPagoConfig::setAccessToken(env('MERCADO_PAGO_TOKEN'));
            $client = new PaymentClient();

            // Pegando o CPF real do usuário e limpando caracteres especiais
            $userCpf = preg_replace('/[^0-9]/', '', auth()->user()->cpf);

            // Criando o pagamento PIX
            $payment = $client->create([
                "transaction_amount" => $amount,
                "description" => "Reserva Barber Nathan: " . $service->name,
                "payment_method_id" => "pix",
                "payer" => [
                    "email" => auth()->user()->email,
                    "first_name" => explode(' ', auth()->user()->name)[0],
                    "identification" => [
                        "type" => "CPF",
                        "number" => $userCpf // Agora enviando o CPF real do banco!
                    ]
                ]
            ]);

            // Verificação de erro na resposta da API
            if (!$payment->id) {
                dd("Erro na API do Mercado Pago:", $payment->error);
            }

            // Criando o registro no banco com os dados do PIX para a Success Blade
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
            // Verifica se o erro possui uma resposta detalhada da API
            if (method_exists($e, 'getApiResponse')) {
                $response = $e->getApiResponse();
                dd([
                    'Mensagem' => 'Erro na API Mercado Pago',
                    'Causa' => $response->getContent() // Isso vai mostrar o JSON real do erro
                ]);
            }

            // Se for um erro comum do PHP/Laravel
            dd("Erro Geral no Agendamento:", $e->getMessage());
        }
    }

    /**
     * MANTIDO: Listagem Admin
     */
    public function index()
    {
        $appointments = Appointment::with('service')->get();
        return view('admin.appointments.index', compact('appointments'));
    }

    /**
     * MANTIDO: Agendamento manual pelo barbeiro (Walk-in)
     */
    public function storeAvulso(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'service_id'  => 'required|exists:services,id',
            'date'        => 'required|date',
            'time'        => 'required',
        ]);

        try {
            Appointment::create([
                'user_id'     => null,
                'client_name' => $validated['client_name'],
                'service_id'  => $validated['service_id'],
                'date'        => $validated['date'],
                'time'        => $validated['time'],
                'status'      => 'confirmed',
            ]);

            return redirect()->back()->with('success', 'Agendamento registrado!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao salvar: ' . $e->getMessage());
        }
    }

    /**
     * MANTIDO: Atualização de Status (Admin)
     */
    public function updateStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Status atualizado!');
    }

    /**
     * MANTIDO: Exclusão de Agendamento
     */
    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return redirect()->back()->with('success', 'Agendamento removido.');
    }
}
