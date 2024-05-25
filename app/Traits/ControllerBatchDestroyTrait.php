<?php

namespace App\Traits;

use App\Enums\Responses\ResponseTypesEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

trait ControllerBatchDestroyTrait
{
    /**
     * @var bool
     */
    protected bool $considerDeletable = false;

    /**
     * @var string
     */
    protected string $policyModel;

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function batchDestroy(Request $request): JsonResponse
    {
        Gate::authorize('batchDelete', $this->policyModel);

        $ids = $request->input('ids', []);

        $res = $this->service->batchDeleteByIds($ids, considerDeletable: $this->considerDeletable);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function batchDestroyBySlug(Request $request): JsonResponse
    {
        Gate::authorize('batchDelete', $this->policyModel);

        $slugs = $request->input('ids', []);

        $res = $this->service->batchDeleteBySlugs($slugs, considerDeletable: $this->considerDeletable);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
