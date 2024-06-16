<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;

class ShoppingCart extends Model
{
    protected $table = 'shoppingcart';

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $casts = [
        'content' => 'encrypted:array',
    ];
}
