<?php

namespace App\Http\Requests\Auth;

use App\Rules\PersianMobileRule;
use App\Services\Contracts\AuthServiceInterface;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'captcha' => ['required', 'captcha_api:' . $this->input('key')],
            'username' => ['required', new PersianMobileRule],
            'password' => 'required',
            'remember' => 'boolean',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @param bool $isAdmin
     * @return void
     * @throws ValidationException
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function authenticate(bool $isAdmin, AuthServiceInterface $authService): void
    {
        $this->ensureIsNotRateLimited();

        if (!Auth::attempt($this->only('username', 'password'), $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        $user = $this->user();
        if ($isAdmin && !$user->is_admin) {
            $authService->logout();
            throw ValidationException::withMessages([
                'username' => 'این اکانت دسترسی لازم برای ورود به پنل ادمین را ندارد.',
            ]);
        }
        if (!$user->verified_at) {
            $authService->logout();
            throw ValidationException::withMessages([
                'username' => 'اکانت شما فعال نمی‌باشد. لطفا ابتدا آن را فعال نمایید.',
            ]);
        }
        if ($user->is_banned) {
            $authService->logout();
            throw ValidationException::withMessages([
                'username' => $user->ban_desc || 'اکانت شما بن شده است!',
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @return void
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    public function throttleKey(): string
    {
        return Str::lower($this->input('username')) . '|' . $this->ip();
    }
}
