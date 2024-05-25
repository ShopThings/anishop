<?php

namespace Database\Seeders;

use App\Models\FileManager;
use App\Models\SendMethod;
use Illuminate\Database\Seeder;

class SendMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /**
         * 📍[Use with caution][Use this alongside with "FileManagerSeeder" class]
         * We Assume there is a directory named "send_method" and there are 4 png images in it:
         * ["barbari.png", "chapar.png", "post.png", "motori.png"]
         */
        $methods = [
            'barbari' => [
                'title' => 'باربری',
                'description' => '',
                'image_id' => '', // Set in files iteration
                'price' => 100000,
                'priority' => 1,
                'determine_price_by_shop_location' => true,
                'only_for_shop_location' => false,
                'apply_number_of_shipments_on_price' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            'chapar' => [
                'title' => 'چاپار',
                'description' => '',
                'image_id' => '', // Set in files iteration
                'price' => 40000,
                'priority' => 2,
                'determine_price_by_shop_location' => true,
                'only_for_shop_location' => false,
                'apply_number_of_shipments_on_price' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            'post' => [
                'title' => 'پست',
                'description' => '',
                'image_id' => '', // Set in files iteration
                'price' => 40000,
                'priority' => 0,
                'determine_price_by_shop_location' => true,
                'only_for_shop_location' => false,
                'apply_number_of_shipments_on_price' => true,
                'is_published' => true,
                'created_at' => now(),
            ],
            'motori' => [
                'title' => 'پیک موتوری (درون شهری)',
                'description' => 'این روش ارسال فقط برای مناطق داخل استان فارس و شهر شیراز می‌باشد.',
                'image_id' => '', // Set in files iteration
                'price' => 25000,
                'priority' => 3,
                'determine_price_by_shop_location' => false,
                'only_for_shop_location' => true,
                'apply_number_of_shipments_on_price' => false,
                'is_published' => true,
                'created_at' => now(),
            ],
        ];

        $files = FileManager::query()->whereLike('full_path', 'send_methods', '{value}%')->get();
        foreach ($files as $file) {
            if (str_starts_with($file->full_path, 'send_methods/')) {
                $indexName = null;
                if (str_ends_with($file->full_path, 'barbari.png')) {
                    $indexName = 'barbari';
                } elseif (str_ends_with($file->full_path, 'chapar.png')) {
                    $indexName = 'chapar';
                } elseif (str_ends_with($file->full_path, 'post.png')) {
                    $indexName = 'post';
                } elseif (str_ends_with($file->full_path, 'motori.png')) {
                    $indexName = 'motori';
                }

                if (!empty($indexName)) {
                    $methods[$indexName]['image_id'] = $file->id;
                }
            }
        }

        foreach ($methods as $method) {
            if (!empty($method['image_id'])) {
                SendMethod::create($method);
            }
        }
    }
}
