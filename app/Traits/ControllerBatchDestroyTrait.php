<?php

namespace App\Traits;

use App\Enums\Responses\ResponseTypesEnum;
use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

trait ControllerBatchDestroyTrait
{
    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function batchDestroy(Request $request): JsonResponse
    {
        $this->authorize('batchDelete', User::class);

        $ids = $request->input('ids', []);

        $res = $this->service->batchDeleteByIds($ids);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
