<?php

namespace App\Models;

use App\Enums\Comments\CommentConditionsEnum;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BlogComment extends Model
{
    use SoftDeletesTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasDeletedRelationTrait;

    protected $guarded = [
        'id',
    ];

    /**
     * @return BelongsTo
     */
    public function blog(): BelongsTo
    {
        return $this->belongsTo(Blog::class);
    }

    /**
     * @return BelongsTo
     */
    public function badge(): BelongsTo
    {
        return $this->belongsTo(BlogCommentBadge::class, 'badge_id');
    }

    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(BlogComment::class, 'comment_id');
    }

    /**
     * @param int $depth
     * @return BelongsTo
     */
    public function allParents(int $depth = 10): BelongsTo
    {
        if ($depth <= 0) {
            return $this->parent();
        }

        return $this->parent()->with(['allParents' => function ($query) use ($depth) {
            $query->allParents($depth - 1);
        }]);
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'comment_id');
    }

    /**
     * @param int $depth
     * @return HasMany
     */
    public function allChildren(int $depth = 10): HasMany
    {
        if ($depth <= 0) {
            return $this->children();
        }

        return $this->children()->with(['allChildren' => function ($query) use ($depth) {
            $query->allChildren($depth - 1);
        }]);
    }

    /**
     * @return bool
     */
    public function hasChildren(): bool
    {
        return $this->children()->count() > 0;
    }

    /**
     * @return bool
     */
    public function hasAcceptedChildren(): bool
    {
        return $this->children()->where('condition', CommentConditionsEnum::ACCEPTED->name)->count() > 0;
    }
}
