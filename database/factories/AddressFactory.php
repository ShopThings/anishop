<?php

namespace Database\Factories;

use App\Models\AddressUser;
use App\Models\Province;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AddressUser>
 */
class AddressFactory extends Factory
{
    protected $model = AddressUser::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $province = Province::inRandomOrder()->first();
        $city = $province->cities()->inRandomOrder()->first();

        return [
            'user_id' => User::factory(),
            'full_name' => $this->faker->firstName . ' ' . $this->faker->lastName,
            'mobile' => $this->faker->phoneNumber(),
            'address' => $this->faker->streetAddress(),
            'city_id' => $city->id,
            'province_id' => $province->id,
            'postal_code' => $this->faker->postcode(),
            'created_at' => now(),
        ];
    }

    /**
     * Assign address to specific user
     */
    public function onUser(User $user): static
    {
        return $this->state(fn(array $attributes) => [
            'user_id' => $user->id,
        ]);
    }
}
