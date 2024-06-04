<?php

namespace App\Models;

use App\Casts\CleanHtmlCast;
use App\Casts\StringToArray;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasSluggableTrait;
use App\Traits\HasUpdatedRelationTrait;
use App\Traits\VisitorViewTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mews\Purifier\Casts\CleanHtml;
use Shetabit\Visitor\Traits\Visitable;

class Product extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasSluggableTrait,
        Visitable,
        VisitorViewTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'quick_properties' => 'array',
        'properties' => 'array',
        'keywords' => StringToArray::class,
        'brief_description' => CleanHtml::class,
        'description' => CleanHtmlCast::class,
        'is_available' => 'boolean',
        'is_commenting_allowed' => 'boolean',
        'is_published' => 'boolean',
    ];

    protected $sluggableField = 'escaped_title';

    /**
     * @inheritDoc
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @return BelongsTo
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    /**
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return HasMany
     */
    public function items(): HasMany
    {
        return $this->hasMany(ProductProperty::class, 'product_id');
    }

    /**
     * @return HasMany
     */
    public function festivals(): HasMany
    {
        return $this->hasMany(ProductFestival::class);
    }

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
    public function images(): HasMany
    {
        return $this->hasMany(ProductGallery::class, 'product_id');
    }

    /**
     * @return HasMany
     */
    public function relatedProducts(): HasMany
    {
        return $this->hasMany(RelatedProduct::class, 'product_id');
    }

    /**
     * @return HasMany
     */
    public function relatedProductItems(): HasMany
    {
        return $this->hasMany(RelatedProduct::class, 'related_id');
    }

    /**
     * @return HasMany
     */
    public function productAttrValues(): HasMany
    {
        return $this->hasMany(ProductAttributeProduct::class, 'product_id');
    }

    /**
     * @return HasMany
     */
    public function favoriteProducts(): HasMany
    {
        return $this->hasMany(UserFavoriteProduct::class, 'product_id');
    }
}
