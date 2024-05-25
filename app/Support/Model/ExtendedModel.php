<?php

namespace App\Support\Model;

use App\Traits\FilterableByDatesTrait;
use App\Traits\ModelScopeTrait;
use Illuminate\Database\Eloquent\Model;

abstract class ExtendedModel extends Model
{
    use ModelScopeTrait,
        ModifierBootTrait,
        FilterableByDatesTrait;
}
