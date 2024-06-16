<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Traits\HasCreatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAttributeProduct extends Model
{
    use HasCreatedRelationTrait;

    public $timestamps = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * @return BelongsTo
     */
    public function attrValue(): BelongsTo
    {
        return $this->belongsTo(ProductAttributeValue::class, 'product_attribute_value_id');
    }
}
