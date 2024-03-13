<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Showing\UserAuthShowResource;
use App\Services\Contracts\AuthServiceInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class AuthController extends Controller
{
    /**
     * @param LoginRequest $request
     * @param AuthServiceInterface $service
     * @return JsonResponse
     */
    public function login(LoginRequest $request, AuthServiceInterface $service): JsonResponse
    {
        $isFromAdmin = Route::is('api.admin.login');
        $service->login($request, $isFromAdmin);
        $tokenName = $isFromAdmin ? config('market.token_name.admin') : config('market.token_name.main');
        $user = Auth::user();
        $expireAt = Carbon::now()->addDays(30);
        $token = $user->createToken(name: $tokenName, expiresAt: $expireAt)->plainTextToken;
        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => [
                'user' => new UserAuthShowResource($user),
                'token' => $token,
            ],
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function logout(AuthServiceInterface $service): JsonResponse
    {
        $service->logout();
        return response()->json(status: ResponseCodes::HTTP_NO_CONTENT);
    }
}
