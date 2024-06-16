<?php

namespace App\Http\Controllers\Other;

use App\Enums\Charts\ChartPeriodsEnum;
use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Orders\ReturnOrderStatusesEnum;
use App\Enums\Responses\ResponseTypesEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\MostSaleProductResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\ReturnOrderRequest;
use App\Models\User;
use App\Services\Contracts\BlogBadgeServiceInterface;
use App\Services\Contracts\BlogCategoryServiceInterface;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Services\Contracts\BlogServiceInterface;
use App\Services\Contracts\BrandServiceInterface;
use App\Services\Contracts\CategoryServiceInterface;
use App\Services\Contracts\CityPostPriceServiceInterface;
use App\Services\Contracts\ColorServiceInterface;
use App\Services\Contracts\ComplaintServiceInterface;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Services\Contracts\CouponServiceInterface;
use App\Services\Contracts\FaqServiceInterface;
use App\Services\Contracts\FestivalServiceInterface;
use App\Services\Contracts\MenuServiceInterface;
use App\Services\Contracts\NewsletterServiceInterface;
use App\Services\Contracts\OrderBadgeServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\PaymentMethodServiceInterface;
use App\Services\Contracts\PeriodicServiceInterface;
use App\Services\Contracts\ProductAttributeCategoryServiceInterface;
use App\Services\Contracts\ProductAttributeServiceInterface;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\ReturnOrderServiceInterface;
use App\Services\Contracts\SendMethodServiceInterface;
use App\Services\Contracts\SliderServiceInterface;
use App\Services\Contracts\StaticPageServiceInterface;
use App\Services\Contracts\UnitServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Contracts\WeightPostPriceServiceInterface;
use App\Support\Gate\PermissionHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response as ResponseCodes;

class AdminDashboardController extends Controller
{
    /**
     * @param PeriodicServiceInterface $service
     * @param OrderBadgeServiceInterface $badgeService
     */
    public function __construct(
        protected PeriodicServiceInterface   $service,
        protected OrderBadgeServiceInterface $badgeService
    )
    {
    }

