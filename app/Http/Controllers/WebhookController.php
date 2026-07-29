<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use MercadoPago\MercadoPagoConfig;
use MercadoPago\Client\Payment\PaymentClient;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function handleMercadoPago(Request $request)
    {
        Log::info('Webhook Recebido:', $request->all());

        if (!$this->isValidSignature($request)) {
            Log::warning('Webhook MP: assinatura inválida rejeitada.', [
                'ip' => $request->ip(),
                'headers' => $request->headers->all(),
            ]);
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $paymentId = $request->input('data.id') ?? $request->input('id');
        $type = $request->input('type');

        if ($paymentId && ($type === 'payment' || !$type)) {
            MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
            $client = new PaymentClient();

            try {
                $payment = $client->get($paymentId);

                if ($payment->status === 'approved') {
                    DB::transaction(function () use ($paymentId) {
                        $appointment = Appointment::where('payment_id', $paymentId)
                            ->lockForUpdate()
                            ->first();

                        if ($appointment && $appointment->status !== 'confirmed') {
                            $appointment->update(['status' => 'confirmed']);
                            Log::info("Agendamento {$appointment->id} confirmado via Webhook.");
                        }
                    });
                }
            } catch (\Exception $e) {
                Log::error("Erro no Webhook MP: " . $e->getMessage());
                return response()->json(['error' => 'Erro interno'], 500);
            }
        }

        return response()->json(['status' => 'received'], 200);
    }

    private function isValidSignature(Request $request): bool
    {
        $secret = config('services.mercadopago.webhook_secret');

        // Se não houver secret configurado (ambiente local/dev), permite passar
        if (empty($secret)) {
            return true;
        }

        $xSignature = $request->header('X-Signature');
        $xRequestId = $request->header('X-Request-Id');

        if (!$xSignature) {
            return false;
        }

        $dataId = $request->input('data.id') ?? $request->input('id', '');

        // Monta o manifest conforme documentação do Mercado Pago
        $manifest = "id:{$dataId};request-id:{$xRequestId};ts:";

        // Extrai ts e v1 do header X-Signature
        $ts = null;
        $v1 = null;

        foreach (explode(',', $xSignature) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, '');
            if ($key === 'ts') $ts = $value;
            if ($key === 'v1') $v1 = $value;
        }

        if (!$ts || !$v1) {
            return false;
        }

        $manifest .= $ts;
        $computed = hash_hmac('sha256', $manifest, $secret);

        return hash_equals($computed, $v1);
    }
}
