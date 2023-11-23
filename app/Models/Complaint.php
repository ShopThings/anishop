<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    public $timestamps = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'is_seen' => 'boolean',
        'created_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function statusChanger(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
