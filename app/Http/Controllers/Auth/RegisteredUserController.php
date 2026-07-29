<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
<<<<<<< HEAD
use Illuminate\Validation\Rules;
=======
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
<<<<<<< HEAD
=======
    /**
     * Display the registration view.
     */
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    public function create(): View
    {
        return view('auth.register');
    }

<<<<<<< HEAD
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
=======
    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            // CPF REMOVIDO DAQUI
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            // CPF REMOVIDO DAQUI
            'password' => Hash::make($request->password),
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
        ]);

        event(new Registered($user));
        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
<<<<<<< HEAD
}
=======
}
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
