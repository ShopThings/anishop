<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsletterRequest;
use App\Http\Resources\NewsletterResource;
use App\Models\Newsletter;
use App\Models\User;
use App\Services\Contracts\NewsletterServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        return NewsletterResource::collection($this->service->getMembers($filter));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreNewsletterRequest $request
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function store(StoreNewsletterRequest $request): JsonResponse
    {
        $this->authorize('create', User::class);

        $validated = $request->validated();
        $model = $this->service->create($validated);

        if (!is_null($model)) {
            return response()->json([
                'type' => ResponseTypesEnum::SUCCESS->value,
                'message' => 'شماره همراه ثبت شد.',
                'data' => $model,
            ]);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ثبت شماره همراه',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Newsletter $newsletter
     * @return NewsletterResource
     * @throws AuthorizationException
     */
    public function show(Newsletter $newsletter): NewsletterResource
    {
        $this->authorize('view', $newsletter);
        return new NewsletterResource($newsletter);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param Newsletter $newsletter
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, Newsletter $newsletter): JsonResponse
    {
        $this->authorize('delete', $newsletter);

        $permanent = $request->user()->id === $newsletter->creator()?->id;
        $res = $this->service->deleteById($newsletter->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
