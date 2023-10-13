<?php

namespace App\Providers;

use App\Repositories\AuthRepository;
use App\Repositories\BlogBadgeRepository;
use App\Repositories\BlogCategoryRepository;
use App\Repositories\BlogCommentRepository;
use App\Repositories\BlogRepository;
use App\Repositories\BrandRepository;
use App\Repositories\CartRepository;
use App\Repositories\CategoryImageRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CityPostPriceRepository;
use App\Repositories\CityRepository;
use App\Repositories\ColorRepository;
use App\Repositories\ComplaintRepository;
use App\Repositories\ContactUsRepository;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\BlogBadgeRepositoryInterface;
use App\Repositories\Contracts\BlogCategoryRepositoryInterface;
use App\Repositories\Contracts\BlogCommentRepositoryInterface;
use App\Repositories\Contracts\BlogRepositoryInterface;
use App\Repositories\Contracts\BrandRepositoryInterface;
use App\Repositories\Contracts\CartRepositoryInterface;
use App\Repositories\Contracts\CategoryImageRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\CityPostPriceRepositoryInterface;
use App\Repositories\Contracts\CityRepositoryInterface;
use App\Repositories\Contracts\ColorRepositoryInterface;
use App\Repositories\Contracts\ComplaintRepositoryInterface;
use App\Repositories\Contracts\ContactUsRepositoryInterface;
use App\Repositories\Contracts\CouponRepositoryInterface;
use App\Repositories\Contracts\FaqRepositoryInterface;
use App\Repositories\Contracts\FestivalRepositoryInterface;
use App\Repositories\Contracts\FileRepositoryInterface;
use App\Repositories\Contracts\MenuRepositoryInterface;
use App\Repositories\Contracts\NewsletterRepositoryInterface;
use App\Repositories\Contracts\OrderBadgeRepositoryInterface;
use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Repositories\Contracts\PaymentMethodRepositoryInterface;
use App\Repositories\Contracts\ProductAttributeCategoryRepositoryInterface;
use App\Repositories\Contracts\ProductAttributeProductRepositoryInterface;
use App\Repositories\Contracts\ProductAttributeRepositoryInterface;
use App\Repositories\Contracts\ProductAttributeValueRepositoryInterface;
use App\Repositories\Contracts\ProductCommentRepositoryInterface;
use App\Repositories\Contracts\ProductRepositoryInterface;
use App\Repositories\Contracts\ProvinceRepositoryInterface;
use App\Repositories\Contracts\ReportRepositoryInterface;
use App\Repositories\Contracts\ReturnOrderRepositoryInterface;
use App\Repositories\Contracts\SliderRepositoryInterface;
use App\Repositories\Contracts\StaticPageRepositoryInterface;
use App\Repositories\Contracts\UnitRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\WeightPostPriceRepositoryInterface;
use App\Repositories\CouponRepository;
use App\Repositories\FaqRepository;
use App\Repositories\FestivalRepository;
use App\Repositories\FileRepository;
use App\Repositories\MenuRepository;
use App\Repositories\NewsletterRepository;
use App\Repositories\OrderBadgeRepository;
use App\Repositories\OrderRepository;
use App\Repositories\PaymentMethodRepository;
use App\Repositories\ProductAttributeCategoryRepository;
use App\Repositories\ProductAttributeProductRepository;
use App\Repositories\ProductAttributeRepository;
use App\Repositories\ProductAttributeValueRepository;
use App\Repositories\ProductCommentRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ProvinceRepository;
use App\Repositories\ReportRepository;
use App\Repositories\ReturnOrderRepository;
use App\Repositories\SliderRepository;
use App\Repositories\StaticPageRepository;
use App\Repositories\UnitRepository;
use App\Repositories\UserRepository;
use App\Repositories\WeightPostPriceRepository;
use App\Services\AuthService;
use App\Services\BlogBadgeService;
use App\Services\BlogCategoryService;
use App\Services\BlogCommentService;
use App\Services\BlogService;
use App\Services\BrandService;
use App\Services\CategoryImageService;
use App\Services\CategoryService;
use App\Services\CityPostPriceService;
use App\Services\CityService;
use App\Services\ColorService;
use App\Services\ComplaintService;
use App\Services\ContactUsService;
use App\Services\Contracts\AuthServiceInterface;
use App\Services\Contracts\BlogBadgeServiceInterface;
use App\Services\Contracts\BlogCategoryServiceInterface;
use App\Services\Contracts\BlogCommentServiceInterface;
use App\Services\Contracts\BlogServiceInterface;
use App\Services\Contracts\BrandServiceInterface;
use App\Services\Contracts\CategoryImageServiceInterface;
use App\Services\Contracts\CategoryServiceInterface;
use App\Services\Contracts\CityPostPriceServiceInterface;
use App\Services\Contracts\CityServiceInterface;
use App\Services\Contracts\ColorServiceInterface;
use App\Services\Contracts\ComplaintServiceInterface;
use App\Services\Contracts\ContactUsServiceInterface;
use App\Services\Contracts\CouponServiceInterface;
use App\Services\Contracts\FaqServiceInterface;
use App\Services\Contracts\FestivalServiceInterface;
use App\Services\Contracts\FileServiceInterface;
use App\Services\Contracts\MenuServiceInterface;
use App\Services\Contracts\NewsletterServiceInterface;
use App\Services\Contracts\OrderBadgeServiceInterface;
use App\Services\Contracts\OrderServiceInterface;
use App\Services\Contracts\PaymentMethodServiceInterface;
use App\Services\Contracts\ProductAttributeCategoryServiceInterface;
use App\Services\Contracts\ProductAttributeProductServiceInterface;
use App\Services\Contracts\ProductAttributeServiceInterface;
use App\Services\Contracts\ProductAttributeValueServiceInterface;
use App\Services\Contracts\ProductCommentServiceInterface;
use App\Services\Contracts\ProductServiceInterface;
use App\Services\Contracts\ProvinceServiceInterface;
use App\Services\Contracts\ReportServiceInterface;
use App\Services\Contracts\ReturnOrderServiceInterface;
use App\Services\Contracts\RoleServiceInterface;
use App\Services\Contracts\SliderServiceInterface;
use App\Services\Contracts\StaticPageServiceInterface;
use App\Services\Contracts\UnitServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Contracts\WeightPostPriceServiceInterface;
use App\Services\CouponService;
use App\Services\FaqService;
use App\Services\FestivalService;
use App\Services\FileService;
use App\Services\MenuService;
use App\Services\NewsletterService;
use App\Services\OrderBadgeService;
use App\Services\OrderService;
use App\Services\PaymentMethodService;
use App\Services\ProductAttributeCategoryService;
use App\Services\ProductAttributeProductService;
use App\Services\ProductAttributeService;
use App\Services\ProductAttributeValueService;
use App\Services\ProductCommentService;
use App\Services\ProductService;
use App\Services\ProvinceService;
use App\Services\ReportService;
use App\Services\ReturnOrderService;
use App\Services\RoleService;
use App\Services\SliderService;
use App\Services\StaticPageService;
use App\Services\UnitService;
use App\Services\UserService;
use App\Services\WeightPostPriceService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->registerRepositories();
        $this->registerServices();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * @return void
     */
    private function registerRepositories(): void
    {
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(PaymentMethodRepositoryInterface::class, PaymentMethodRepository::class);
        $this->app->bind(ColorRepositoryInterface::class, ColorRepository::class);
        $this->app->bind(BrandRepositoryInterface::class, BrandRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(CategoryImageRepositoryInterface::class, CategoryImageRepository::class);
        $this->app->bind(FestivalRepositoryInterface::class, FestivalRepository::class);
        $this->app->bind(UnitRepositoryInterface::class, UnitRepository::class);
        $this->app->bind(CouponRepositoryInterface::class, CouponRepository::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
        $this->app->bind(ProductAttributeRepositoryInterface::class, ProductAttributeRepository::class);
        $this->app->bind(ProductAttributeValueRepositoryInterface::class, ProductAttributeValueRepository::class);
        $this->app->bind(ProductAttributeCategoryRepositoryInterface::class, ProductAttributeCategoryRepository::class);
        $this->app->bind(ProductAttributeProductRepositoryInterface::class, ProductAttributeProductRepository::class);
        $this->app->bind(ProductCommentRepositoryInterface::class, ProductCommentRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderBadgeRepositoryInterface::class, OrderBadgeRepository::class);
        $this->app->bind(ReturnOrderRepositoryInterface::class, ReturnOrderRepository::class);
        $this->app->bind(ReportRepositoryInterface::class, ReportRepository::class);
        $this->app->bind(BlogBadgeRepositoryInterface::class, BlogBadgeRepository::class);
        $this->app->bind(BlogRepositoryInterface::class, BlogRepository::class);
        $this->app->bind(BlogCommentRepositoryInterface::class, BlogCommentRepository::class);
        $this->app->bind(BlogCategoryRepositoryInterface::class, BlogCategoryRepository::class);
        $this->app->bind(StaticPageRepositoryInterface::class, StaticPageRepository::class);
        $this->app->bind(ContactUsRepositoryInterface::class, ContactUsRepository::class);
        $this->app->bind(ComplaintRepositoryInterface::class, ComplaintRepository::class);
        $this->app->bind(FaqRepositoryInterface::class, FaqRepository::class);
        $this->app->bind(NewsletterRepositoryInterface::class, NewsletterRepository::class);
        $this->app->bind(CityPostPriceRepositoryInterface::class, CityPostPriceRepository::class);
        $this->app->bind(CityRepositoryInterface::class, CityRepository::class);
        $this->app->bind(ProvinceRepositoryInterface::class, ProvinceRepository::class);
        $this->app->bind(WeightPostPriceRepositoryInterface::class, WeightPostPriceRepository::class);
        $this->app->bind(SliderRepositoryInterface::class, SliderRepository::class);
        $this->app->bind(MenuRepositoryInterface::class, MenuRepository::class);
        $this->app->bind(FileRepositoryInterface::class, FileRepository::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
    }

    /**
     * @return void
     */
    private function registerServices(): void
    {
        $this->app->bind(RoleServiceInterface::class, RoleService::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(PaymentMethodServiceInterface::class, PaymentMethodService::class);
        $this->app->bind(ColorServiceInterface::class, ColorService::class);
        $this->app->bind(BrandServiceInterface::class, BrandService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(CategoryImageServiceInterface::class, CategoryImageService::class);
        $this->app->bind(FestivalServiceInterface::class, FestivalService::class);
        $this->app->bind(UnitServiceInterface::class, UnitService::class);
        $this->app->bind(CouponServiceInterface::class, CouponService::class);
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(ProductAttributeServiceInterface::class, ProductAttributeService::class);
        $this->app->bind(ProductAttributeValueServiceInterface::class, ProductAttributeValueService::class);
        $this->app->bind(ProductAttributeCategoryServiceInterface::class, ProductAttributeCategoryService::class);
        $this->app->bind(ProductAttributeProductServiceInterface::class, ProductAttributeProductService::class);
        $this->app->bind(ProductCommentServiceInterface::class, ProductCommentService::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);
        $this->app->bind(OrderBadgeServiceInterface::class, OrderBadgeService::class);
        $this->app->bind(ReturnOrderServiceInterface::class, ReturnOrderService::class);
        $this->app->bind(ReportServiceInterface::class, ReportService::class);
        $this->app->bind(BlogBadgeServiceInterface::class, BlogBadgeService::class);
        $this->app->bind(BlogServiceInterface::class, BlogService::class);
        $this->app->bind(BlogCommentServiceInterface::class, BlogCommentService::class);
        $this->app->bind(BlogCategoryServiceInterface::class, BlogCategoryService::class);
        $this->app->bind(StaticPageServiceInterface::class, StaticPageService::class);
        $this->app->bind(ContactUsServiceInterface::class, ContactUsService::class);
        $this->app->bind(ComplaintServiceInterface::class, ComplaintService::class);
        $this->app->bind(FaqServiceInterface::class, FaqService::class);
        $this->app->bind(NewsletterServiceInterface::class, NewsletterService::class);
        $this->app->bind(CityPostPriceServiceInterface::class, CityPostPriceService::class);
        $this->app->bind(CityServiceInterface::class, CityService::class);
        $this->app->bind(ProvinceServiceInterface::class, ProvinceService::class);
        $this->app->bind(WeightPostPriceServiceInterface::class, WeightPostPriceService::class);
        $this->app->bind(SliderServiceInterface::class, SliderService::class);
        $this->app->bind(MenuServiceInterface::class, MenuService::class);
        $this->app->bind(FileServiceInterface::class, FileService::class);
    }
}
