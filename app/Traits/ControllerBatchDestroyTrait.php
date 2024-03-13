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
     * @var bool
     */
    protected bool $considerDeletable = false;

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function batchDestroy(Request $request): JsonResponse
    {
        $this->authorize('batchDelete', User::class);

        $ids = $request->input('ids', []);

        $res = $this->service->batchDeleteByIds($ids, considerDeletable: $this->considerDeletable);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function batchDestroyBySlug(Request $request): JsonResponse
    {
        $this->authorize('batchDelete', User::class);

        $slugs = $request->input('ids', []);

        $res = $this->service->batchDeleteBySlugs($slugs, considerDeletable: $this->considerDeletable);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
