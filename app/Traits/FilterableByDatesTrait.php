<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * @method Builder today(string $column = 'created_at')
 * @method Builder yesterday(string $column = 'created_at')
 * @method Builder monthToDate(string $column = 'created_at')
 * @method Builder quarterToDate(string $column = 'created_at')
 * @method Builder yearToDate(string $column = 'created_at')
 * @method Builder last7Days(string $column = 'created_at')
 * @method Builder last30Days(string $column = 'created_at')
 * @method Builder lastQuarter(string $column = 'created_at')
 * @method Builder lastYear(string $column = 'created_at')
 * @method Builder thisWeek(string $column = 'created_at')
 * @method Builder thisMonth(string $column = 'created_at')
 * @method Builder lastMonths(int $months, string $column = 'created_at')
 * @method Builder thisYear(string $column = 'created_at')
 */
trait FilterableByDatesTrait
{
    use CompanyTimezoneDetectorTrait;

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeToday($query, string $column = 'created_at'): mixed
    {
        return $query->whereDate($column, Carbon::today()->timezone($this->getCompanyTimezone()));
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeYesterday($query, string $column = 'created_at'): mixed
    {
        return $query->whereDate($column, Carbon::yesterday()->timezone($this->getCompanyTimezone()));
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeMonthToDate($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now()->timezone($this->getCompanyTimezone());
        return $query->whereBetween($column, [$now->copy()->startOfMonth(), $now->copy()]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeQuarterToDate($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now()->timezone($this->getCompanyTimezone());
        return $query->whereBetween($column, [$now->copy()->startOfQuarter(), $now->copy()]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeYearToDate($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now()->timezone($this->getCompanyTimezone());
        return $query->whereBetween($column, [$now->copy()->startOfYear(), $now->copy()]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeLast7Days($query, string $column = 'created_at'): mixed
    {
        $timezone = $this->getCompanyTimezone();
        return $query->whereBetween($column, [
            Carbon::today()->timezone($timezone)->subDays(6),
            Carbon::now()->timezone($timezone)
        ]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeLast30Days($query, string $column = 'created_at'): mixed
    {
        $timezone = $this->getCompanyTimezone();
        return $query->whereBetween($column, [
            Carbon::today()->timezone($timezone)->subDays(29), Carbon::now()->timezone($timezone)
        ]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeLastQuarter($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now()->timezone($this->getCompanyTimezone());
        return $query->whereBetween($column, [$now->copy()->startOfQuarter()->subMonths(3), $now->copy()->startOfQuarter()]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeLastYear($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now()->timezone($this->getCompanyTimezone());
        return $query->whereBetween($column, [$now->copy()->subYear(), $now->copy()]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeThisWeek($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now()->timezone($this->getCompanyTimezone());
        return $query->whereBetween($column, [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeThisMonth($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now()->timezone($this->getCompanyTimezone());
        return $query->whereBetween($column, [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()]);
    }

    /**
     * @param $query
     * @param int $months
     * @param string $column
     * @return mixed
     */
    public function scopeLastMonths($query, int $months, string $column = 'created_at'): mixed
    {
        $now = Carbon::now()->timezone($this->getCompanyTimezone());
        return $query->whereBetween($column, [$now->copy()->subMonths($months)->startOfMonth(), $now->copy()->endOfMonth()]);
    }

    /**
     * @param $query
     * @param string $column
     * @return mixed
     */
    public function scopeThisYear($query, string $column = 'created_at'): mixed
    {
        $now = Carbon::now()->timezone($this->getCompanyTimezone());
        return $query->whereBetween($column, [$now->copy()->startOfYear(), $now->copy()->endOfYear()]);
    }
}
