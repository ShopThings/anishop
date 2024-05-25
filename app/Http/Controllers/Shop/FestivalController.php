<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFestivalProductRequest;
use App\Http\Requests\StoreFestivalRequest;
use App\Http\Requests\UpdateFestivalRequest;
use App\Http\Resources\FestivalProductResource;
use App\Http\Resources\FestivalResource;
use App\Models\Category;
use App\Models\Festival;
use App\Models\Product;
use App\Services\Contracts\FestivalServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class FestivalController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param FestivalServiceInterface $service
     */
    public function __construct(
        protected FestivalServiceInterface $service
    )
    {
        $this->policyModel = Festival::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Festival::class);
        return FestivalResource::collection($this->service->getFestivals($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(StoreFestivalRequest $request): JsonResponse
    {
        Gate::authorize('create', Festival::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد جشنواره با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد جشنواره',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param Festival $festival
     * @return FestivalResource
     */
    public function show(Festival $festival): FestivalResource
    {
        Gate::authorize('view', $festival);
        return new FestivalResource($festival);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFestivalRequest $request
     * @param Festival $festival
     * @return FestivalResource|JsonResponse
     */
    public function update(UpdateFestivalRequest $request, Festival $festival): FestivalResource|JsonResponse
    {
        Gate::authorize('update', $festival);

        $validated = $request->validated();
        $model = $this->service->updateById($festival->id, $validated);

        if (!is_null($model)) {
            return new FestivalResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش جشنواره',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Festival $festival
     * @return JsonResponse
     */
    public function destroy(Festival $festival): JsonResponse
    {
        Gate::authorize('delete', $festival);

        $res = $this->service->deleteById($festival->id);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Filter $filter
     * @param Festival $festival
     * @return FestivalProductResource
     */
    public function products(Filter $filter, Festival $festival): FestivalProductResource
    {
        Gate::authorize('viewAny', Festival::class);

        return new FestivalProductResource($this->service->getFestivalProducts(
            festivalId: $festival->id,
            filter: $filter
        ));
    }

    /**
     * @param StoreFestivalProductRequest $request
     * @param Festival $festival
     * @return JsonResponse
     */
    public function storeProduct(StoreFestivalProductRequest $request, Festival $festival): JsonResponse
    {
        Gate::authorize('create', Festival::class);

        $validated = $request->validated();
        $model = $this->service->addProductToFestival(
            $validated['product'], $festival->id, $validated['discount_percentage']
        );

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'محصول به جشنواره اضافه شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در افزودن محصول',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param StoreFestivalProductRequest $request
     * @param Festival $festival
     * @return JsonResponse
     */
    public function storeCategoryProducts(StoreFestivalProductRequest $request, Festival $festival): JsonResponse
    {
        Gate::authorize('create', Festival::class);

        $validated = $request->validated();
        $model = $this->service->addCategoryToFestival(
            $validated['category'], $festival->id, $validated['discount_percentage']
        );

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'محصولات دسته‌بندی به جشنواره اضافه شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در افزودن محصولات دسته‌بندی',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Festival $festival
     * @param Product $product
     * @return JsonResponse
     */
    public function destroyProduct(Festival $festival, Product $product): JsonResponse
    {
        Gate::authorize('delete', $festival);

        $res = $this->service->removeProductFromFestival($product->id, $festival->id);
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
     * @param Festival $festival
     * @return JsonResponse
     */
    public function batchDestroyProduct(Request $request, Festival $festival): JsonResponse
    {
        Gate::authorize('delete', $festival);

        $ids = $request->input('ids', []);

        $res = $this->service->removeProductsFromFestival($festival->id, $ids);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Festival $festival
     * @param Category $category
     * @return JsonResponse
     */
    public function batchDestroyCategory(Festival $festival, Category $category): JsonResponse
    {
        Gate::authorize('delete', $festival);

        $res = $this->service->removeCategoryFromFestival($category->id, $festival->id);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
