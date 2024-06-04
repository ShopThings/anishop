<?php

namespace Database\Seeders;

use App\Models\FileManager;
use App\Support\Traits\FilenameTrait;
use Illuminate\Database\Seeder;

class FileManagerSeeder extends Seeder
{
    use FilenameTrait;

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
                'name' => $this->getEscapedFilename('b4'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('blogs', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b5'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('blogs', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b6'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('blogs', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b7'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('blogs', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b8'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('blogs', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b9'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('blogs', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b2'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('blogs', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b1'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('blogs', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b3'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('blogs', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b2'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b4'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b3'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b5'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b6'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('m1'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('m2_1'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('m2_2'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('m3_1'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('m3_3'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('m3_4'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b1'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('m3_2'),
                'extension' => 'gif',
                'path' => $this->convertInvalidPathCharacters('banners', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b2'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b3'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b4'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b1'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b5'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b6'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b8'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b7'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b10'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b9'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('b11'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('brands', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('c3'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('categories', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('c6'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('categories', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('c4'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('categories', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('c5'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('categories', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('c1'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('categories', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('c2'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('categories', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('Idpay'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('gateways', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('beh_pardakht'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('gateways', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('irankish'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('gateways', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('sadad'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('gateways', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('tap'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('gateways', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('zarinpal'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('gateways', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('mabna'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('gateways', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('wallet'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('gateways', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('3'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('namad', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('1'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('namad', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('2'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('namad', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('p5'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('p2'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('p1'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('p3'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('p4'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('g6'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products/single_product', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('g8'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products/single_product', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('g7'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products/single_product', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('g9'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products/single_product', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('g10'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products/single_product', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('g1'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products/single_product', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('g2'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products/single_product', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('g3'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products/single_product', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('g4'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products/single_product', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('g5'),
                'extension' => 'jpg',
                'path' => $this->convertInvalidPathCharacters('products/single_product', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('barbari'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('send_methods', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('chapar'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('send_methods', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('post'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('send_methods', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
            [
                'name' => $this->getEscapedFilename('motori'),
                'extension' => 'png',
                'path' => $this->convertInvalidPathCharacters('send_methods', true),
                'is_deletable' => 1,
                'created_at' => now(),
            ],
        ];

        foreach ($files as $file) {
            FileManager::create($file);
        }
    }
}
