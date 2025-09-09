<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Carbon\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService extends Service implements AuthServiceInterface
{
    public function __construct(
        protected AuthRepositoryInterface $repository,
        protected UserRepositoryInterface $userRepository
    )
    {
    }

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function login(
        Request $request,
        string  $username,
        string  $password,
        bool    $remember = false,
        bool    $isAdmin = false
    ): string
    {
        $this->authenticate(
            $request,
            [
                'username' => $username,
                'password' => $password,
            ],
            $remember,
            $isAdmin,
        );
        return $this->getUserLoginToken($isAdmin);
    }

    /**
     * @inheritDoc
     */
    public function logout(): void
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        request()->session()->regenerate();
    }

    /**
     * @inheritDoc
     */
    public function assignPassword(User $user, $password): bool
    {
        return $this->repository->assignPassword($user, $password);
    }

    /**
     * @inheritDoc
     */
    public function resetPassword(User $user, $newPassword): bool
    {
        return $this->repository->resetPassword($user, $newPassword);
    }

    /**
     * @inheritDoc
     */
    public function sendOTP(string $mobile): bool
    {
        $user = $this->getUserByUsername($mobile);

        if (!$user instanceof User) return false;

        return $this->repository->sendOTP($user);
    }

    /**
     * @inheritDoc
     */
    public function sendActivationVerificationCode(string $mobile): bool
    {
        $user = $this->getUserByUsername($mobile);

        if (!$user instanceof User) return false;

        return $this->repository->sendActivationVerificationCode($user);
    }

    /**
     * @inheritDoc
     */
    public function sendForgetPasswordVerificationCode(string $mobile): bool
    {
        $user = $this->getUserByUsername($mobile);

        if (!$user instanceof User) return false;

        return $this->repository->sendForgetPasswordVerificationCode($user);
    }

    /**
     * @inheritDoc
     * @throws ValidationException
     */
    public function verifyOTP(Request $request, string $username, string $code): ?string
    {
        $user = $this->getUserByUsername($username);

        if (
            !$user instanceof User ||
            now()->gt($user->otp_password_expires_at)
        ) {
            $user->resetOTP();
            return null;
        }

        $this->authenticate(
            $request,
            [
                'username' => $username,
                'otp_password' => $code,
            ],
        );
        return $this->getUserLoginToken();
    }

    /**
     * @inheritDoc
     */
    public function verifyActivationCode(string $username, string $code): bool
    {
        $user = $this->getUserByUsername($username);

        if (!$user instanceof User) return false;

        return $this->repository->verifyActivationCode($user, $code);
    }

    /**
     * @inheritDoc
     */
    public function verifyForgetPasswordCode(string $username, string $code): bool
    {
        $user = $this->getUserByUsername($username);

        if (!$user instanceof User) return false;

        return $this->repository->verifyForgetPasswordCode($user, $code);
    }

    /**
     * @inheritDoc
     */
    public function getUserByUsername(string $username): ?Model
    {
        if (empty($username)) return null;

        $where = new WhereBuilder('users');
        $where->whereEqual('username', $username);

        return $this->userRepository->findWhere($where->build());
    }

    /*
    |--------------------------------------------------------------------------
    | Login Authentication Methods
    |--------------------------------------------------------------------------
    */

    /**
     * @param bool $isAdmin
     * @return string
     * @throws ValidationException
     */
    private function getUserLoginToken(bool $isAdmin = false): string
    {
        $tokenName = $isAdmin ? config('market.token_name.admin') : config('market.token_name.main');

        /**
         * @var User $user
         */
        $user = Auth::user();

        if (empty($user)) {
            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        $user->otp_password = null;
        $user->otp_password_wait_for_code = null;
        $user->otp_password_expires_at = null;
        $user->save();

        $expireAt = Carbon::now()->addDays(30);
        return $user->createToken(name: $tokenName, expiresAt: $expireAt)->plainTextToken;
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @param Request $request
     * @param array $credentials
     * @param bool $remember
     * @param bool $isAdmin
     * @return void
     * @throws ValidationException
     */
    private function authenticate(
        Request $request,
        array   $credentials,
        bool    $remember = false,
        bool    $isAdmin = false
    ): void
    {
        $username = $credentials['username'];

        if (empty($username)) return;

        $this->ensureIsNotRateLimited($request, $username);

        if (!Auth::guard('web')->attempt(
            $credentials,
            $remember
        )) {
            RateLimiter::hit($this->throttleKey($request, $username));

            throw ValidationException::withMessages([
                'username' => trans('auth.failed'),
            ]);
        }

        $user = Auth::user();
        if ($isAdmin && !$user->is_admin) {
            $this->logout();
            throw ValidationException::withMessages([
                'username' => 'این اکانت دسترسی لازم برای ورود به پنل ادمین را ندارد.',
            ]);
        }
        if (!$user->verified_at) {
            $this->logout();
            throw ValidationException::withMessages([
                'username' => 'اکانت شما فعال نمی‌باشد. لطفا ابتدا آن را فعال نمایید.',
            ]);
        }
        if ($user->is_banned) {
            $this->logout();
            throw ValidationException::withMessages([
                'username' => $user->ban_desc || 'اکانت شما بن شده است!',
            ]);
        }

        RateLimiter::clear($this->throttleKey($request, $username));

        $request->session()->regenerate();
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @param Request $request
     * @param string $username
     * @return void
     * @throws ValidationException
     */
    private function ensureIsNotRateLimited(Request $request, string $username): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request, $username), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey($request, $username));

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
     * @param Request $request
     * @param string $username
     * @return string
     */
    private function throttleKey(Request $request, string $username): string
    {
        return Str::lower($username) . '|' . $request->ip();
    }
}
