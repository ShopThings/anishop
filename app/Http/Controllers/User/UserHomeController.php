<?php

namespace App\Http\Controllers\User;

use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Services\Contracts\ReturnOrderServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserHomeController extends Controller
{
    /**
     * @param Request $request
     * @param OrderServiceInterface $orderService
     * @param ReturnOrderServiceInterface $returnOrderService
     * @param ProductCommentServiceInterface $productCommentService
     * @param BlogCommentServiceInterface $blogCommentService
     * @param UserServiceInterface $userService
     * @param ContactUsServiceInterface $contactUsService
     * @return JsonResponse
     */
    public function countOfItems(
        Request                        $request,
        OrderServiceInterface          $orderService,
        ReturnOrderServiceInterface    $returnOrderService,
        ProductCommentServiceInterface $productCommentService,
        BlogCommentServiceInterface    $blogCommentService,
        UserServiceInterface           $userService,
        ContactUsServiceInterface      $contactUsService,
    ): JsonResponse
    {
        $user = $request->user();

        $orderCount = $orderService->getUserOrdersCount($user->id);
        $returnOrderCount = $returnOrderService->getUserOrdersCount($user->id);
        $productCommentCount = $productCommentService->getUserCommentsCount($user->id);
        $blogCommentCount = $blogCommentService->getUserCommentsCount($user->id);
        $addressCount = $userService->getUserAddressesCount($user->id);
        $favoriteProductCount = $userService->getUserFavoriteProductsCount($user->id);
        $contactCount = $contactUsService->getContactsCount($user->id);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => [
                'order_count' => $orderCount,
                'return_order_count' => $returnOrderCount,
                'product_comment_count' => $productCommentCount,
                'blog_comment_count' => $blogCommentCount,
                'address_count' => $addressCount,
                'favorite_product_count' => $favoriteProductCount,
                'contact_count' => $contactCount,
            ],
        ]);
    }
}
