<?php

namespace Database\Seeders;

use App\Enums\Menus\MenuPlacesEnum;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuAndPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create menus
        $topMenu = Menu::create([
            'title' => 'منوی بالای صفحه',
            'place_in' => MenuPlacesEnum::TOP_MENU->value,
            'is_deletable' => false,
        ]);
        $topMenuBlog = Menu::create([
            'title' => 'منوی بالای صفحه بلاگ',
            'place_in' => MenuPlacesEnum::TOP_MENU_BLOG->value,
            'is_deletable' => false,
        ]);
        $footerMenu1 = Menu::create([
            'title' => 'دسترسی سریع',
            'place_in' => MenuPlacesEnum::FOOTER->value,
            'is_deletable' => false,
        ]);
        $footerMenu2 = Menu::create([
            'title' => 'لینک‌های مفید',
            'place_in' => MenuPlacesEnum::FOOTER->value,
            'is_deletable' => false,
        ]);

        // create menu items
        // -create top menu items
        MenuItem::create([
            'menu_id' => $topMenu->id,
            'title' => 'خانه',
            'link' => '/',
            'can_have_children' => false,
        ]);
        MenuItem::create([
            'menu_id' => $topMenu->id,
            'title' => 'بلاگ',
            'link' => '/blog/search',
        ]);
        MenuItem::create([
            'menu_id' => $topMenu->id,
            'title' => 'تماس با ما',
            'link' => '/contact',
        ]);

        // -create top menu blog items
        MenuItem::create([
            'menu_id' => $topMenuBlog->id,
            'title' => 'بلاگ',
            'link' => '/blog/search',
            'can_have_children' => false,
        ]);
        MenuItem::create([
            'menu_id' => $topMenuBlog->id,
            'title' => 'اخبار',
            'link' => '#',
        ]);

        // -create footer items
        MenuItem::create([
            'menu_id' => $footerMenu1->id,
            'title' => 'محصولات',
            'link' => '/search',
        ]);
        MenuItem::create([
            'menu_id' => $footerMenu1->id,
            'title' => 'برندها',
            'link' => '/brands',
        ]);
        MenuItem::create([
            'menu_id' => $footerMenu1->id,
            'title' => 'سؤالات متداول',
            'link' => '/faq',
        ]);
        //
        MenuItem::create([
            'menu_id' => $footerMenu2->id,
            'title' => 'تماس با ما',
            'link' => '/contact',
        ]);
        MenuItem::create([
            'menu_id' => $footerMenu2->id,
            'title' => 'ثبت شکایات',
            'link' => '/contact',
        ]);
        MenuItem::create([
            'menu_id' => $footerMenu2->id,
            'title' => 'قوانین و مقررات',
            'link' => '#',
        ]);
    }
}
