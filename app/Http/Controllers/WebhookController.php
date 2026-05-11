<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    public function handleMercadoPago(Request $request)
    {
        // Log para debug local no storage/logs/laravel.log
        Log::info('Webhook Recebido:', $request->all());

        // O Mercado Pago envia o ID dentro de 'data' para notificações de pagamento
        $paymentId = $request->input('data.id') ?? $request->input('id');
        $type = $request->input('type');

        // Só processamos se for uma notificação de pagamento
        if ($paymentId && ($type === 'payment' || !$type)) {
            MercadoPagoConfig::setAccessToken(env('MERCADO_PAGO_TOKEN'));
            $client = new PaymentClient();

            try {
                $payment = $client->get($paymentId);

                if ($payment->status === 'approved') {
                    $appointment = Appointment::where('payment_id', $paymentId)->first();

                    if ($appointment && $appointment->status !== 'confirmed') {
                        $appointment->update(['status' => 'confirmed']);
                        Log::info("Agendamento {$appointment->id} confirmado via Webhook.");
                    }
                }
            } catch (\Exception $e) {
                Log::error("Erro no Webhook MP: " . $e->getMessage());
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        // O MP exige um status 200 ou 201 para parar de enviar a mesma notificação
        return response()->json(['status' => 'received'], 200);
    }
}
