<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasSluggableTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Festival extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasSluggableTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'is_published' => 'boolean',
    ];

    protected $sluggableField = 'title';

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProductFestival::class, 'festival_id');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActivated(Builder $query): Builder
    {
        return $query
            ->where('start_at', '>=', now())
            ->where('end_at', '<=', now())
            ->orderByDesc('created_at');
    }
}
