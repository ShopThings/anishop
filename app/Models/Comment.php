<?php

namespace App\Models;

use App\Casts\CleanHtmlCast;
use App\Casts\StringToArray;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Database\Factories\ProductCommentFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use SoftDeletesTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait,
        HasDeletedRelationTrait,
        HasFactory;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'pros' => StringToArray::class,
        'cons' => StringToArray::class,
        'answer' => CleanHtmlCast::class,
        'answered_at' => 'datetime',
        'changed_condition_at' => 'datetime',
    ];

    protected static function newFactory()
    {
        return ProductCommentFactory::new();
    }

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
    public function answeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'answered_by');
    }

    /**
     * @return BelongsTo
     */
    public function conditionChanger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_condition_by');
    }
}
