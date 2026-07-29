<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with('user')->latest()->get();
        return view('admin.coupons.index', compact('coupons'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code'             => 'nullable|string|max:30|unique:coupons,code',
            'discount_percent' => 'required|integer|min:1|max:100',
            'max_uses'         => 'required|integer|min:0',
            'expires_at'       => 'nullable|date|after:today',
        ]);

        $code = $request->filled('code')
            ? strtoupper($request->code)
            : 'MAN-' . strtoupper(Str::random(5));

        Coupon::create([
            'code'             => $code,
            'discount_percent' => $request->discount_percent,
            'type'             => 'manual',
            'max_uses'         => $request->max_uses,
            'expires_at'       => $request->expires_at ?: null,
            'is_active'        => true,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupom criado com sucesso!');
    }

    public function update(Request $request, $id)
    {
        $coupon = Coupon::findOrFail($id);

        $request->validate([
            'discount_percent' => 'required|integer|min:1|max:100',
            'max_uses'         => 'required|integer|min:0',
            'expires_at'       => 'nullable|date',
        ]);

        $coupon->update([
            'discount_percent' => $request->discount_percent,
            'max_uses'         => $request->max_uses,
            'expires_at'       => $request->expires_at ?: null,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Cupom atualizado!');
    }

    public function destroy($id)
    {
        Coupon::findOrFail($id)->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Cupom excluído.');
    }

    public function toggle($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->update(['is_active' => ! $coupon->is_active]);
        return redirect()->back()->with('success', 'Status do cupom atualizado.');
    }

    // Rota pública: valida cupom via AJAX
    public function validateCoupon(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (! $coupon || ! $coupon->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Cupom inválido ou já utilizado.']);
        }

        return response()->json([
            'valid'            => true,
            'discount_percent' => $coupon->discount_percent,
            'message'          => "Cupom aplicado! {$coupon->discount_percent}% de desconto.",
        ]);
    }
}
