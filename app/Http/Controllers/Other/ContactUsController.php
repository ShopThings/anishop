<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateContactUsRequest;
use App\Http\Resources\ContactUsResource;
use App\Models\ContactUs;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ContactUsController extends Controller
{
    use ControllerBatchDestroyTrait;

    /**
     * @param ContactUsServiceInterface $service
     */
    public function __construct(
        protected ContactUsServiceInterface $service
    )
    {
        $this->policyModel = ContactUs::class;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Filter $filter): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', ContactUs::class);
        return ContactUsResource::collection($this->service->getContacts($filter));
    }

    /**
     * Display the specified resource.
     *
     * @param ContactUs $contact
     * @return ContactUsResource
     */
    public function show(ContactUs $contact): ContactUsResource
    {
        Gate::authorize('view', $contact);
        return new ContactUsResource($contact);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateContactUsRequest $request
     * @param ContactUs $contact
     * @return ContactUsResource|JsonResponse
     */
    public function update(UpdateContactUsRequest $request, ContactUs $contact): JsonResponse|ContactUsResource
    {
        Gate::authorize('update', $contact);

        $validated = $request->validated([
            'answer',
            'is_seen',
        ]);
        $model = $this->service->updateById($contact->id, $validated);

        if (!is_null($model)) {
            return new ContactUsResource($model);
        }
        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در ویرایش تماس',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param ContactUs $contact
     * @return JsonResponse
     */
    public function destroy(Request $request, ContactUs $contact): JsonResponse
    {
        Gate::authorize('delete', $contact);

        $permanent = $request->user()->id === $contact->creator?->id;
        $res = $this->service->deleteById($contact->id, $permanent);
        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
