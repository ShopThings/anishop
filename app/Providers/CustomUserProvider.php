<?php

namespace App\Providers;

use Exception;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class CustomUserProvider extends EloquentUserProvider
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'] ?? null;
        $hashed = $user->getAuthPassword();

        if (isset($credentials['otp_password'])) {
            $plain = $credentials['otp_password'];
            $hashed = $user->otp_password;
        }

        if ((empty($plain) || empty($hashed)) && app()->isProduction()) {
            throw new Exception('خطا در بخش سرور، لطفا با پشتیبانی تماس بگیرید.');
        }

        // Default password field
        return $this->hasher->check($plain, $hashed);
    }
}
