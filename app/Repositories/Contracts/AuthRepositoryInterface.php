<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface AuthRepositoryInterface
{
    /**
     * @param User $user
     * @param string $password
     * @return bool
     */
    public function assignPassword(User $user, string $password): bool;

    /**
     * @param User $user
     * @param string $newPassword
     * @return bool
     */
    public function resetPassword(User $user, string $newPassword): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function sendOTP(User $user): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function sendActivationVerificationCode(User $user): bool;

    /**
     * @param User $user
     * @return bool
     */
    public function sendForgetPasswordVerificationCode(User $user): bool;

    /**
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function verifyActivationCode(User $user, string $code): bool;

    /**
     * @param User $user
     * @param string $code
     * @return bool
     */
    public function verifyForgetPasswordCode(User $user, string $code): bool;
}
