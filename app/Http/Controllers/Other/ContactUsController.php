<?php

namespace App\Http\Controllers\Other;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateContactUsRequest;
use App\Http\Resources\ContactUsResource;
use App\Models\ContactUs;
use App\Models\User;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Support\Filter;
use App\Traits\ControllerBatchDestroyTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
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
        return ContactUsResource::collection($this->service->getContacts($filter));
    }

    /**
     * Display the specified resource.
     *
     * @param ContactUs $contact
     * @return ContactUsResource
     * @throws AuthorizationException
     */
    public function show(ContactUs $contact): ContactUsResource
    {
        $this->authorize('view', $contact);
        return new ContactUsResource($contact);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateContactUsRequest $request
     * @param ContactUs $contact
     * @return ContactUsResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateContactUsRequest $request, ContactUs $contact): JsonResponse|ContactUsResource
    {
        $this->authorize('update', $contact);

        $validated = $request->validated([
            'answer',
            'is_seen',
        ]);
        $model = $this->service->updateById($contact->id, $validated);

        if (!is_null($model)) {
            return new ContactUsResource($model);
        } else {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'خطا در ویرایش تماس',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param ContactUs $contact
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, ContactUs $contact): JsonResponse
    {
        $this->authorize('delete', $contact);

        $permanent = $request->user()->id === $contact->creator?->id;
        $res = $this->service->deleteById($contact->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
