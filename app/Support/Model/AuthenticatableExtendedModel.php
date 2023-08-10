<?php

namespace App\Support\Model;

use App\Traits\ModelScopeTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @inheritDoc
 */
abstract class AuthenticatableExtendedModel extends Authenticatable
{
    use ModelScopeTrait,
        ModifierBootTrait;
}
