<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Services\Contracts\FaqServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class FaqController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param FaqServiceInterface $service
     */
    public function __construct(
        protected FaqServiceInterface $service
    )
    {
        $this->considerDeletable = true;
        $this->policyModel = Faq::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Faq::class);
        return FaqResource::collection($this->service->getFaqs($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFaqRequest $request
     * @return JsonResponse
     */
    public function store(StoreFaqRequest $request): JsonResponse
    {
        Gate::authorize('create', Faq::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد سؤال با موفقیت انجام شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ایجاد سؤال',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param Faq $faq
     * @return FaqResource
     */
    public function show(Faq $faq): FaqResource
    {
        Gate::authorize('view', $faq);
        return new FaqResource($faq);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFaqRequest $request
     * @param Faq $faq
     * @return FaqResource|JsonResponse
     */
    public function update(UpdateFaqRequest $request, Faq $faq): FaqResource|JsonResponse
    {
        Gate::authorize('update', $faq);

        $validated = $request->validated();
        $model = $this->service->updateById($faq->id, $validated);

        if (!is_null($model)) {
            return new FaqResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش سؤال',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Faq $faq
     * @return JsonResponse
     */
    public function destroy(Request $request, Faq $faq): JsonResponse
    {
        Gate::authorize('delete', $faq);

        $permanent = $request->user()->id === $faq->creator?->id;
        $res = $this->service->deleteById($faq->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
