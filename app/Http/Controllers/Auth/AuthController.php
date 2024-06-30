<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Showing\UserAuthShowResource;
use App\Models\User;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\RoleServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @param AuthServiceInterface $service
     * @param RoleServiceInterface $roleService
     * @return JsonResponse
     */
    public function login(
        LoginRequest         $request,
        AuthServiceInterface $service,
        RoleServiceInterface $roleService
    ): JsonResponse
    {
        $isFromAdmin = Route::is('api.admin.login');
        $token = $service->login(
            $request,
            $request->input('username'),
            $request->input('password'),
            $request->boolean('remember'),
            $isFromAdmin
        );

        /**
         * @var User $user
         */
        $user = Auth::user();

        $data = [
            'user' => new UserAuthShowResource($user),
            'token' => $token,
        ];

        if ($isFromAdmin) {
            $permissions = $roleService->getUserPermissions($user);
            $data['permissions'] = $permissions;
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $data,
        ]);
    }

    /**
     * @param AuthServiceInterface $service
     * @return JsonResponse
     */
    public function logout(AuthServiceInterface $service): JsonResponse
    {
        $service->logout();
        return response()->json(status: ResponseCodes::HTTP_NO_CONTENT);
    }
}
