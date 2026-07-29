<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Coupon;
use App\Models\Review;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ReviewController extends Controller
{
    // Painel admin
    public function index()
    {
        $reviews = Review::with('user', 'appointment')
            ->latest()
            ->get();

        $positiveReviews = $reviews->whereIn('rating', [3, 4, 5]);
        $average = $positiveReviews->count() ? round($positiveReviews->avg('rating'), 1) : null;
        $total   = $reviews->count();

        $settings           = Setting::pluck('value', 'key');
        $reviewCouponActive  = (bool) ($settings['review_coupon_active']  ?? true);
        $reviewCouponPercent = (int)  ($settings['review_coupon_percent'] ?? 5);

        return view('admin.reviews.index', compact('reviews', 'average', 'total', 'reviewCouponActive', 'reviewCouponPercent'));
    }

    // Página pública de avaliação (link enviado via WhatsApp pelo admin)
    public function form(Request $request)
    {
        $user = User::find($request->user_id);

        // Verifica se já avaliou sem agendamento (review standalone)
        $jaAvaliou = $user
            ? Review::where('user_id', $user->id)->whereNull('appointment_id')->exists()
            : false;

        $settings           = Setting::pluck('value', 'key');
        $reviewCouponActive  = (bool) ($settings['review_coupon_active']  ?? true);
        $reviewCouponPercent = (int)  ($settings['review_coupon_percent'] ?? 5);

        return view('reviews.form', compact('user', 'jaAvaliou', 'reviewCouponActive', 'reviewCouponPercent'));
    }

    // Salva avaliação (via fetch — sucesso.blade e form standalone)
    public function store(Request $request)
    {
        $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'user_id'        => 'nullable|exists:users,id',
            'rating'         => 'required|integer|min:1|max:5',
            'comment'        => 'nullable|string|max:1000',
        ]);

        // Impede duplicatas
        if ($request->appointment_id) {
            if (Review::where('appointment_id', $request->appointment_id)->exists()) {
                return response()->json(['message' => 'Já avaliado.'], 422);
            }
            $appointment = Appointment::find($request->appointment_id);
            $userId      = $appointment?->user_id;
            $authorName  = $appointment?->user?->name ?? $appointment?->client_name ?? 'Cliente';
        } else {
            if ($request->user_id && Review::where('user_id', $request->user_id)->whereNull('appointment_id')->exists()) {
                return response()->json(['message' => 'Já avaliado.'], 422);
            }
            $user       = $request->user_id ? User::find($request->user_id) : null;
            $userId     = $user?->id;
            $authorName = $user?->name ?? 'Cliente';
        }

        Review::create([
            'appointment_id' => $request->appointment_id,
            'user_id'        => $userId,
            'client_name'    => $authorName,
            'rating'         => $request->rating,
            'comment'        => $request->comment,
        ]);

        return response()->json(['success' => true]);
    }

    // Gera cupom AVA- após avaliar no Google (vinculado ao agendamento ou ao user)
    public function generateCoupon(Request $request)
    {
        $request->validate([
            'appointment_id' => 'nullable|exists:appointments,id',
            'user_id'        => 'nullable|exists:users,id',
        ]);

        $settings           = Setting::pluck('value', 'key');
        $reviewCouponActive  = (bool) ($settings['review_coupon_active']  ?? true);
        $reviewCouponPercent = (int)  ($settings['review_coupon_percent'] ?? 5);

        if (! $reviewCouponActive) {
            return response()->json(['disabled' => true]);
        }

        // Busca cupom já existente para não gerar dois
        $existing = Coupon::where('type', 'review')
            ->when($request->appointment_id, fn($q) => $q->where('appointment_id', $request->appointment_id))
            ->when(! $request->appointment_id && $request->user_id, fn($q) => $q->where('user_id', $request->user_id)->whereNull('appointment_id'))
            ->first();

        if (! $existing) {
            $appointment = $request->appointment_id ? Appointment::find($request->appointment_id) : null;
            $user        = $request->user_id ? User::find($request->user_id) : $appointment?->user;

            do {
                $code = 'AVA-' . strtoupper(Str::random(5));
            } while (Coupon::where('code', $code)->exists());

            $existing = Coupon::create([
                'code'             => $code,
                'discount_percent' => $reviewCouponPercent,
                'type'             => 'review',
                'user_id'          => $user?->id,
                'client_phone'     => $user?->whatsapp,
                'appointment_id'   => $appointment?->id,
                'max_uses'         => 1,
            ]);
        }

        return response()->json(['code' => $existing->code]);
    }
}
