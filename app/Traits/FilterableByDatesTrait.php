<?php

namespace App\Traits;

use Illuminate\Support\Carbon;

trait FilterableByDatesTrait
{
    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeToday($query, string $column = 'created_at'): mixed
    {
        return $query->whereDate($column, Carbon::today());
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeYesterday($query, string $column = 'created_at'): mixed
    {
        return $query->whereDate($column, Carbon::yesterday());
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeMonthToDate($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now();
        return $query->whereBetween($column, [$now->startOfMonth(), $now]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeQuarterToDate($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now();
        return $query->whereBetween($column, [$now->startOfQuarter(), $now]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeYearToDate($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now();
        return $query->whereBetween($column, [$now->startOfYear(), $now]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeLast7Days($query, string $column = 'created_at'): mixed
    {
        return $query->whereBetween($column, [Carbon::today()->subDays(6), Carbon::now()]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeLast30Days($query, string $column = 'created_at'): mixed
    {
        return $query->whereBetween($column, [Carbon::today()->subDays(29), Carbon::now()]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeLastQuarter($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now();
        return $query->whereBetween($column, [$now->startOfQuarter()->subMonths(3), $now->startOfQuarter()]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeLastYear($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now();
        return $query->whereBetween($column, [$now->subYear(), $now]);
    }
}
