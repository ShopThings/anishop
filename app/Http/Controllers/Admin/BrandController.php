<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Models\Brand;
use App\Models\User;
use App\Services\Contracts\BrandServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class BrandController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

    /**
     * @param BrandServiceInterface $service
     */
    public function __construct(
        protected BrandServiceInterface $service
    )
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $params = $this->getPaginateParameters($request);

        return BrandResource::collection($this->service->getBrands(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreBrandRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreBrandRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد برند با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد برند',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Brand $brand
     * @return BrandResource
     * @throws AuthorizationException
     */
    public function show(Brand $brand)
    {
        $this->authorize('view', $brand);
        return new BrandResource($brand);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateBrandRequest $request
     * @param Brand $brand
     * @return BrandResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $this->authorize('update', $brand);

        $validated = $request->validated();
        unset($validated['escaped_name']);
        unset($validated['slug']);
        unset($validated['is_deletable']);
        $model = $this->service->updateById($brand->id, $validated);

        if (!is_null($model)) {
            return new BrandResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش برند',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Brand $brand
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Brand $brand)
    {
        $this->authorize('delete', $brand);

        $permanent = $request->user()->id === $brand->creator()?->id;
        $res = $this->service->deleteById($brand->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
