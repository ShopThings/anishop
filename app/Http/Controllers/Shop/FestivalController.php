<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFestivalProductRequest;
use App\Http\Resources\FestivalResource;
use App\Models\Category;
use App\Models\Festival;
use App\Models\Product;
use App\Models\User;
use App\Services\Contracts\FestivalServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class FestivalController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

    /**
     * @param FestivalServiceInterface $service
     */
    public function __construct(
        protected FestivalServiceInterface $service
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

        return FestivalResource::collection($this->service->getFestivals(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد جشنواره با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد جشنواره',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Festival $festival
     * @return FestivalResource
     * @throws AuthorizationException
     */
    public function show(Festival $festival)
    {
        $this->authorize('view', $festival);
        return new FestivalResource($festival);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Festival $festival
     * @return FestivalResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(Request $request, Festival $festival)
    {

        $this->authorize('update', $festival);

        $validated = $request->validated();
        $model = $this->service->updateById($festival->id, $validated);

        if (!is_null($model)) {
            return new FestivalResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش جشنواره',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Festival $festival
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Festival $festival)
    {
        $this->authorize('delete', $festival);

        $res = $this->service->deleteById($festival->id);
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
     * @param Festival $festival
     * @return FestivalResource
     * @throws AuthorizationException
     */
    public function products(Request $request, Festival $festival)
    {
        $this->authorize('viewAny', User::class);

        $params = $this->getPaginateParameters($request);

        return new FestivalResource($this->service->getFestivalProducts(
            festivalId: $festival->id,
            searchText: $params['text'],
            limit: $params['limit'],
            page: $params['page'],
            order: $params['order']
        ));
    }

    /**
     * @param StoreFestivalProductRequest $request
     * @param Festival $festival
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function storeProduct(StoreFestivalProductRequest $request, Festival $festival)
    {
        $this->authorize('create', User::class);

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
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در افزودن محصول',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param StoreFestivalProductRequest $request
     * @param Festival $festival
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function storeCategoryProducts(StoreFestivalProductRequest $request, Festival $festival)
    {
        $this->authorize('create', User::class);

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
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در افزودن محصولات دسته‌بندی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * @param Festival $festival
     * @param Product $product
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroyProduct(Festival $festival, Product $product)
    {
        $this->authorize('delete', $festival);

        $res = $this->service->removeProductFromFestival($product->id, $festival->id);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * @param Festival $festival
     * @param Category $category
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function batchDestroyProduct(Festival $festival, Category $category)
    {
        $this->authorize('delete', $festival);

        $res = $this->service->removeCategoryFromFestival($category->id, $festival->id);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
