<?php

namespace App\Guards;

use Illuminate\Auth\SessionGuard;

class CustomGuard extends SessionGuard
{
    /**
     * @inheritDoc
     */
    public function validate(array $credentials = [])
    {
        $newCredentials = [
            'username' => $credentials['username'],
        ];

        if (isset($credentials['otp_password'])) {
            $newCredentials['otp_password'] = $credentials['otp_password'];
        } else {
            $newCredentials['password'] = $credentials['password'];
        }

        $user = $this->provider->retrieveByCredentials($newCredentials);

        return $this->provider->validateCredentials($user, $newCredentials);
    }
}
