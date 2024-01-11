<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddressUser extends Model
{
    use HasFactory;

    protected $table = 'address_user';

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    protected static function newFactory()
    {
        return AddressFactory::new();
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    /**
     * @return BelongsTo
     */
    public function province(): BelongsTo
    {
        return $this->belongsTo(Province::class);
    }
}
