<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

interface AuthServiceInterface extends ServiceInterface
{
    /**
     * @param LoginRequest $request
     * @param bool $isAdmin
     * @return void
     */
    public function login(LoginRequest $request, bool $isAdmin): void;

    /**
     * @return void
     */
    public function logout(): void;

    /**
     * @param User $user
     * @param $password
     * @return bool
     */
    public function assignPassword(User $user, $password): bool;

    /**
     * @param User $user
     * @param $newPassword
     * @return bool
     */
    public function resetPassword(User $user, $newPassword): bool;

    /**
     * @param string $mobile
     * @return bool
     */
    public function sendActivationVerificationCode(string $mobile): bool;

    /**
     * @param string $mobile
     * @return bool
     */
    public function sendForgetPasswordVerificationCode(string $mobile): bool;

    /**
     * @param string $username
     * @param string $code
     * @return bool
     */
    public function verifyActivationCode(string $username, string $code): bool;

    /**
     * @param string $username
     * @param string $code
     * @return bool
     */
    public function verifyForgetPasswordCode(string $username, string $code): bool;

    /**
     * @param string $username
     * @return Model|null
     */
    public function getUserByUsername(string $username): ?Model;
}
