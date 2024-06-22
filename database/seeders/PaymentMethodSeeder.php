<?php

namespace Database\Seeders;

use App\Enums\Payments\PaymentTypesEnum;
use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $method = PaymentMethod::create([
            'title' => PaymentTypesEnum::getTranslations(PaymentTypesEnum::TESTING),
            'image_id' => null,
            'type' => PaymentTypesEnum::TESTING->value,
            'is_published' => true,
            'created_at' => now(),
            'created_by' => 1, // this is developer account
        ]);
        $method->is_sealed = true;
        $method->save();
    }
}
