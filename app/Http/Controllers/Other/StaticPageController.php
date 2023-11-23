<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaticPageRequest;
use App\Http\Requests\UpdateStaticPageRequest;
use App\Http\Resources\StaticPageResource;
use App\Models\StaticPage;
use App\Models\User;
use App\Services\Contracts\StaticPageServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class StaticPageController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

    /**
     * @param StaticPageServiceInterface $service
     */
    public function __construct(
        protected StaticPageServiceInterface $service
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

        return StaticPageResource::collection($this->service->getPages(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreStaticPageRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreStaticPageRequest $request)
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد صفحه با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد صفحه',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param StaticPage $staticPage
     * @return StaticPageResource
     * @throws AuthorizationException
     */
    public function show(StaticPage $staticPage)
    {
        $this->authorize('view', $staticPage);
        return new StaticPageResource($staticPage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStaticPageRequest $request
     * @param StaticPage $staticPage
     * @return StaticPageResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateStaticPageRequest $request, StaticPage $staticPage)
    {
        $this->authorize('update', $staticPage);

        $validated = $request->validated();
        unset($validated['is_deletable']);
        $model = $this->service->updateById($staticPage->id, $validated);

        if (!is_null($model)) {
            return new StaticPageResource($model);
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
     * @param StaticPage $staticPage
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, StaticPage $staticPage)
    {
        $this->authorize('delete', $staticPage);

        $permanent = $request->user()->id === $staticPage->creator()?->id;
        $res = $this->service->deleteById($staticPage->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
