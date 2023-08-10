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
        return $this->belongsTo(get_class($this), 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(get_class($this), 'parent_id');
    }

    /**
     * @return HasMany
     */
    public function allChildren(): HasMany
    {
        return $this->children()->with('allChildren');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeChildless(Builder $query): Builder
    {
        return $query->has('children', '=', 0);
    }
}
