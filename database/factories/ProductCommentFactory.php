<?php

namespace Database\Factories;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class ProductCommentFactory extends Factory
{
    protected $model = Comment::class;

    /**
     * @var array
     */
    private array $productIds;

    /**
     * @var array
     */
    private array $userIds;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);

        $this->productIds = Product::all()->pluck('id')->toArray();
        $this->userIds = User::all()->pluck('id')->toArray();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $condition = $this->faker->randomElement(array_map(fn($item) => $item->value, CommentConditionsEnum::cases()));
        $answer = $this->faker->randomElement([null, $this->faker->realText()]);
        $pros = explode(' ', rtrim($this->faker->optional(0.4, '')->realText(100), '.'));
        $cons = explode(' ', rtrim($this->faker->optional(0.2, '')->realText(100), '.'));

        return [
            'product_id' => $this->faker->randomElement($this->productIds),
            'condition' => $condition,
            'status' => $condition == CommentConditionsEnum::UNSET->value
                ? $this->faker->randomElement(array_map(fn($item) => $item->value, CommentStatusesEnum::cases()))
                : CommentStatusesEnum::READ->value,
            'pros' => $this->faker->randomElements($pros, $this->faker->numberBetween(0, count($pros))),
            'cons' => $this->faker->randomElements($cons, $this->faker->numberBetween(0, count($cons))),
            'description' => $this->faker->realText(),
            'answer' => $answer,
            'answered_at' => !is_null($answer) ? $this->faker->dateTime() : null,
            'answered_by' => !is_null($answer) ? 1 : null,
            'changed_condition_at' => $condition == CommentConditionsEnum::UNSET->value
                ? $this->faker->dateTime()
                : null,
            'changed_condition_by' => $condition == CommentConditionsEnum::UNSET->value
                ? 1
                : null,
            'flag_count' => $this->faker->optional(0.2, 0)->numberBetween(1, 7),
            'up_vote_count' => $this->faker->optional(0.75, 0)->numberBetween(5, 16),
            'down_vote_count' => $this->faker->optional(0.2, 0)->numberBetween(1, 7),
            'created_at' => now(),
            'created_by' => $this->faker->randomElement($this->userIds),
        ];
    }
}
