<?php

namespace App\Enums\Gates;

use App\Traits\EnumTranslateTrait;

enum RolesEnum: string
{
    use EnumTranslateTrait;

    case DEVELOPER = 'developer';
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case USER_MANAGER = 'user_manager';
    case PRODUCT_MANAGER = 'product_manager';
    case ORDER_MANAGER = 'order_manager';
    case WRITER = 'writer';
    case USER = 'user';

    /**
     * @return string[]
     */
    protected static function translationArray(): array
    {
        return [
            self::DEVELOPER->value => 'توسعه دهنده',
            self::SUPER_ADMIN->value => 'کاربر ویژه',
            self::ADMIN->value => 'ادمین',
            self::USER_MANAGER->value => 'مدیر کاربران',
            self::PRODUCT_MANAGER->value => 'مدیر محصولات',
            self::ORDER_MANAGER->value => 'مدیر سفارشات',
            self::WRITER->value => 'نویسنده',
            self::USER->value => 'کاربر',
        ];
    }

    /**
     * @return RolesEnum[]
     */
    public static function getAdminRoles(): array
    {
        return [
            self::DEVELOPER, self::SUPER_ADMIN, self::ADMIN, self::USER_MANAGER,
            self::ORDER_MANAGER, self::PRODUCT_MANAGER, self::WRITER,
        ];
    }

    /**
     * @return RolesEnum[]
     */
    public static function getAssignableRoles(): array
    {
        return [
            self::ADMIN, self::USER_MANAGER, self::PRODUCT_MANAGER,
            self::ORDER_MANAGER, self::WRITER, self::USER,
        ];
    }

    /**
     * @return RolesEnum[]
     */
    public static function getPrivilegedRoles(): array
    {
        return [self::DEVELOPER, self::SUPER_ADMIN];
    }

    /**
     * @param string $role
     * @return bool
     */
    public static function isAdminRole(string $role): bool
    {
        $roleValues = array_map(fn($item) => $item->value, self::getAdminRoles());
        return in_array($role, $roleValues);
    }
}
