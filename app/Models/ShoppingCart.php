<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;

class ShoppingCart extends Model
{
    protected $table = 'shoppingcart';

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $primaryKey = 'identifier';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $guarded = [];

    protected $casts = [
        'content' => 'encrypted:array',
    ];
}
