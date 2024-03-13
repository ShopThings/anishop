<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFaqRequest;
use App\Http\Requests\UpdateFaqRequest;
use App\Http\Resources\FaqResource;
use App\Models\Faq;
use App\Models\User;
use App\Services\Contracts\FaqServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);
        return FaqResource::collection($this->service->getFaqs($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreFaqRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreFaqRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'ایجاد سؤال با موفقیت انجام شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ایجاد سؤال',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Faq $faq
     * @return FaqResource
     * @throws AuthorizationException
     */
    public function show(Faq $faq): FaqResource
    {
        $this->authorize('view', $faq);
        return new FaqResource($faq);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateFaqRequest $request
     * @param Faq $faq
     * @return FaqResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateFaqRequest $request, Faq $faq): FaqResource|JsonResponse
    {
        $this->authorize('update', $faq);

        $validated = $request->validated();
        $model = $this->service->updateById($faq->id, $validated);

        if (!is_null($model)) {
            return new FaqResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش سؤال',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Faq $faq
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Faq $faq): JsonResponse
    {
        $this->authorize('delete', $faq);

        $permanent = $request->user()->id === $faq->creator?->id;
        $res = $this->service->deleteById($faq->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
