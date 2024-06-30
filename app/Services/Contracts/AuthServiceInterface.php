<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface AuthServiceInterface extends ServiceInterface
{
    /**
     * @param Request $request
     * @param string $username
     * @param string $password
     * @param bool $remember
     * @param bool $isAdmin
     * @return string
     */
    public function login(
        Request $request,
        string  $username,
        string  $password,
        bool    $remember = false,
        bool    $isAdmin = false
    ): string;

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
    public function sendOTP(string $mobile): bool;

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
     * @param Request $request
     * @param string $username
     * @param string $code
     * @return string|null
     */
    public function verifyOTP(Request $request, string $username, string $code): ?string;

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
