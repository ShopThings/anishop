<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsletterRequest;
use App\Http\Resources\NewsletterResource;
use App\Models\Newsletter;
use App\Services\Contracts\NewsletterServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class NewsletterController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param NewsletterServiceInterface $service
     */
    public function __construct(
        protected NewsletterServiceInterface $service
    )
    {
        $this->policyModel = Newsletter::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Newsletter::class);
        return NewsletterResource::collection($this->service->getMembers($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNewsletterRequest $request
     * @return JsonResponse
     */
    public function store(StoreNewsletterRequest $request): JsonResponse
    {
        Gate::authorize('create', Newsletter::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'شماره همراه ثبت شد.',
                'data' => $model,
            ]);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ثبت شماره همراه',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Display the specified resource.
     *
     * @param Newsletter $newsletter
     * @return NewsletterResource
     */
    public function show(Newsletter $newsletter): NewsletterResource
    {
        Gate::authorize('view', $newsletter);
        return new NewsletterResource($newsletter);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Newsletter $newsletter
     * @return JsonResponse
     */
    public function destroy(Request $request, Newsletter $newsletter): JsonResponse
    {
        Gate::authorize('delete', $newsletter);

        $permanent = $request->user()->id === $newsletter->creator?->id;
        $res = $this->service->deleteById($newsletter->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
