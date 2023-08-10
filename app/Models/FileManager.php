<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FileManager extends Model
{
    use HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    protected $table = 'file_manager';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_deletable' => 'boolean',
    ];

    /**
     * @return HasMany
     */
    public function productImages(): HasMany
    {
        return $this->hasMany(Product::class, 'image_id');
    }

    /**
     * @return HasMany
     */
    public function categoryImages(): HasMany
    {
        return $this->hasMany(CategoryImage::class, 'image_id');
    }

    /**
     * @return HasMany
     */
    public function blogImages(): HasMany
    {
        return $this->hasMany(Blog::class, 'image_id');
    }

    /**
     * @return HasMany
     */
    public function blogCategoryImages(): HasMany
    {
        return $this->hasMany(BlogCategory::class, 'image_id');
    }

    /**
     * @return HasMany
     */
    public function productGalleryImages(): HasMany
    {
        return $this->hasMany(ProductGallery::class, 'image_id');
    }

    /**
     * @return HasMany
     */
    public function paymentMethodImages(): HasMany
    {
        return $this->hasMany(PaymentMethod::class, 'image_id');
    }
}
