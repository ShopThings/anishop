<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Http\Resources\UnitResource;
use App\Models\Unit;
use App\Services\Contracts\UnitServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
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
        $this->policyModel = Unit::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Unit::class);
        return UnitResource::collection($this->service->getUnits($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreUnitRequest $request
     * @return JsonResponse
     */
    public function store(StoreUnitRequest $request): JsonResponse
    {
        Gate::authorize('create', Unit::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد واحد محصول با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد واحد محصول',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param Unit $unit
     * @return UnitResource
     */
    public function show(Unit $unit): UnitResource
    {
        Gate::authorize('view', $unit);
        return new UnitResource($unit);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateUnitRequest $request
     * @param Unit $unit
     * @return UnitResource|JsonResponse
     */
    public function update(UpdateUnitRequest $request, Unit $unit): JsonResponse|UnitResource
    {
        Gate::authorize('update', $unit);

        $validated = $request->validated();
        unset($validated['is_deletable']);
        $model = $this->service->updateById($unit->id, $validated);

        if (!is_null($model)) {
            return new UnitResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش واحد محصول',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Unit $unit
     * @return JsonResponse
     */
    public function destroy(Request $request, Unit $unit): JsonResponse
    {
        Gate::authorize('delete', $unit);

        $permanent = $request->user()->id === $unit->creator?->id;
        $res = $this->service->deleteById($unit->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
