<?php

namespace App\Services;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\AuthServiceInterface;
use App\Support\Service;
use App\Support\WhereBuilder\WhereBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $where = new WhereBuilder('users');
        $where->whereEqual('username', $username);

        $user = $this->userRepository->findWhere($where->build());

        if (!$user instanceof Model) return null;
        return $user;
    }
}
