<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Models\User;
use App\Services\Contracts\UnitServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UnitController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param UnitServiceInterface $service
     */
    public function __construct(
        protected UnitServiceInterface $service
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
        return UnitResource::collection($this->service->getUnits($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUnitRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreUnitRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد واحد محصول با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد واحد محصول',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Unit $unit
     * @return UnitResource
     * @throws AuthorizationException
     */
    public function show(Unit $unit): UnitResource
    {
        $this->authorize('view', $unit);
        return new UnitResource($unit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUnitRequest $request
     * @param Unit $unit
     * @return UnitResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateUnitRequest $request, Unit $unit): JsonResponse|UnitResource
    {
        $this->authorize('update', $unit);

        $validated = $request->validated();
        unset($validated['is_deletable']);
        $model = $this->service->updateById($unit->id, $validated);

        if (!is_null($model)) {
            return new UnitResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش واحد محصول',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Unit $unit
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Unit $unit): JsonResponse
    {
        $this->authorize('delete', $unit);

        $permanent = $request->user()->id === $unit->creator?->id;
        $res = $this->service->deleteById($unit->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
