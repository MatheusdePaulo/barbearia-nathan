<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;

class WebhookController extends Controller
{
    public function handleMercadoPago(Request $request)
    {
        // O MP envia o ID do pagamento no campo 'data.id' ou apenas 'id' dependendo do tipo
        $paymentId = $request->input('data_id') ?? $request->input('id');

        if ($paymentId) {
            MercadoPagoConfig::setAccessToken(env('MERCADO_PAGO_TOKEN'));
            $client = new PaymentClient();

            try {
                $payment = $client->get($paymentId);

                // Se o status for approved, atualizamos o agendamento
                if ($payment->status === 'approved') {
                    $appointment = Appointment::where('payment_id', $paymentId)->first();
                    if ($appointment) {
                        $appointment->update(['status' => 'confirmed']);
                    }
                }
            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
