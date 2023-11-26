<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Support\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthService extends Service implements AuthServiceInterface
{
    public function __construct(protected AuthRepositoryInterface $repository)
    {
    }

    /**
     * @inheritDoc
     */
    public function resetPassword($newPassword): bool
    {
        $user = Auth::user();
        if (!$user) return false;
        return $this->repository->resetPassword($user, $newPassword);
    }

    /**
     * @inheritDoc
     */
    public function login(LoginRequest $request, bool $isAdmin): void
    {
        $request->authenticate($isAdmin, $this);
        $request->session()->regenerate();
    }

    /**
     * @inheritDoc
     */
    public function logout(): void
    {
        $user = Auth::user();
        $user->currentAccessToken()->delete();
        Request::session()->regenerate();
    }
}
