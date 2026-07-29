<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'phone'    => ['required', 'string'],
            'email'    => ['nullable', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'birthday' => ['nullable', 'date'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $whatsappLimpo = preg_replace('/[^0-9]/', '', $request->phone);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->filled('email') ? strtolower($request->email) : null,
            'password' => $request->password,
            'whatsapp' => $whatsappLimpo,
            'birthday' => $request->filled('birthday') ? $request->birthday : null,
            'is_admin' => false,
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
