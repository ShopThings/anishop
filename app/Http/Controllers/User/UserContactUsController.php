<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserContactUsResource;
use App\Http\Resources\User\UserContactUsSingleResource;
use App\Models\ContactUs;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserContactUsController extends Controller
{
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
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, Filter $filter): AnonymousResourceCollection
    {
        return UserContactUsResource::collection($this->service->getUserContacts(
            userId: $request->user()->id,
            filter: $filter
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param ContactUs $contact
     * @return UserContactUsSingleResource|JsonResponse
     */
    public function show(Request $request, ContactUs $contact): JsonResponse|UserContactUsSingleResource
    {
        if ($check = $this->_checkAuthorization($request->user(), $contact)) return $check;

        return new UserContactUsSingleResource($contact);
    }

    /**
     * @param $user
     * @param ContactUs $contact
     * @return JsonResponse|null
     */
    private function _checkAuthorization($user, ContactUs $contact): ?JsonResponse
    {
        if ($user->id !== $contact->user_id) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'دسترسی غیر مجاز به پیام',
            ], ResponseCodes::HTTP_UNAUTHORIZED);
        }
        return null;
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
        if ($check = $this->_checkAuthorization($request->user(), $contact)) return $check;

        $res = $this->service->deleteUserContactById($contact->id, false);

        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }
        return response()->json([
            'type' => ResponseTypesEnum::WARNING->value,
            'message' => 'عملیات مورد نظر قابل انجام نمی‌باشد.',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }
}
