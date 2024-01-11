<?php

namespace App\Models;

use App\Casts\StringToArray;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasSluggableTrait;
use App\Traits\HasParentRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Database\Factories\BlogCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Builder;

/**
 * @inheritDoc
 * @method Builder childless()
 */
class BlogCategory extends Model
{
    use SoftDeletesTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasDeletedRelationTrait,
        HasParentRelationTrait,
        HasSluggableTrait,
        HasFactory;

    protected $table = 'blog_categories';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'keywords' => StringToArray::class,
    ];

    protected $sluggableField = 'escaped_name';

    protected static function newFactory()
    {
        return BlogCategoryFactory::new();
    }

    /**
     * @return HasMany
     */
    public function blogs(): HasMany
    {
        return $this->hasMany(Blog::class, 'category_id');
    }
}
