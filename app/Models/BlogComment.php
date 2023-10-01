<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BlogComment extends Model
{
    use SoftDeletesTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasDeletedRelationTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
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
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
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
     * @return BelongsTo
     */
    public function answerTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answer_to');
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(BlogComment::class, 'comment_id');
    }

    /**
     * @return HasMany
     */
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }
}
