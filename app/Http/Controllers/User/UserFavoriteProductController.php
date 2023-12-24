<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFavoriteProductRequest;
use App\Http\Resources\User\UserFavoriteProductResource;
use App\Models\Product;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserFavoriteProductController extends Controller
{
    /**
     * @param UserServiceInterface $service
     */
    public function __construct(
        protected UserServiceInterface $service
    )
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, Filter $filter): AnonymousResourceCollection
    {
        return UserFavoriteProductResource::collection($this->service->getUserFavoriteProduct(
            user: $request->user(),
            filter: $filter
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFavoriteProductRequest $request
     * @return JsonResponse
     */
    public function store(StoreFavoriteProductRequest $request): JsonResponse
    {
        $validated = $request->validated('product');

        $res = $this->service->addFavoriteProduct($request->user()->id, $validated);

        if ($res)
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'محصول به لیست علاقه‌مندی‌های شما اضافه گردید.',
            ], ResponseCodes::HTTP_OK);
        else
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در افزودن به لیست علاقه‌مندی‌ها',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function destroy(Request $request, Product $product): JsonResponse
    {
        $res = $this->service->deleteUserFavoriteProductById($request->user()->id, $product->id);

        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
