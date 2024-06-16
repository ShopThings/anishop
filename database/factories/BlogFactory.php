<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\FileManager;
use App\Support\Converters\CharacterConverter;
use App\Support\Converters\NumberConverter;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogFactory extends Factory
{
    protected $model = Blog::class;

    /**
     * @var array
     */
    private array $blogImages;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);

        $this->blogImages = FileManager::query()->whereLike('path', 'blogs')->get()->pluck('id')->toArray();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = rtrim($this->faker->realText(150), '.');
        $keywords = explode(' ', rtrim($this->faker->realText(300), '.'));

        return [
            'category_id' => BlogCategory::factory(),
            'title' => $title,
            'escaped_title' => NumberConverter::toEnglish(CharacterConverter::toPersian($title)),
            'slug' => str_slug_persian($title),
            'image_id' => $this->faker->randomElement($this->blogImages),
            'brief_description' => $this->faker->realText(150),
            'description' => $this->faker->realText(1000),
            'keywords' => $this->faker->randomElements($keywords, $this->faker->numberBetween(0, count($keywords))),
            'is_commenting_allowed' => $this->faker->randomElement([true, false]),
            'is_published' => $this->faker->randomElement([true, false]),
            'created_at' => now(),
        ];
    }
}
