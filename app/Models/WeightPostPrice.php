<?php

namespace App\Models;

use App\Support\Model\ExtendedModel as Model;

class WeightPostPrice extends Model
{
    public $timestamps = false;

    protected $hasCreatedBy = false;

    protected $hasUpdatedBy = false;

    protected $guarded = [
        'id',
    ];
}
