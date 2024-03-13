<?php

namespace App\Models;

use App\Casts\CleanHtmlCast;
use App\Support\Model\ExtendedModel as Model;
use App\Support\Model\SoftDeletesTrait;
use App\Traits\HasCreatedRelationTrait;
use App\Traits\HasDeletedRelationTrait;
use App\Traits\HasUpdatedRelationTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mews\Purifier\Casts\CleanHtml;

class ContactUs extends Model
{
    use SoftDeletesTrait,
        HasDeletedRelationTrait,
        HasCreatedRelationTrait,
        HasUpdatedRelationTrait;

    protected $table = 'contact_us';

    public $timestamps = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'message' => CleanHtml::class,
        'answer' => CleanHtmlCast::class,
        'is_seen' => 'boolean',
        'answered_at' => 'datetime',
        'changed_status_at' => 'datetime',
        'created_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function answeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function statusChanger(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
