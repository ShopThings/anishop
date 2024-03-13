<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CityPostPrice extends Model
{
    protected $table = 'city_post_price';

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];

    /**
     * @return BelongsTo
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
