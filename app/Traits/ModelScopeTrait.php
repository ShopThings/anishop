<?php

namespace App\Traits;

use App\Enums\DatabaseEnum;
use App\Support\Model\CaseWhen;
use App\Support\Model\Concat;
use Illuminate\Database\Eloquent\Builder;

/**
 * @method CaseWhen case()
 * @method Concat concat()
 * @method Builder published()
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

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePublished(Builder $query): Builder
    {
        if (!array_key_exists('is_published', $this->getCasts())) return $query;

        return $query->where($this->getTable() . '.is_published', DatabaseEnum::DB_YES);
    }
}
