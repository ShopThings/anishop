<?php

namespace App\Models;

use App\Casts\StringToArray;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasNameSluggableTrait;
use App\Traits\HasParentRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * {@inheritdoc}
 * @method Builder childless()
 */
class BlogCategory extends Model
{
    use SoftDeletesTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasDeletedRelationTrait,
        HasParentRelationTrait,
        HasNameSluggableTrait;

    protected $table = 'blog_categories';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'keywords' => StringToArray::class,
    ];

    /**
     * @return HasMany
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
}
