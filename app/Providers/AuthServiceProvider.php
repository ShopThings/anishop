<?php

namespace App\Providers;

use App\Enums\Gates\RolesEnum;
use App\Models\BlogCategory;
use App\Models\BlogComment;
use App\Models\BlogCommentBadge;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryImage;
use App\Models\City;
use App\Models\CityPostPrice;
use App\Models\Color;
use App\Models\Comment;
use App\Models\Complaint;
use App\Models\ContactUs;
use App\Models\Coupon;
use App\Models\Faq;
use App\Models\Festival;
use App\Models\FileManager;
use App\Models\Menu;
use App\Models\Newsletter;
use App\Models\Order;
use App\Models\OrderBadge;
use App\Models\OrderDetail;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeCategory;
use App\Models\ProductAttributeProduct;
use App\Models\ProductAttributeValue;
use App\Models\Province;
use App\Models\ReturnOrderRequest;
use App\Models\Slider;
use App\Models\StaticPage;
use App\Models\Unit;
use App\Models\User;
use App\Models\WeightPostPrice;
use App\Policies\BlogBadgePolicy;
use App\Policies\BlogCategoryPolicy;
use App\Policies\BlogCommentPolicy;
use App\Policies\BrandPolicy;
use App\Policies\CategoryImagePolicy;
use App\Policies\CategoryPolicy;
use App\Policies\CityPolicy;
use App\Policies\CityPostPricePolicy;
use App\Policies\ColorPolicy;
use App\Policies\ComplaintPolicy;
use App\Policies\ContactUsPolicy;
use App\Policies\CouponPolicy;
use App\Policies\FaqPolicy;
use App\Policies\FestivalPolicy;
use App\Policies\FilePolicy;
use App\Policies\MenuPolicy;
use App\Policies\NewsletterPolicy;
use App\Policies\OrderBadgePolicy;
use App\Policies\OrderDetailPolicy;
use App\Policies\OrderPolicy;
use App\Policies\PaymentMethodPolicy;
use App\Policies\ProductAttributeCategoryPolicy;
use App\Policies\ProductAttributePolicy;
use App\Policies\ProductAttributeProductPolicy;
use App\Policies\ProductAttributeValuePolicy;
use App\Policies\ProductCommentPolicy;
use App\Policies\ProductPolicy;
use App\Policies\ProvincePolicy;
use App\Policies\ReportPolicy;
use App\Policies\ReturnOrderPolicy;
use App\Policies\SliderPolicy;
use App\Policies\StaticPagePolicy;
use App\Policies\UnitPolicy;
use App\Policies\UserPolicy;
use App\Policies\WeightPostPricePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        PaymentMethod::class => PaymentMethodPolicy::class,
        Color::class => ColorPolicy::class,
        Brand::class => BrandPolicy::class,
        Category::class => CategoryPolicy::class,
        CategoryImage::class => CategoryImagePolicy::class,
        Festival::class => FestivalPolicy::class,
        Unit::class => UnitPolicy::class,
        Coupon::class => CouponPolicy::class,
        Product::class => ProductPolicy::class,
        ProductAttribute::class => ProductAttributePolicy::class,
        ProductAttributeValue::class => ProductAttributeValuePolicy::class,
        ProductAttributeCategory::class => ProductAttributeCategoryPolicy::class,
        ProductAttributeProduct::class => ProductAttributeProductPolicy::class,
        Comment::class => ProductCommentPolicy::class,
        OrderDetail::class => OrderDetailPolicy::class,
        Order::class => OrderPolicy::class,
        OrderBadge::class => OrderBadgePolicy::class,
        ReturnOrderRequest::class => ReturnOrderPolicy::class,
        BlogCommentBadge::class => BlogBadgePolicy::class,
        BlogComment::class => BlogCommentPolicy::class,
        BlogCategory::class => BlogCategoryPolicy::class,
        StaticPage::class => StaticPagePolicy::class,
        ContactUs::class => ContactUsPolicy::class,
        Complaint::class => ComplaintPolicy::class,
        Faq::class => FaqPolicy::class,
        Newsletter::class => NewsletterPolicy::class,
        CityPostPrice::class => CityPostPricePolicy::class,
        City::class => CityPolicy::class,
        Province::class => ProvincePolicy::class,
        WeightPostPrice::class => WeightPostPricePolicy::class,
        Slider::class => SliderPolicy::class,
        Menu::class => MenuPolicy::class,
        FileManager::class => FilePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
        $this->registerCustomPolicies();

        // Implicitly grant "Developer" role all permission checks using can()
        Gate::before(function (User $user, $ability) {
            return $user->hasAnyRole([RolesEnum::DEVELOPER->value]) ? true : null;
        });
    }

    protected function registerCustomPolicies(): void
    {
        // Ability to report admin stuffs
        Gate::define('canReport', [ReportPolicy::class, 'canReport']);
    }
}
