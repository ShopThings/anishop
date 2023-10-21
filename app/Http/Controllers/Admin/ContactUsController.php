<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateContactUsRequest;
use App\Http\Resources\ContactUsResource;
use App\Models\ContactUs;
use App\Models\User;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Traits\ControllerBatchDestroyTrait;
use App\Traits\ControllerPaginateTrait;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class ContactUsController extends Controller
{
    use ControllerPaginateTrait,
        ControllerBatchDestroyTrait;

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
     * @param Request $request
     * @return AnonymousResourceCollection
     * @throws AuthorizationException
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $params = $this->getPaginateParameters($request);

        return ContactUsResource::collection($this->service->getContacts(
            searchText: $params['text'], limit: $params['limit'], page: $params['page'], order: $params['order']
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param ContactUs $contactUs
     * @return ContactUsResource
     * @throws AuthorizationException
     */
    public function show(ContactUs $contactUs)
    {
        $this->authorize('view', $contactUs);
        return new ContactUsResource($contactUs);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateContactUsRequest $request
     * @param ContactUs $contactUs
     * @return ContactUsResource|JsonResponse
     * @throws AuthorizationException
     */
    public function update(UpdateContactUsRequest $request, ContactUs $contactUs)
    {
        $this->authorize('update', $contactUs);

        $validated = $request->validated(['is_seen']);
        $model = $this->service->updateById($contactUs->id, $validated);

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
     * @param ContactUs $contactUs
     * @return JsonResponse
     * @throws AuthorizationException
     */
    public function destroy(Request $request, ContactUs $contactUs)
    {
        $this->authorize('delete', $contactUs);

        $permanent = $request->user()->id === $contactUs->creator()?->id;
        $res = $this->service->deleteById($contactUs->id, $permanent);
        if ($res)
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        else
            return response()->json([
                'type' => ResponseTypesEnum::WARNING->value,
                'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
            ], ResponseCodes::HTTP_UNPROCESSABLE_ENTITY);
    }
}
