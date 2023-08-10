<?php

namespace App\Models;

use App\Enums\Products\ProductAttributeTypesEnum;
use App\Support\Model\ExtendedModel as Model;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductAttribute extends Model
{
    use HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'type' => ProductAttributeTypesEnum::class,
    ];

    /**
     * @return HasMany
     */
    public function productAttrs(): HasMany
    {
        return $this->hasMany(ProductAttributeCategory::class, 'product_attribute_id');
    }

    /**
     * @return HasMany
     */
    public function attrValues(): HasMany
    {
        return $this->hasMany(ProductAttributeValue::class, 'product_attribute_id');
    }
}
