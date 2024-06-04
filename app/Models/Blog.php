<?php

namespace App\Models;

use App\Casts\CleanHtmlCast;
use App\Casts\StringToArray;
use App\Enums\Comments\CommentConditionsEnum;
use App\Enums\DatabaseEnum;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasSluggableTrait;
use App\Traits\HasUpdatedRelationTrait;
use App\Traits\VisitorViewTrait;
use Database\Factories\BlogFactory;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Shetabit\Visitor\Traits\Visitable;

/**
 * @method Builder archivedInYear(DateTimeInterface|int|string $year)
 * @method Builder archivedInMonth(DateTimeInterface|int|string $year, DateTimeInterface|int|string $month)
 */
class Blog extends Model
{
    use SoftDeletesTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasDeletedRelationTrait,
        HasSluggableTrait,
        Visitable,
        VisitorViewTrait,
        HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'keywords' => StringToArray::class,
        'description' => CleanHtmlCast::class,
        'is_commenting_allowed' => 'boolean',
        'is_published' => 'boolean',
    ];

    protected $sluggableField = 'escaped_title';

    protected static function newFactory()
    {
        return BlogFactory::new();
    }

    /**
     * @inheritDoc
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @param Builder $query
     * @param $year
     * @return Builder
     */
    public function scopeArchivedInYear(Builder $query, $year): Builder
    {
        return $query->whereYear('created_at', $year);
    }

    /**
     * @param Builder $query
     * @param $year
     * @param $month
     * @return Builder
     */
    public function scopeArchivedInMonth(Builder $query, $year, $month): Builder
    {
        return $query
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month);
    }

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(FileManager::class, 'image_id');
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'category_id');
    }

    /**
     * @return HasMany
     */
    public function comments(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'blog_id');
    }

    /**
     * @return int
     */
    public function commentsCount(): int
    {
        return $this->comments()->where('condition', CommentConditionsEnum::ACCEPTED->value)->count();
    }

    /**
     * @return HasMany
     */
    public function votes(): HasMany
    {
        return $this->hasMany(BlogVote::class, 'blog_id');
    }

    /**
     * @return int
     */
    public function upVoteCount(): int
    {
        return $this->votes()->where('is_voted', DatabaseEnum::DB_YES)->count();
    }

    /**
     * @return int
     */
    public function downVoteCount(): int
    {
        return $this->votes()->where('is_voted', DatabaseEnum::DB_NO)->count();
    }
}
