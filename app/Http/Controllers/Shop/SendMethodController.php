<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSendMethodRequest;
use App\Http\Requests\UpdateSendMethodRequest;
use App\Http\Resources\SendMethodResource;
use App\Models\SendMethod;
use App\Models\User;
use App\Services\Contracts\SendMethodServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class SendMethodController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param SendMethodServiceInterface $service
     */
    public function __construct(
        protected readonly SendMethodServiceInterface $service
    )
    {
        $this->considerDeletable = true;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return SendMethodResource::collection($this->service->getMethods($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSendMethodRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreSendMethodRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد روش ارسال با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد روش ارسال',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param SendMethod $sendMethod
     * @return SendMethodResource
     * @throws AuthorizationException
     */
    public function show(SendMethod $sendMethod): SendMethodResource
    {
        $this->authorize('view', $sendMethod);
        return new SendMethodResource($sendMethod);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSendMethodRequest $request
     * @param SendMethod $sendMethod
     * @return SendMethodResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(
        UpdateSendMethodRequest $request,
        SendMethod              $sendMethod
    ): SendMethodResource|JsonResponse
    {
        $this->authorize('update', $sendMethod);

        $validated = $request->validated();
        unset($validated['code']);
        unset($validated['is_deletable']);

        $model = $this->service->updateById($sendMethod->id, $validated);

        if (!is_null($model)) {
            return new SendMethodResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش روش ارسال',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param SendMethod $sendMethod
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, SendMethod $sendMethod): JsonResponse
    {
        $this->authorize('delete', $sendMethod);

        $permanent = $request->user()->id === $sendMethod->creator?->id;
        $res = $this->service->deleteById($sendMethod->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
