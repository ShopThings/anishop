<?php

namespace App\Models;

use App\Casts\StringToArray;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasTitleSluggableTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mews\Purifier\Casts\CleanHtml;

class Product extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasTitleSluggableTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'keywords' => StringToArray::class,
        'description' => CleanHtml::class,
        'is_available' => 'boolean',
        'is_commenting_allowed' => 'boolean',
        'is_published' => 'boolean',
    ];

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
        return $this->hasMany(Festival::class);
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
        return $this->hasMany(ProductAttributeProduct::class, 'product_attribute_value_id');
    }

    /**
     * @return HasMany
     */
    public function favoriteProducts(): HasMany
    {
        return $this->hasMany(UserFavoriteProduct::class, 'product_id');
    }
}
