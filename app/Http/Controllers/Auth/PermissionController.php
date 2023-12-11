<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Services\Contracts\RoleServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class PermissionController extends Controller
{
    /**
     * @param RoleServiceInterface $service
     * @return JsonResponse
     */
    public function index(RoleServiceInterface $service): JsonResponse
    {
        $permissions = $service->getPermissions();
        $status = ResponseCodes::HTTP_OK;
        if (count($permissions)) {
            $res = [
                'type' => ResponseTypesEnum::SUCCESS->value,
                'data' => $permissions,
            ];
        } else {
            $res = [
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'دسترسی‌های لازم برای واکشی دسترسی‌های موجود را دارا نمی‌باشید.',
            ];
            $status = ResponseCodes::HTTP_UNPROCESSABLE_ENTITY;
        }
        return response()->json($res, $status);
    }
}
