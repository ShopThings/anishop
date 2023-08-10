<?php

namespace App\Traits;

use App\Support\Model\CaseWhen;
use App\Support\Model\Concat;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method CaseWhen case()
 * @method Concat concat()
 */
trait ModelScopeTrait
{
    /**
     * Can build case when with simple structure and nested too
     *
     * @param Builder $query
     * @return CaseWhen
     */
    public function scopeCase(Builder $query): CaseWhen
    {
        return new CaseWhen($query);
    }

    /**
     * Can build concat for some expression
     *
     * @param Builder $query
     * @return Concat
     */
    public function scopeConcat(Builder $query): Concat
    {
        return new Concat($query);
    }
}
