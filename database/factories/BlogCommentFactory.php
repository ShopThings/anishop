<?php

namespace Database\Factories;

use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\Comments\CommentStatusesEnum;
use App\Models\Blog;
use App\Models\BlogComment;
use App\Models\BlogCommentBadge;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Collection;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Blog>
 */
class BlogCommentFactory extends Factory
{
    protected $model = BlogComment::class;

    /**
     * @var array|mixed[]
     */
    private array $usersIds;

    /**
     * @var array|mixed[]
     */
    private array $badgeIds;

    public function __construct($count = null, ?Collection $states = null, ?Collection $has = null, ?Collection $for = null, ?Collection $afterMaking = null, ?Collection $afterCreating = null, $connection = null, ?Collection $recycle = null)
    {
        parent::__construct($count, $states, $has, $for, $afterMaking, $afterCreating, $connection, $recycle);

        $this->usersIds = User::all()->pluck('id')->toArray();
        $this->badgeIds = BlogCommentBadge::all()->pluck('id')->toArray();
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $condition = $this->faker->randomElement(array_map(fn($item) => $item->value, CommentConditionsEnum::cases()));

        return [
            'user_id' => $this->faker->randomElement(array_merge($this->usersIds, [null])),
            'blog_id' => Blog::factory(),
            'badge_id' => $this->faker->randomElement($this->badgeIds),
            'condition' => $condition,
            'status' => $condition == CommentConditionsEnum::UNSET->value
                ? $this->faker->randomElement(array_map(fn($item) => $item->value, CommentStatusesEnum::cases()))
                : CommentStatusesEnum::READ->value,
            'description' => $this->faker->realText(1000),
            'created_at' => now(),
            'created_by' => $this->faker->randomElement($this->usersIds),
        ];
    }

    /**
     * For nesting comments
     */
    public function forBlog(Blog $blog): static
    {
        return $this->state(fn(array $attributes) => [
            'blog_id' => $blog->id,
        ]);
    }

    /**
     * For nesting comments
     */
    public function forComment(BlogComment $comment): static
    {
        return $this->state(fn(array $attributes) => [
            'comment_id' => $comment->id,
        ]);
    }
}
