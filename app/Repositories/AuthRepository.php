<?php

namespace App\Repositories;

use App\Events\ForgetPasswordEvent;
use App\Events\RegisteredEvent;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use function App\Support\Helper\get_random_verification_code;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function assignPassword(User $user, string $password): bool
    {
        $user->password = Hash::make($password);
        $user->verification_code = null;
        $user->verified_at = now();

        $status = $user->save();

        if ($status) {
            RegisteredEvent::dispatch($user);
        }

        return $status;
    }

    /**
     * @inheritDoc
     */
    public function resetPassword(User $user, string $newPassword): bool
    {
        $user->password = Hash::make($newPassword);
        $user->forget_password_code = null;
        $user->forget_password_at = now();

        $status = $user->save();

        if ($status) {
            ForgetPasswordEvent::dispatch($user);
        }

        return $status;
    }

    /**
     * @inheritDoc
     */
    public function sendActivationVerificationCode(User $user): bool
    {
        if ($user->shouldSendAVerifyCode()) {
            $code = get_random_verification_code(config('market:sms.verify_code_length'));
            $user->notifyActivationVerificationCode($code);
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function sendForgetPasswordVerificationCode(User $user): bool
    {
        if ($user->shouldSendActivationVerifyCode()) {
            $code = get_random_verification_code(config('market:sms.verify_code_length'));
            $user->notifyForgetPasswordVerificationCode($code);
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function verifyActivationCode(User $user, string $code): bool
    {
        return $user->verification_code === $code;
    }

    /**
     * @inheritDoc
     */
    public function verifyForgetPasswordCode(User $user, string $code): bool
    {
        return $user->forget_password_code === $code;
    }
}
