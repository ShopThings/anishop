<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasNameSluggableTrait;
use App\Traits\HasParentRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasParentRelationTrait,
        HasNameSluggableTrait;

    public $table = 'categories';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'show_in_menu' => 'boolean',
        'show_in_side_menu' => 'boolean',
        'show_in_slider' => 'boolean',
        'is_published' => 'boolean',
        'is_deletable' => 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * @return HasMany
     */
    public function productAttrs(): HasMany
    {
        return $this->hasMany(ProductAttributeCategory::class, 'category_id');
    }
}
