<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaticPageRequest;
use App\Http\Requests\UpdateStaticPageRequest;
use App\Http\Resources\StaticPageResource;
use App\Models\StaticPage;
use App\Services\Contracts\StaticPageServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class StaticPageController extends Controller
{
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
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', StaticPage::class);
        return StaticPageResource::collection($this->service->getPages($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreStaticPageRequest $request
     * @return JsonResponse
     */
    public function store(StoreStaticPageRequest $request): JsonResponse
    {
        Gate::authorize('create', StaticPage::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد صفحه با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد صفحه',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param StaticPage $staticPage
     * @return StaticPageResource
     */
    public function show(StaticPage $staticPage): StaticPageResource
    {
        Gate::authorize('view', $staticPage);
        return new StaticPageResource($staticPage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateStaticPageRequest $request
     * @param StaticPage $staticPage
     * @return StaticPageResource|JsonResponse
     */
    public function update(
        UpdateStaticPageRequest $request,
        StaticPage              $staticPage
    ): JsonResponse|StaticPageResource
    {
        Gate::authorize('update', $staticPage);

        $validated = $request->validated();
        unset($validated['is_deletable']);
        $model = $this->service->updateById($staticPage->id, $validated);

        if (!is_null($model)) {
            return new StaticPageResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش رنگ',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param StaticPage $staticPage
     * @return JsonResponse
     */
    public function destroy(Request $request, StaticPage $staticPage): JsonResponse
    {
        Gate::authorize('delete', $staticPage);

        $permanent = $request->user()->id === $staticPage->creator?->id;
        $res = $this->service->deleteById($staticPage->id, $permanent);
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
     * @return JsonResponse
     */
    public function batchDestroyByUrl(Request $request): JsonResponse
    {
        Gate::authorize('batchDelete', StaticPage::class);

        $urls = $request->input('ids', []);

        $res = $this->service->batchDeleteByUrls($urls, considerDeletable: true);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
