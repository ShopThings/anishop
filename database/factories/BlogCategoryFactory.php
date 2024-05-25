<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use App\Support\Converters\CharacterConverter;
use App\Support\Converters\NumberConverter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogCategory>
 */
class BlogCategoryFactory extends Factory
{
    protected $model = BlogCategory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = rtrim($this->faker->text(75), '.');
        $keywords = explode(' ', rtrim($this->faker->text(300), '.'));

        return [
            'name' => $name,
            'escaped_name' => NumberConverter::toEnglish(CharacterConverter::toPersian($name)),
            'slug' => str_slug_persian($name),
            'priority' => $this->faker->numberBetween(0, 100),
            'keywords' => $this->faker->randomElements($keywords, $this->faker->numberBetween(0, count($keywords))),
            'is_published' => $this->faker->randomElement([true, false]),
            'show_in_menu' => $this->faker->boolean(60),
            'show_in_side_menu' => $this->faker->boolean(40),
            'created_at' => now(),
        ];
    }
}
