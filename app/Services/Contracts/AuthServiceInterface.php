<?php

namespace App\Services\Contracts;

use App\Contracts\ServiceInterface;
use App\Http\Requests\Auth\LoginRequest;

interface AuthServiceInterface extends ServiceInterface
{
    /**
     * @param $newPassword
     * @return bool
     */
    public function resetPassword($newPassword): bool;

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
}
