<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Support\Converters\CharacterConverter;
use App\Support\Converters\NumberConverter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    protected $model = Blog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim($this->faker->sentence(), '.');

        return [
            'category_id' => BlogCategory::factory(),
            'title' => $title,
            'escaped_title' => NumberConverter::toEnglish(CharacterConverter::toPersian($title)),
            'slug' => Str::slug(title: $title, language: 'fa'),
            // TODO: change this according to filesystem and filemanager items
            'image_id' => null,
            'description' => $this->faker->text(1000),
            'keywords' => implode(',', $this->faker->words($this->faker->randomElement([1, 3, 4, 6, 7, 10]))),
            'is_commenting_allowed' => $this->faker->randomElement([true, false]),
            'is_published' => $this->faker->randomElement([true, false]),
            'created_at' => now(),
        ];
    }
}
