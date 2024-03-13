<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

trait HasParentRelationTrait
{
    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
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
        return $this->hasMany(static::class, 'parent_id');
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
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithoutChildren(Builder $query): Builder
    {
        return $query->has('children', '=', 0);
    }
}
