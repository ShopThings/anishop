<?php

namespace Database\Seeders;

use App\Models\FileManager;
use Illuminate\Database\Seeder;

class FileManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * 📍[ATTENTION]
         *   For this to be working fine, you need to have follow below directory structure:
         *   (in "storage/public")
         *
         * <code>
         *   /
         *   ├── banners
         *   ├── blogs
         *   ├── brands
         *   ├── categories
         *   ├── gateways
         *   ├── namad
         *   ├── products
         *   │   └── single_product
         *   └── send_methods
         * </code>
         *
         * And for files you should look at "full_path" and have each of them inside corresponding directory.
         * Each file should have 4 types that filemanager follows:
         *  - original (the file without any extra name to it)
         *  - large (should have "-large" at the end of filename)
         *  - medium (should have "-medium" at the end of filename)
         *  - small (should have "-small" at the end of filename)
         *
         * As an example for "banners/b1.jpg"
         * <code>
         *   - original -> b1-1716491587.jpg
         *   - large -> b1-1716491587-large.jpg
         *   - medium -> b1-1716491587-medium.jpg
         *   - small -> b1-1716491587-small.jpg
         * </code>
         *
         * "1716491587" is a timestamp that show upload time (it is not important in seeding phase)
         */
        $files = [
            [
                'name' => 'b4',
                'extension' => 'jpg',
                'path' => 'blogs',
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => 'b5',
                'extension' => 'jpg',
                'path' => 'blogs',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b6',
                'extension' => 'jpg',
                'path' => 'blogs',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b7',
                'extension' => 'jpg',
                'path' => 'blogs',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b8',
                'extension' => 'jpg',
                'path' => 'blogs',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b9',
                'extension' => 'jpg',
                'path' => 'blogs',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b2',
                'extension' => 'jpg',
                'path' => 'blogs',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b1',
                'extension' => 'jpg',
                'path' => 'blogs',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b3',
                'extension' => 'jpg',
                'path' => 'blogs',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b2',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b4',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b3',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b5',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b6',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'm1',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'm2-1',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'm2-2',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'm3-1',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'm3-3',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'm3-4',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b1',
                'extension' => 'jpg',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'm3-2',
                'extension' => 'gif',
                'path' => 'banners',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b2',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b3',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b4',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b1',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b5',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b6',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b8',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b7',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b10',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b9',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'b11',
                'extension' => 'png',
                'path' => 'brands',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'c3',
                'extension' => 'png',
                'path' => 'categories',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'c6',
                'extension' => 'png',
                'path' => 'categories',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'c4',
                'extension' => 'png',
                'path' => 'categories',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'c5',
                'extension' => 'png',
                'path' => 'categories',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'c1',
                'extension' => 'png',
                'path' => 'categories',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'c2',
                'extension' => 'png',
                'path' => 'categories',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'Idpay',
                'extension' => 'png',
                'path' => 'gateways',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'beh-pardakht',
                'extension' => 'png',
                'path' => 'gateways',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'irankish',
                'extension' => 'jpg',
                'path' => 'gateways',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'sadad',
                'extension' => 'jpg',
                'path' => 'gateways',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'tap',
                'extension' => 'jpg',
                'path' => 'gateways',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'zarinpal',
                'extension' => 'png',
                'path' => 'gateways',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'mabna',
                'extension' => 'png',
                'path' => 'gateways',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'wallet',
                'extension' => 'png',
                'path' => 'gateways',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => '3',
                'extension' => 'jpg',
                'path' => 'namad',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => '1',
                'extension' => 'jpg',
                'path' => 'namad',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => '2',
                'extension' => 'jpg',
                'path' => 'namad',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'p5',
                'extension' => 'jpg',
                'path' => 'products',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'p2',
                'extension' => 'jpg',
                'path' => 'products',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'p1',
                'extension' => 'jpg',
                'path' => 'products',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'p3',
                'extension' => 'jpg',
                'path' => 'products',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'p4',
                'extension' => 'jpg',
                'path' => 'products',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'g6',
                'extension' => 'jpg',
                'path' => 'products/single_product',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'g8',
                'extension' => 'jpg',
                'path' => 'products/single_product',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'g7',
                'extension' => 'jpg',
                'path' => 'products/single_product',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'g9',
                'extension' => 'jpg',
                'path' => 'products/single_product',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'g10',
                'extension' => 'jpg',
                'path' => 'products/single_product',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'g1',
                'extension' => 'jpg',
                'path' => 'products/single_product',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'g2',
                'extension' => 'jpg',
                'path' => 'products/single_product',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'g3',
                'extension' => 'jpg',
                'path' => 'products/single_product',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'g4',
                'extension' => 'jpg',
                'path' => 'products/single_product',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'g5',
                'extension' => 'jpg',
                'path' => 'products/single_product',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'barbari',
                'extension' => 'png',
                'path' => 'send_methods',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'chapar',
                'extension' => 'png',
                'path' => 'send_methods',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'post',
                'extension' => 'png',
                'path' => 'send_methods',
                'is_deletable' => 1,
                'created_at' => now(),

            ],
            [
                'name' => 'motori',
                'extension' => 'png',
                'path' => 'send_methods',
                'is_deletable' => 1,
                'created_at' => now(),

            ]
        ];

        foreach ($files as $file) {
            FileManager::create($file);
        }
    }
}
