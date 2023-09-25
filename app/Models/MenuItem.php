<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasParentRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuItem extends Model
{
    use HasCreatedRelationTrait,
        HasParentRelationTrait;

    public $timestamps = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'can_have_children' => 'boolean',
        'is_published' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}