    /**
     * @return JsonResponse
     */
    public function totalSale(): JsonResponse
    {
        Gate::authorize('viewAny', Order::class);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getTotalSale(),
        ]);
    }

    /**
     * @param string $period
     * @return JsonResponse
     */
    public function periodSale(string $period): JsonResponse
    {
        Gate::authorize('viewAny', Order::class);

        $period = $this->getValidatedPeriod($period);

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $this->service->getPeriodSale($period),
        ]);
    }

    /**
     * @param string $period
     * @return ChartPeriodsEnum
     */
    private function getValidatedPeriod(string $period): ChartPeriodsEnum
    {
        $period = ChartPeriodsEnum::tryFrom($period);

        if (is_null($period)) {
            return ChartPeriodsEnum::TODAY;
        }

        return $period;
    }

    /**
     * @param string $period
     * @return JsonResponse
     */
    public function chartUsers(string $period): JsonResponse
    {
        Gate::authorize('viewAny', User::class);

        $period = $this->getValidatedPeriod($period);
        $usersCount = $this->service->getPeriodUsersCount($period);
        $datasetLabel = 'تعداد عضویت کاربران (' . (ChartPeriodsEnum::getTranslations($period) ?? 'دوره نامشخص') . ')';

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'labels' => $usersCount['labels'],
            'dataset_label' => $datasetLabel,
            'data' => $usersCount['data'],
        ]);
    }

    /**
     * @param string $period
     * @param string $status
     * @return JsonResponse
     */
    public function chartOrders(string $period, string $status): JsonResponse
    {
        Gate::authorize('viewAny', Order::class);

        $period = $this->getValidatedPeriod($period);
        $ordersCount = $this->service->getPeriodOrdersCount($period, $status);

        if (empty($ordersCount['send_status_title'])) {
            $badge = $this->badgeService->getBadgeByCode($status);

            $statusTitle = $badge?->title ?? 'وضعیت نامشخص';
            $statusColor = $badge?->color_hex ?? '#000000';
        } else {
            $statusTitle = $ordersCount['send_status_title'];
            $statusColor = $ordersCount['send_status_color_hex'];
        }

        $datasetLabel = $statusTitle . ' (' . (ChartPeriodsEnum::getTranslations($period) ?? 'دوره نامشخص') . ')';

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'labels' => $ordersCount['labels'],
            'dataset_label' => $datasetLabel,
            'background_color' => $statusColor,
            'data' => $ordersCount['data'],
        ]);
    }

    /**
     * @param string $period
     * @param string $status
     * @return JsonResponse
     */
    public function chartReturnOrders(string $period, string $status): JsonResponse
    {
        Gate::authorize('viewAny', ReturnOrderRequest::class);

        $status = ReturnOrderStatusesEnum::tryFrom($status);

        if (is_null($status)) {
            return response()->json([
                'type' => ResponseTypesEnum::ERROR->value,
                'message' => 'وضعیت ارسال شده نامعتبر می‌باشد.',
            ], ResponseCodes::HTTP_NOT_ACCEPTABLE);
        }

        $period = $this->getValidatedPeriod($period);
        $returnOrdersCount = $this->service->getPeriodReturnOrdersCount($period, $status);

        $statusTitle = ReturnOrderStatusesEnum::getTranslations($status) ?? 'وضعیت نامشخص';
        $statusColor = ReturnOrderStatusesEnum::getStatusColor()[$status->value] ?? '#000000';
        $datasetLabel = $statusTitle . ' (' . (ChartPeriodsEnum::getTranslations($period) ?? 'دوره نامشخص') . ')';

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'labels' => $returnOrdersCount['labels'],
            'dataset_label' => $datasetLabel,
            'background_color' => $statusColor,
            'data' => $returnOrdersCount['data'],
        ]);
    }

    /**
     * @param string $period
     * @return AnonymousResourceCollection
     */
    public function tableMostSaleProducts(string $period): AnonymousResourceCollection
    {
        Gate::authorize('viewAny', Product::class);

        $period = $this->getValidatedPeriod($period);

        return MostSaleProductResource::collection($this->service->getPeriodMostSaleProductsCount($period));
    }

    /**
     * @param Request $request
     * @param UserServiceInterface $userService
     * @param PaymentMethodServiceInterface $paymentMethodService
     * @param SendMethodServiceInterface $sendMethodService
     * @param ColorServiceInterface $colorService
     * @param BrandServiceInterface $brandService
     * @param CategoryServiceInterface $categoryService
     * @param FestivalServiceInterface $festivalService
     * @param UnitServiceInterface $unitService
     * @param CouponServiceInterface $couponService
     * @param ProductServiceInterface $productService
     * @param ProductAttributeServiceInterface $productAttributeService
     * @param ProductCommentServiceInterface $productCommentService
     * @param OrderServiceInterface $orderService
     * @param OrderBadgeServiceInterface $orderBadgeService
     * @param ReturnOrderServiceInterface $returnOrderService
     * @param BlogServiceInterface $blogService
     * @param BlogCategoryServiceInterface $blogCategoryService
     * @param BlogCommentServiceInterface $blogCommentService
     * @param StaticPageServiceInterface $staticPageService
     * @param ContactUsServiceInterface $contactService
     * @param ComplaintServiceInterface $complaintService
     * @param FaqServiceInterface $faqService
     * @param NewsletterServiceInterface $newsletterService
     * @param CityPostPriceServiceInterface $cityPostPriceService
     * @param WeightPostPriceServiceInterface $weightPostPriceService
     * @param SliderServiceInterface $sliderService
     * @param MenuServiceInterface $menuService
     * @return JsonResponse
     */
    public function itemsCounts(
        Request                                  $request,
        UserServiceInterface                     $userService,
        PaymentMethodServiceInterface            $paymentMethodService,
        SendMethodServiceInterface               $sendMethodService,
        ColorServiceInterface                    $colorService,
        BrandServiceInterface                    $brandService,
        CategoryServiceInterface                 $categoryService,
        FestivalServiceInterface                 $festivalService,
        UnitServiceInterface                     $unitService,
        CouponServiceInterface                   $couponService,
        ProductServiceInterface                  $productService,
        ProductAttributeServiceInterface         $productAttributeService,
        ProductAttributeCategoryServiceInterface $productAttributeCategoryService,
        ProductCommentServiceInterface           $productCommentService,
        OrderServiceInterface                    $orderService,
        OrderBadgeServiceInterface               $orderBadgeService,
        ReturnOrderServiceInterface              $returnOrderService,
        BlogServiceInterface                     $blogService,
        BlogBadgeServiceInterface                $blogBadgeService,
        BlogCategoryServiceInterface             $blogCategoryService,
        BlogCommentServiceInterface              $blogCommentService,
        StaticPageServiceInterface               $staticPageService,
        ContactUsServiceInterface                $contactService,
        ComplaintServiceInterface                $complaintService,
        FaqServiceInterface                      $faqService,
        NewsletterServiceInterface               $newsletterService,
        CityPostPriceServiceInterface            $cityPostPriceService,
        WeightPostPriceServiceInterface          $weightPostPriceService,
        SliderServiceInterface                   $sliderService,
        MenuServiceInterface                     $menuService
    ): JsonResponse
    {
        $user = $request->user();
        $data = [];

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::USER)
        )) {
            $data['users'] = $userService->getUsersCount();
            $data['admin_users'] = $userService->getUsersCount(true);
            $data['regular_users'] = $userService->getUsersCount(false);
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::PAYMENT_METHOD)
        )) {
            $data['payment_methods'] = $paymentMethodService->getMethodsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::SEND_METHOD)
        )) {
            $data['send_methods'] = $sendMethodService->getMethodsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::COLOR)
        )) {
            $data['colors'] = $colorService->getColorsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::BRAND)
        )) {
            $data['brands'] = $brandService->getBrandsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::CATEGORY)
        )) {
            $data['categories'] = $categoryService->getCategoriesCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::FESTIVAL)
        )) {
            $data['festivals'] = $festivalService->getFestivalsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::UNIT)
        )) {
            $data['units'] = $unitService->getUnitsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::COUPON)
        )) {
            $data['coupons'] = $couponService->getCouponsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::PRODUCT)
        )) {
            $data['products'] = $productService->getProductsCount();
        }


        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::PRODUCT_ATTRIBUTE)
        )) {
            $data['product_attributes'] = $productAttributeService->getAttributesCount();
            $data['product_attribute_categories'] = $productAttributeCategoryService->getAttributeCategoriesCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::PRODUCT_COMMENT)
        )) {
            $data['product_comments'] = $productCommentService->getCommentsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::ORDER)
        )) {
            $data['orders'] = $orderService->getOrdersCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::ORDER_BADGE)
        )) {
            $data['order_badges'] = $orderBadgeService->getBadgesCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::RETURN_ORDER_REQUEST)
        )) {
            $data['return_orders'] = $returnOrderService->getRequestsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::BLOG)
        )) {
            $data['blogs'] = $blogService->getBlogsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::BLOG_COMMENT_BADGE)
        )) {
            $data['blog_comment_badges'] = $blogBadgeService->getBadgesCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::BLOG_CATEGORY)
        )) {
            $data['blog_categories'] = $blogCategoryService->getCategoriesCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::BLOG_COMMENT)
        )) {
            $data['blog_comments'] = $blogCommentService->getCommentsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::STATIC_PAGE)
        )) {
            $data['static_pages'] = $staticPageService->getPagesCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::CONTACT_US)
        )) {
            $data['contacts'] = $contactService->getContactsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::COMPLAINT)
        )) {
            $data['complaints'] = $complaintService->getComplaintsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::FAQ)
        )) {
            $data['faqs'] = $faqService->getFaqsCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::NEWSLETTER)
        )) {
            $data['newsletters'] = $newsletterService->getMembersCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::CITY_POST_PRICE)
        )) {
            $data['city_post_prices'] = $cityPostPriceService->getPostPricesCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::WEIGHT_POST_PRICE)
        )) {
            $data['weigh_post_prices'] = $weightPostPriceService->getPostPricesCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::SLIDER)
        )) {
            $data['sliders'] = $sliderService->getSlidersCount();
        }

        if ($user->hasPermissionTo(
            PermissionHelper::permission(
                PermissionsEnum::READ,
                PermissionPlacesEnum::MENU)
        )) {
            $data['menus'] = $menuService->getMenusCount();
        }

        return response()->json([
            'type' => ResponseTypesEnum::SUCCESS->value,
            'data' => $data,
        ]);
    }
}
