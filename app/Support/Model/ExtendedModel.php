<?php

namespace App\Support\Model;

use App\Traits\ModelScopeTrait;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Model;

abstract class ExtendedModel extends Model
{
    use ModelScopeTrait,
        ModifierBootTrait;
}
