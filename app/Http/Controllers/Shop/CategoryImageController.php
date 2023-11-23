<?php

namespace App\Http\Controllers\Shop;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryImageRequest;
use App\Http\Requests\UpdateCategoryImageRequest;
use App\Http\Resources\CategoryImageResource;
use App\Models\CategoryImage;
use App\Models\User;
use App\Services\Contracts\CategoryImageServiceInterface;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class CategoryImageController extends Controller
{
    use ControllerPaginateTrait;

    /**
     * @param CategoryImageServiceInterface $service
     */
    public function __construct(
        protected CategoryImageServiceInterface $service
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

        return CategoryImageResource::collection($this->service->getCategoryImages(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryImageRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد تصویر دسته‌بندی با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد تصویر دسته‌بندی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param CategoryImage $categoryImage
     * @return CategoryImageResource
     * @throws AuthorizationException
     */
    public function show(CategoryImage $categoryImage)
    {

        $this->authorize('view', $categoryImage);
        return new CategoryImageResource($categoryImage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCategoryImageRequest $request
     * @param CategoryImage $categoryImage
     * @return CategoryImageResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateCategoryImageRequest $request, CategoryImage $categoryImage)
    {
        $this->authorize('update', $categoryImage);

        $validated = $request->validated();
        $model = $this->service->updateById($categoryImage->id, $validated);

        if (!is_null($model)) {
            return new CategoryImageResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش تصویر دسته‌بندی',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param CategoryImage $categoryImage
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, CategoryImage $categoryImage)
    {
        $this->authorize('delete', $categoryImage);

        $permanent = $request->user()->id === $categoryImage->creator()?->id;
        $res = $this->service->deleteById($categoryImage->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
