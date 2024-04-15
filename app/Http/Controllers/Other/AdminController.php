<?php

namespace App\Http\Controllers\Other;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderCountResource;
use App\Models\Order;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Services\Contracts\ComplaintServiceInterface;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Services\Contracts\ReturnOrderServiceInterface;
use App\Support\Gate\PermissionHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    /**
     * @param Request $request
     * @param ReturnOrderServiceInterface $returnOrderService
     * @param ProductCommentServiceInterface $productCommentService
     * @param BlogCommentServiceInterface $blogCommentService
     * @param ContactUsServiceInterface $contactUsService
     * @param ComplaintServiceInterface $complaintService
     * @return JsonResponse
     */
    public function alertCounting(
        Request                        $request,
        ReturnOrderServiceInterface    $returnOrderService,
        ProductCommentServiceInterface $productCommentService,
        BlogCommentServiceInterface    $blogCommentService,
        ContactUsServiceInterface      $contactUsService,
        ComplaintServiceInterface      $complaintService,
    ): JsonResponse
    {
        $user = $request->user();
        $data = [];

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::RETURN_ORDER_REQUEST)
        )) {
            $data['return_order_count'] = $returnOrderService->getNotSeenRequestsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::PRODUCT_COMMENT)
        )) {
            $data['product_comment_count'] = $productCommentService->getNotSeenCommentsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::BLOG_COMMENT)
        )) {
            $data['blog_comment_count'] = $blogCommentService->getNotSeenCommentsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::CONTACT_US)
        )) {
            $data['contact_count'] = $contactUsService->getNotSeenContactsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::COMPLAINT)
        )) {
            $data['complaint_count'] = $complaintService->getNotSeenComplaintsCount();
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $data,
        ]);
    }

    /**
     * @param OrderServiceInterface $service
     * @return AnonymousResourceCollection
     */
    public function orderCounting(OrderServiceInterface $service): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Order::class);
        return OrderCountResource::collection($service->getOrdersCountWithBadges());
    }
}
