<?php

namespace App\Models;

use App\Enums\Settings\SettingsEnum;
use App\Enums\SMS\SMSTypesEnum;
use App\Notifications\VerifyCodeNotification;
use App\Services\Contracts\SettingServiceInterface;
use App\Support\Model\AuthenticatableExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Shetabit\Visitor\Traits\Visitor;
use Spatie\Permission\Traits\HasRoles;

/**
 * @method Builder verified()
 */
class User extends Model
{
    use SoftDeletesTrait,
        HasFactory,
        Notifiable,
        HasApiTokens,
        HasRoles,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasDeletedRelationTrait,
        Visitor;

    private const CreatedByLabel = 'created_by';
    private const UpdateByLabel = 'updated_by';
    private const DeletedByLabel = 'deleted_by';

    protected $guarded = [
        'id',
    ];

    protected $hidden = [
        'password',
        'otp_password',
        'remember_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_banned' => 'boolean',
        'forget_password_at' => 'datetime',
        'verified_at' => 'datetime',
        'password' => 'hashed',
        'otp_password' => 'hashed',
        'is_deletable' => 'boolean',
    ];

    /**
     * @var SettingServiceInterface
     */
    protected SettingServiceInterface $settingService;

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->settingService = app()->get(SettingServiceInterface::class);
    }

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    /*
    |--------------------------------------------------------------------------
    | Some Needed Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('verified_at');
    }

    /*
    |--------------------------------------------------------------------------
    | Some Needed Functionality
    |--------------------------------------------------------------------------
    */

    /**
     * @return bool
     */
    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    /**
     * @return bool
     */
    public function shouldSendOTP(): bool
    {
        return is_null($this->otp_password_wait_for_code) ||
            (
                !is_null($this->otp_password_wait_for_code) &&
                now()->gt($this->otp_password_wait_for_code)
            );
    }

    /**
     * @return bool
     */
    public function resetOTP(): bool
    {
        $this->otp_password = null;
        $this->otp_password_wait_for_code = null;
        $this->otp_password_expires_at = null;
        return $this->save();
    }

    /**
     * @param string $code
     * @return void
     */
    public function notifyOTP(string $code): void
    {
        $this->otp_password = Hash::make($code);
        $this->otp_password_wait_for_code = now()->addMinutes(config('market.sms.verify_code_resend_wait', 1));
        $this->otp_password_expires_at = now()->addMinutes(config('market.sms.otp_code_expire_time', 10));
        $this->save();

        $model = $this->settingService->getSetting(SettingsEnum::SMS_OTP->value);
        $this->notify(
            (new VerifyCodeNotification($this, $code, $model, SMSTypesEnum::OTP))->afterCommit()
        );
    }

    /**
     * @return bool
     */
    public function shouldSendActivationVerifyCode(): bool
    {
        return is_null($this->verify_wait_for_code) ||
            (
                !is_null($this->verify_wait_for_code) &&
                now()->gt($this->verify_wait_for_code)
            );
    }

    /**
     * @param string $code
     * @return void
     */
    public function notifyActivationVerificationCode(string $code): void
    {
        $this->verification_code = $code;
        $this->verify_wait_for_code = now()->addMinutes(config('market.sms.verify_code_resend_wait', 1));
        $this->save();

        $model = $this->settingService->getSetting(SettingsEnum::SMS_ACTIVATION->value);
        $this->notify(
            (new VerifyCodeNotification($this, $code, $model, SMSTypesEnum::ACTIVATION))->afterCommit()
        );
    }

    /**
     * @return bool
     */
    public function shouldSendForgotPasswordVerifyCode(): bool
    {
        return is_null($this->forget_password_wait_for_code) ||
            (
                !is_null($this->forget_password_wait_for_code) &&
                now()->gt($this->forget_password_wait_for_code)
            );
    }

    /**
     * @param string $code
     * @return void
     */
    public function notifyForgetPasswordVerificationCode(string $code): void
    {
        $this->forget_password_code = $code;
        $this->forget_password_wait_for_code = now()->addMinutes(config('market.sms.verify_code_resend_wait', 1));
        $this->save();

        $model = $this->settingService->getSetting(SettingsEnum::SMS_RECOVER_PASS->value);
        $this->notify(
            (new VerifyCodeNotification($this, $code, $model, SMSTypesEnum::RECOVER_PASS))->afterCommit()
        );
    }

    /**
     * @return HasMany
     */
    public function addresses(): HasMany
    {
        return $this->hasMany(AddressUser::class);
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->latest();
    }

    /**
     * @return HasMany
     */
    public function blogComments(): HasMany
    {
        return $this->hasMany(BlogComment::class)->latest();
    }

    /**
     * @return HasMany
     */
    public function blogVotes(): HasMany
    {
        return $this->hasMany(BlogVote::class, 'blog_id');
    }

    /**
     * @return HasMany
     */
    public function favoriteProducts(): HasMany
    {
        return $this->hasMany(UserFavoriteProduct::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function reservedOrders(): HasMany
    {
        return $this->hasMany(OrderReserve::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Blog Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdBlogs(): HasMany
    {
        return $this->hasMany(Blog::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedBlogs(): HasMany
    {
        return $this->hasMany(Blog::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedBlogs(): HasMany
    {
        return $this->hasMany(Blog::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Blog Category Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdBlogCategories(): HasMany
    {
        return $this->hasMany(BlogCategory::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedBlogCategories(): HasMany
    {
        return $this->hasMany(BlogCategory::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedBlogCategories(): HasMany
    {
        return $this->hasMany(BlogCategory::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Blog Comment Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdBlogComments(): HasMany
    {
        return $this->hasMany(BlogComment::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedBlogComments(): HasMany
    {
        return $this->hasMany(BlogComment::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedBlogComments(): HasMany
    {
        return $this->hasMany(BlogComment::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Blog Comment Badge Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdBlogCommentBadges(): HasMany
    {
        return $this->hasMany(BlogCommentBadge::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedBlogCommentBadges(): HasMany
    {
        return $this->hasMany(BlogCommentBadge::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedBlogCommentBadges(): HasMany
    {
        return $this->hasMany(BlogCommentBadge::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Setting Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function updatedSettings(): HasMany
    {
        return $this->hasMany(Setting::class, self::UpdateByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Static Page Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdStaticPages(): HasMany
    {
        return $this->hasMany(StaticPage::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedStaticPages(): HasMany
    {
        return $this->hasMany(StaticPage::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedStaticPages(): HasMany
    {
        return $this->hasMany(StaticPage::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Faq Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdFaqs(): HasMany
    {
        return $this->hasMany(Faq::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedFaqs(): HasMany
    {
        return $this->hasMany(Faq::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedFaqs(): HasMany
    {
        return $this->hasMany(Faq::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Complaint Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, self::DeletedByLabel);
    }

    /**
     * @return HasMany
     */
    public function changedStatusComplaints(): HasMany
    {
        return $this->hasMany(Complaint::class, 'changed_status_by');
    }

    /*
    |--------------------------------------------------------------------------
    | ContactUs Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdContacts(): HasMany
    {
        return $this->hasMany(ContactUs::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedContacts(): HasMany
    {
        return $this->hasMany(ContactUs::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedContacts(): HasMany
    {
        return $this->hasMany(ContactUs::class, self::DeletedByLabel);
    }

    /**
     * @return HasMany
     */
    public function changedStatusContacts(): HasMany
    {
        return $this->hasMany(ContactUs::class, 'changed_status_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Newsletter Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdNewsletters(): HasMany
    {
        return $this->hasMany(Newsletter::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedNewsletters(): HasMany
    {
        return $this->hasMany(Newsletter::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedNewsletters(): HasMany
    {
        return $this->hasMany(Newsletter::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Slider Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdSliders(): HasMany
    {
        return $this->hasMany(Slider::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedSliders(): HasMany
    {
        return $this->hasMany(Slider::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedSliders(): HasMany
    {
        return $this->hasMany(Slider::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Slider Items Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdSliderItems(): HasMany
    {
        return $this->hasMany(SliderItem::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedSliderItems(): HasMany
    {
        return $this->hasMany(SliderItem::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedSliderItems(): HasMany
    {
        return $this->hasMany(SliderItem::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Menu Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdMenus(): HasMany
    {
        return $this->hasMany(Menu::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedMenus(): HasMany
    {
        return $this->hasMany(Menu::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedMenus(): HasMany
    {
        return $this->hasMany(Menu::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Menu Items Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdMenuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedMenuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedMenuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Brand Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdBrands(): HasMany
    {
        return $this->hasMany(Brand::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedBrands(): HasMany
    {
        return $this->hasMany(Brand::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedBrands(): HasMany
    {
        return $this->hasMany(Brand::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Category Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdCategories(): HasMany
    {
        return $this->hasMany(Category::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedCategories(): HasMany
    {
        return $this->hasMany(Category::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedCategories(): HasMany
    {
        return $this->hasMany(Category::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Color Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdColors(): HasMany
    {
        return $this->hasMany(Color::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedColors(): HasMany
    {
        return $this->hasMany(Color::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedColors(): HasMany
    {
        return $this->hasMany(Color::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Unit Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdUnits(): HasMany
    {
        return $this->hasMany(Unit::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedUnits(): HasMany
    {
        return $this->hasMany(Unit::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedUnits(): HasMany
    {
        return $this->hasMany(Unit::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Festival Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdFestivals(): HasMany
    {
        return $this->hasMany(Festival::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedFestivals(): HasMany
    {
        return $this->hasMany(Festival::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedFestivals(): HasMany
    {
        return $this->hasMany(Festival::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Coupon Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdCoupons(): HasMany
    {
        return $this->hasMany(Coupon::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedCoupons(): HasMany
    {
        return $this->hasMany(Coupon::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedCoupons(): HasMany
    {
        return $this->hasMany(Coupon::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Category Image Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdCategoryImages(): HasMany
    {
        return $this->hasMany(CategoryImage::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedCategoryImages(): HasMany
    {
        return $this->hasMany(CategoryImage::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedCategoryImages(): HasMany
    {
        return $this->hasMany(CategoryImage::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Attribute Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdProductAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedProductAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedProductAttributes(): HasMany
    {
        return $this->hasMany(ProductAttribute::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Attribute Category Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdProductAttributeCategories(): HasMany
    {
        return $this->hasMany(ProductAttributeCategory::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedProductAttributeCategories(): HasMany
    {
        return $this->hasMany(ProductAttributeCategory::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedProductAttributeCategories(): HasMany
    {
        return $this->hasMany(ProductAttributeCategory::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Attribute Value Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdProductAttributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedProductAttributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedProductAttributeValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Product Attribute Product Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdProductAttributeProducts(): HasMany
    {
        return $this->hasMany(ProductAttributeProduct::class, self::CreatedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Payment Method Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdPaymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedPaymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedPaymentMethods(): HasMany
    {
        return $this->hasMany(PaymentMethod::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Order Badge Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function createdOrderBadges(): HasMany
    {
        return $this->hasMany(OrderBadge::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedOrderBadges(): HasMany
    {
        return $this->hasMany(OrderBadge::class, self::UpdateByLabel);
    }

    /**
     * @return HasMany
     */
    public function deletedOrderBadges(): HasMany
    {
        return $this->hasMany(OrderBadge::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Order Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function changedPaymentStatusOrders(): HasMany
    {
        return $this->hasMany(Order::class, 'payment_status_changed_by');
    }

    /**
     * @return HasMany
     */
    public function createdOrders(): HasMany
    {
        return $this->hasMany(Order::class, self::CreatedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Order Detail Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function changedSendStatusOrders(): HasMany
    {
        return $this->hasMany(OrderDetail::class, 'send_status_changed_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Return Order Request Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function changedStatusReturnOrderRequests(): HasMany
    {
        return $this->hasMany(ReturnOrderRequest::class, 'status_changed_by');
    }

    /**
     * @return HasMany
     */
    public function respondedReturnOrderRequests(): HasMany
    {
        return $this->hasMany(ReturnOrderRequest::class, 'responded_by');
    }

    /**
     * @return HasMany
     */
    public function deletedReturnOrderRequests(): HasMany
    {
        return $this->hasMany(ReturnOrderRequest::class, self::DeletedByLabel);
    }

    /*
    |--------------------------------------------------------------------------
    | Return Order Item Request Modifier Relations
    |--------------------------------------------------------------------------
    */

    /**
     * @return HasMany
     */
    public function acceptedReturnOrderRequestItems(): HasMany
    {
        return $this->hasMany(ReturnOrderRequestItem::class, 'accepted_by');
    }

    /**
     * @return HasMany
     */
    public function createdReturnOrderRequestItems(): HasMany
    {
        return $this->hasMany(ReturnOrderRequestItem::class, self::CreatedByLabel);
    }

    /**
     * @return HasMany
     */
    public function updatedReturnOrderRequestItems(): HasMany
    {
        return $this->hasMany(ReturnOrderRequestItem::class, self::UpdateByLabel);
    }
}
