<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => $this->faker->unique()->phoneNumber(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password,
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'national_code' => '4420549033',
            'is_admin' => $this->faker->randomElement([true, false]),
            'verified_at' => now(),
        ];
    }

    /**
     * Indicate that the model's username should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model's user is not an admin.
     */
    public function notAdmin(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_admin' => false,
        ]);
    }

    /**
     * Indicate that the model's user is not an admin.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_admin' => true,
        ]);
    }
}
