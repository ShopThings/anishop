<?php

namespace Database\Seeders;

use App\Enums\Gates\PermissionPlacesEnum;
use App\Enums\Gates\PermissionsEnum;
use App\Enums\Gates\RolesEnum;
use App\Support\Gate\PermissionHelper;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // create user permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::BAN,
            PermissionPlacesEnum::USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::VERIFY,
            PermissionPlacesEnum::USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::EXPORT,
            PermissionPlacesEnum::USER
        ));

        // create user addresses permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::ADDRESS_USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::ADDRESS_USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::ADDRESS_USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::ADDRESS_USER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::ADDRESS_USER
        ));

        // create user favorite product permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::USER_FAVORITE_PRODUCT
        ));

        // create blog permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::BLOG
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::BLOG
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::BLOG
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::BLOG
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::BLOG
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::BLOG
        ));

        // create blog category permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::BLOG_CATEGORY
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::BLOG_CATEGORY
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::BLOG_CATEGORY
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::BLOG_CATEGORY
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::BLOG_CATEGORY
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::BLOG_CATEGORY
        ));

        // create blog comment permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::BLOG_COMMENT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::BLOG_COMMENT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::BLOG_COMMENT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::BLOG_COMMENT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::BLOG_COMMENT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::BLOG_COMMENT
        ));

        // create blog comment badge permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::BLOG_COMMENT_BADGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::BLOG_COMMENT_BADGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::BLOG_COMMENT_BADGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::BLOG_COMMENT_BADGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::BLOG_COMMENT_BADGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::BLOG_COMMENT_BADGE
        ));

        // create static page permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::STATIC_PAGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::STATIC_PAGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::STATIC_PAGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::STATIC_PAGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::STATIC_PAGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::STATIC_PAGE
        ));

        // create city post price permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::CITY_POST_PRICE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::CITY_POST_PRICE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::CITY_POST_PRICE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::CITY_POST_PRICE
        ));

        // create weight post price permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::WEIGHT_POST_PRICE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::WEIGHT_POST_PRICE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::WEIGHT_POST_PRICE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::WEIGHT_POST_PRICE
        ));

        // create payment method permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::PAYMENT_METHOD
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::PAYMENT_METHOD
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::PAYMENT_METHOD
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::PAYMENT_METHOD
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::PAYMENT_METHOD
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::PAYMENT_METHOD
        ));

        // create payment method permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::SEND_METHOD
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::SEND_METHOD
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::SEND_METHOD
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::SEND_METHOD
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::SEND_METHOD
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::SEND_METHOD
        ));

        // create brand permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::BRAND
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::BRAND
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::BRAND
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::BRAND
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::BRAND
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::BRAND
        ));

        // create category permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::CATEGORY
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::CATEGORY
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::CATEGORY
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::CATEGORY
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::CATEGORY
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::CATEGORY
        ));

        // create category image permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::CATEGORY_IMAGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::CATEGORY_IMAGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::CATEGORY_IMAGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::CATEGORY_IMAGE
        ));

        // create unit permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::UNIT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::UNIT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::UNIT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::UNIT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::UNIT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::UNIT
        ));

        // create color permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::COLOR
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::COLOR
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::COLOR
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::COLOR
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::COLOR
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::COLOR
        ));

        // create coupon permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::COUPON
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::COUPON
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::COUPON
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::COUPON
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::COUPON
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::COUPON
        ));

        // create product permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::PRODUCT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::PRODUCT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::PRODUCT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::PRODUCT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::PRODUCT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::PRODUCT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::EXPORT,
            PermissionPlacesEnum::PRODUCT
        ));

        // create product comment permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::PRODUCT_COMMENT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::PRODUCT_COMMENT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::PRODUCT_COMMENT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::PRODUCT_COMMENT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::PRODUCT_COMMENT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::PRODUCT_COMMENT
        ));

        // create product attribute permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::PRODUCT_ATTRIBUTE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::PRODUCT_ATTRIBUTE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::PRODUCT_ATTRIBUTE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::PRODUCT_ATTRIBUTE
        ));

        // create festival permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::FESTIVAL
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::FESTIVAL
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::FESTIVAL
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::FESTIVAL
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::FESTIVAL
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::FESTIVAL
        ));

        // create order permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::ORDER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::ORDER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::ORDER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::EXPORT,
            PermissionPlacesEnum::ORDER
        ));

        // create order badge permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::ORDER_BADGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::ORDER_BADGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::ORDER_BADGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::ORDER_BADGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::ORDER_BADGE
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::ORDER_BADGE
        ));

        // create return order permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::RETURN_ORDER_REQUEST
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::RETURN_ORDER_REQUEST
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::RETURN_ORDER_REQUEST
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::RETURN_ORDER_REQUEST
        ));

        // create menu permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::MENU
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::MENU
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::MENU
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::MENU
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::MENU
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::MENU
        ));

        // create slider permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::SLIDER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::SLIDER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::SLIDER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::SLIDER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::SLIDER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::SLIDER
        ));

        // create setting permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::SETTING
        ));

        // create contact-us permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::CONTACT_US
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::CONTACT_US
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::CONTACT_US
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::CONTACT_US
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::CONTACT_US
        ));

        // create complaint permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::COMPLAINT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::COMPLAINT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::COMPLAINT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::COMPLAINT
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::COMPLAINT
        ));

        // create newsletter permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::NEWSLETTER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::NEWSLETTER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::NEWSLETTER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::NEWSLETTER
        ));

        // create faq permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::FAQ
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::FAQ
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::FAQ
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::FAQ
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PERMANENT_DELETE,
            PermissionPlacesEnum::FAQ
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::PUBLISH,
            PermissionPlacesEnum::FAQ
        ));

        // create file-manager permissions
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::CREATE,
            PermissionPlacesEnum::FILE_MANAGER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::READ,
            PermissionPlacesEnum::FILE_MANAGER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPDATE,
            PermissionPlacesEnum::FILE_MANAGER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DELETE,
            PermissionPlacesEnum::FILE_MANAGER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::UPLOAD,
            PermissionPlacesEnum::FILE_MANAGER
        ));
        $this->createPermission(PermissionHelper::permission(
            PermissionsEnum::DOWNLOAD,
            PermissionPlacesEnum::FILE_MANAGER
        ));

        //---------------------------------------------------------------------

        $this->createRole(RolesEnum::DEVELOPER->value, Permission::all());
        $this->createRole(RolesEnum::SUPER_ADMIN->value, Permission::all());
        $this->createRole(RolesEnum::ADMIN->value, Permission::all());

        $this->createRole(RolesEnum::USER_MANAGER->value, [
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::USER),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::USER),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::USER),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::USER),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::USER),
            PermissionHelper::permission(PermissionsEnum::BAN, PermissionPlacesEnum::USER),
            PermissionHelper::permission(PermissionsEnum::VERIFY, PermissionPlacesEnum::USER),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::ADDRESS_USER),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::ADDRESS_USER),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::ADDRESS_USER),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::ADDRESS_USER),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::ADDRESS_USER),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::USER_FAVORITE_PRODUCT),
        ]);

        $this->createRole(RolesEnum::PRODUCT_MANAGER->value, [
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::BRAND),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::BRAND),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::BRAND),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::BRAND),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::BRAND),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::BRAND),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::CATEGORY),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::CATEGORY),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::CATEGORY),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::CATEGORY),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::CATEGORY),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::CATEGORY),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::CATEGORY_IMAGE),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::CATEGORY_IMAGE),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::CATEGORY_IMAGE),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::CATEGORY_IMAGE),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::UNIT),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::UNIT),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::UNIT),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::UNIT),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::UNIT),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::UNIT),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::SEND_METHOD),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::SEND_METHOD),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::SEND_METHOD),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::SEND_METHOD),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::SEND_METHOD),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::COLOR),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::COLOR),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::COLOR),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::COLOR),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::COLOR),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::COLOR),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::COUPON),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::COUPON),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::COUPON),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::COUPON),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::COUPON),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::COUPON),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::PRODUCT),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::PRODUCT),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::PRODUCT),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::PRODUCT),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::PRODUCT),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::PRODUCT),
            PermissionHelper::permission(PermissionsEnum::EXPORT, PermissionPlacesEnum::PRODUCT),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::PRODUCT_COMMENT),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::PRODUCT_COMMENT),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::PRODUCT_COMMENT),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::PRODUCT_COMMENT),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::PRODUCT_COMMENT),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::PRODUCT_COMMENT),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::PRODUCT_ATTRIBUTE),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::PRODUCT_ATTRIBUTE),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::PRODUCT_ATTRIBUTE),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::PRODUCT_ATTRIBUTE),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::FESTIVAL),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::FESTIVAL),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::FESTIVAL),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::FESTIVAL),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::FESTIVAL),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::FESTIVAL),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::FILE_MANAGER),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::FILE_MANAGER),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::FILE_MANAGER),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::FILE_MANAGER),
            PermissionHelper::permission(PermissionsEnum::UPLOAD, PermissionPlacesEnum::FILE_MANAGER),
        ]);

        $this->createRole(RolesEnum::ORDER_MANAGER->value, [
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::ORDER),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::ORDER),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::ORDER),
            PermissionHelper::permission(PermissionsEnum::EXPORT, PermissionPlacesEnum::ORDER),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::ORDER_BADGE),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::ORDER_BADGE),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::ORDER_BADGE),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::ORDER_BADGE),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::ORDER_BADGE),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::ORDER_BADGE),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::RETURN_ORDER_REQUEST),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::RETURN_ORDER_REQUEST),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::RETURN_ORDER_REQUEST),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::RETURN_ORDER_REQUEST),
        ]);

        $this->createRole(RolesEnum::WRITER->value, [
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::BLOG),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::BLOG),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::BLOG),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::BLOG),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::BLOG),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::BLOG),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::BLOG_CATEGORY),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::BLOG_CATEGORY),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::BLOG_CATEGORY),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::BLOG_CATEGORY),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::BLOG_CATEGORY),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::BLOG_CATEGORY),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::BLOG_COMMENT),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::BLOG_COMMENT),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::BLOG_COMMENT),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::BLOG_COMMENT),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::BLOG_COMMENT),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::BLOG_COMMENT),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::BLOG_COMMENT_BADGE),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::BLOG_COMMENT_BADGE),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::BLOG_COMMENT_BADGE),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::BLOG_COMMENT_BADGE),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::BLOG_COMMENT_BADGE),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::BLOG_COMMENT_BADGE),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::STATIC_PAGE),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::STATIC_PAGE),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::STATIC_PAGE),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::STATIC_PAGE),
            PermissionHelper::permission(PermissionsEnum::PERMANENT_DELETE, PermissionPlacesEnum::STATIC_PAGE),
            PermissionHelper::permission(PermissionsEnum::PUBLISH, PermissionPlacesEnum::STATIC_PAGE),
            PermissionHelper::permission(PermissionsEnum::CREATE, PermissionPlacesEnum::FILE_MANAGER),
            PermissionHelper::permission(PermissionsEnum::READ, PermissionPlacesEnum::FILE_MANAGER),
            PermissionHelper::permission(PermissionsEnum::UPDATE, PermissionPlacesEnum::FILE_MANAGER),
            PermissionHelper::permission(PermissionsEnum::DELETE, PermissionPlacesEnum::FILE_MANAGER),
            PermissionHelper::permission(PermissionsEnum::UPLOAD, PermissionPlacesEnum::FILE_MANAGER),
        ]);

        $this->createRole(RolesEnum::USER->value);
    }

    /**
     * @param string $permission
     * @return void
     */
    private function createPermission(string $permission): void
    {
        Permission::create([
            'name' => $permission,
        ]);
    }

    /**
     * @param string $role
     * @param $permissions
     * @return void
     */
    private function createRole(string $role, $permissions = null): void
    {
        $roles = Role::create(['name' => $role]);
        if (!is_null($permissions)) {
            $roles->givePermissionTo($permissions);
        }
    }
}
