<?php

namespace App\Models;

use App\Casts\StringToArray;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasSluggableTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Brand extends Model
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
        'keywords' => StringToArray::class,
        'show_in_slider' => 'boolean',
        'is_published' => 'boolean',
        'is_deletable' => 'boolean',
    ];

    protected $sluggableField = 'latin_name';

    /**
     * @return BelongsTo
     */
    public function image(): BelongsTo
    {
        return $this->belongsTo(FileManager::class, 'image_id');
    }

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }
}
