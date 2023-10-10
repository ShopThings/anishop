<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreColorRequest;
use App\Http\Requests\UpdateColorRequest;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use App\Models\User;
use App\Services\Contracts\ColorServiceInterface;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ColorController extends Controller
{
    use ControllerPaginateTrait;

    /**
     * @param ColorServiceInterface $service
     */
    public function __construct(
        protected ColorServiceInterface $service
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

        return ColorResource::collection($this->service->getColors(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreColorRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreColorRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد رنگ با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد رنگ',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Color $color
     * @return ColorResource
     * @throws AuthorizationException
     */
    public function show(Color $color)
    {
        $this->authorize('view', $color);
        return new ColorResource($color);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateColorRequest $request
     * @param Color $color
     * @return ColorResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateColorRequest $request, Color $color)
    {
        $this->authorize('update', $color);

        $validated = $request->validated();
        unset($validated['is_deletable']);
        $model = $this->service->updateById($color->id, $validated);

        if (!is_null($model)) {
            return new ColorResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش رنگ',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Color $color
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Color $color)
    {
        $this->authorize('delete', $color);

        $permanent = $request->user()->id === $color->creator()?->id;
        $res = $this->service->deleteById($color->id, $permanent);
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
    public function batchDestroy(Request $request)
    {
        $this->authorize('batchDelete', User::class);

        $ids = $request->input('ids', []);

        $res = $this->service->batchDeleteByIds($ids);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
