<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserNotificationResource;
use App\Services\Contracts\UserServiceInterface;
use App\Support\Filter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class UserNotificationController extends Controller
{
    /**
     * @param UserServiceInterface $service
     */
    public function __construct(
        protected UserServiceInterface $service
    )
    {
    }

    /**
     * @param Request $request
     * @param Filter $filter
     * @return AnonymousResourceCollection
     */
    public function index(Request $request, Filter $filter): AnonymousResourceCollection
    {
        return UserNotificationResource::collection($this->service->getUserNotifications(
            user: $request->user(),
            filter: $filter
        ));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $res = $this->service->makeAllNotificationsAsRead($request->user());

        if ($res) {
            return response()->json([], ResponseCodes::HTTP_NO_CONTENT);
        }

        return response()->json([
            'type' => ResponseTypesEnum::ERROR->value,
            'message' => 'خطا در تغییر وضعیت اعلان',
        ], ResponseCodes::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function newNotifications(Request $request): AnonymousResourceCollection
    {
        return UserNotificationResource::collection(
            $this->service->getUnreadNotifications($request->user())
        );
    }
}
