<?php

namespace App\Repositories;

use App\Events\ForgetPasswordEvent;
use App\Events\RegisteredEvent;
use App\Exceptions\PleaseWaitException;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function assignPassword(User $user, string $password): bool
    {
        $user->password = Hash::make($password);
        $user->verification_code = null;

        return $user->save();
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
     * @throws Exception
     */
    public function sendOTP(User $user): bool
    {
        if ($user->shouldSendOTP()) {
            $code = get_random_verification_code(config('market.sms.verify_code_length', 6));

            if (!app()->isProduction()) {
                info("OTP Code For Login [For Testing Purposes]: $user->username - [$code]");
            }

            $user->notifyOTP($code);
            return true;
        }

        $diffTime = vertaTz($user->otp_password_wait_for_code)->diffSeconds(now());
        if ($diffTime <= 0) {
            throw new Exception('خطای غیر منتظره! لطفا دوباره تلاش نمایید.');
        }
        // previous message: 'امکان ارسال مجدد رمز یکبار مصرف پس از ' . $diffTime . ' ثانیه'
        throw new PleaseWaitException('امکان ارسال مجدد رمز یکبار مصرف پس از ۱ دقیقه');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function sendActivationVerificationCode(User $user): bool
    {
        if ($user->shouldSendActivationVerifyCode()) {
            $code = get_random_verification_code(config('market.sms.verify_code_length', 6));
            $user->notifyActivationVerificationCode($code);
            return true;
        }

        $diffTime = vertaTz($user->verify_wait_for_code)->diffSeconds(now());
        if ($diffTime <= 0) {
            throw new Exception('خطای غیر منتظره! لطفا دوباره تلاش نمایید.');
        }
        // previous message: 'امکان ارسال مجدد کد تایید پس از ' . $diffTime . ' ثانیه'
        throw new PleaseWaitException('امکان ارسال مجدد رمز یکبار مصرف پس از ۱ دقیقه');
    }

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function sendForgetPasswordVerificationCode(User $user): bool
    {
        if ($user->shouldSendForgotPasswordVerifyCode()) {
            $code = get_random_verification_code(config('market.sms.verify_code_length', 6));
            $user->notifyForgetPasswordVerificationCode($code);
            return true;
        }

        $diffTime = vertaTz($user->forget_password_wait_for_code)->diffSeconds(now());
        if ($diffTime <= 0) {
            throw new Exception('خطای غیر منتظره! لطفا دوباره تلاش نمایید.');
        }
        // previous message: 'امکان ارسال مجدد کد تایید پس از ' . $diffTime . ' ثانیه'
        throw new PleaseWaitException('امکان ارسال مجدد رمز یکبار مصرف پس از ۱ دقیقه');
    }

    /**
     * @inheritDoc
     */
    public function verifyActivationCode(User $user, string $code): bool
    {
        $res = $user->verification_code === $code;

        if ($res) {
            $user->verified_at = now();
            $status = $user->save();

            if ($status) {
                RegisteredEvent::dispatch($user);
            }
        }

        return $res;
    }

    /**
     * @inheritDoc
     */
    public function verifyForgetPasswordCode(User $user, string $code): bool
    {
        return $user->forget_password_code === $code;
    }
}
