<?php

namespace App\Http\Requests\Auth;

<<<<<<< HEAD
use App\Models\User;
=======
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
<<<<<<< HEAD
=======
    /**
     * Determine if the user is authorized to make this request.
     */
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    public function authorize(): bool
    {
        return true;
    }

<<<<<<< HEAD
    public function rules(): array
    {
        return [
            'login'    => ['required', 'string'],
=======
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            'password' => ['required', 'string'],
        ];
    }

<<<<<<< HEAD
=======
    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

<<<<<<< HEAD
        $login = $this->string('login')->value();
        $password = $this->string('password')->value();
        $remember = $this->boolean('remember');

        $authenticated = false;

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $authenticated = Auth::attempt(['email' => $login, 'password' => $password], $remember);
        } else {
            $whatsapp = preg_replace('/[^0-9]/', '', $login);
            $user = User::where('whatsapp', $whatsapp)->first();
            if ($user) {
                $authenticated = Auth::attempt(['id' => $user->id, 'password' => $password], $remember);
            }
        }

        if (! $authenticated) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'login' => trans('auth.failed'),
=======
        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

<<<<<<< HEAD
=======
    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
<<<<<<< HEAD
            'login' => trans('auth.throttle', [
=======
            'email' => trans('auth.throttle', [
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

<<<<<<< HEAD
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('login')) . '|' . $this->ip());
=======
    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
>>>>>>> 7ac70fc7c47d14397d5b84571e95502c306b785b
    }
}
