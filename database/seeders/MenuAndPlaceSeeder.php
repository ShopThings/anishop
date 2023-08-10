<?php

namespace Database\Seeders;

use App\Enums\Menus\MenuPlacesEnum;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuPlace;
use Illuminate\Database\Seeder;

class MenuAndPlaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // create menu places
        $main = MenuPlace::create([
            'title' => 'اصلی',
            'place_in' => MenuPlacesEnum::MAIN,
        ]);
        $top = MenuPlace::create([
            'title' => 'بالای صفحه',
            'place_in' => MenuPlacesEnum::TOP_MENU,
        ]);
        $footer = MenuPlace::create([
            'title' => 'فوتر(پانوشت)',
            'place_in' => MenuPlacesEnum::FOOTER,
        ]);
        $blog = MenuPlace::create([
            'title' => 'بلاگ',
            'place_in' => MenuPlacesEnum::BLOG,
        ]);

        // create menus
        $mainMenu = Menu::create([
            'menu_place_id' => $main->id,
            'title' => 'منوی اصلی',
            'is_deletable' => false,
        ]);
        $topMenu = Menu::create([
            'menu_place_id' => $top->id,
            'title' => 'منوی بالای صفحه',
            'is_deletable' => false,
        ]);
        $footerMenu1 = Menu::create([
            'menu_place_id' => $footer->id,
            'title' => 'دسترسی سریع',
            'is_deletable' => false,
        ]);
        $footerMenu2 = Menu::create([
            'menu_place_id' => $footer->id,
            'title' => 'لینک‌های مفید',
            'is_deletable' => false,
        ]);
        $blogMenu = Menu::create([
            'menu_place_id' => $blog->id,
            'title' => 'منوی بلاگ',
            'is_deletable' => false,
        ]);

        // create menu items
        // -create top menu items
        MenuItem::create([
            'menu_id' => $topMenu->id,
            'title' => 'خانه',
            'link' => '#',
            'can_have_children' => false,
        ]);
        MenuItem::create([
            'menu_id' => $topMenu->id,
            'title' => 'بلاگ',
            'link' => '#',
        ]);
        MenuItem::create([
            'menu_id' => $topMenu->id,
            'title' => 'درباره ما',
            'link' => '#',
        ]);

        // -create footer items
        MenuItem::create([
            'menu_id' => $footerMenu1->id,
            'title' => 'محصولات',
            'link' => '#',
        ]);
        MenuItem::create([
            'menu_id' => $footerMenu1->id,
            'title' => 'برندها',
            'link' => '#',
        ]);
        MenuItem::create([
            'menu_id' => $footerMenu1->id,
            'title' => 'سؤالات متداول',
            'link' => '#',
        ]);
        //
        MenuItem::create([
            'menu_id' => $footerMenu2->id,
            'title' => 'درباره ما',
            'link' => '#',
        ]);
        MenuItem::create([
            'menu_id' => $footerMenu2->id,
            'title' => 'تماس با ما',
            'link' => '#',
        ]);
        MenuItem::create([
            'menu_id' => $footerMenu2->id,
            'title' => 'ثبت شکایات',
            'link' => '#',
        ]);
        MenuItem::create([
            'menu_id' => $footerMenu2->id,
            'title' => 'قوانین و مقررات',
            'link' => '#',
        ]);
    }
}
