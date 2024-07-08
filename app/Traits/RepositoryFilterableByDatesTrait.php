<?php

namespace App\Traits;

use App\Enums\Charts\ChartPeriodsEnum;
use Exception;
use Hekmatinasser\Verta\Verta;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

trait RepositoryFilterableByDatesTrait
{
    use CompanyTimezoneDetectorTrait,
        AppTimezoneTrait;

    /**
     * Returns an array with following structure:
     * <code>
     * [
     *   [
     *     'label' => array,
     *     'data' => array,
     *   ],
     *   ...
     * ]
     * </code>
     *
     * @param Builder $query
     * @param ChartPeriodsEnum|null $period
     * @param string $dateColumn
     * @return array
     * @throws Exception
     */
    protected function getPeriodicDataFrom(
        Builder           $query,
        ?ChartPeriodsEnum $period,
        string            $dateColumn
    ): array
    {
        return match ($period?->value) {
            ChartPeriodsEnum::WEEKLY->value => $this->getWeeklyData($query, $dateColumn),
            ChartPeriodsEnum::MONTHLY->value => $this->getMonthlyData($query, $dateColumn),
            ChartPeriodsEnum::MONTHS_3->value => $this->getQuarterlyData($query, $dateColumn),
            ChartPeriodsEnum::MONTHS_6->value => $this->getSixMonthsData($query, $dateColumn),
            ChartPeriodsEnum::YEARLY->value => $this->getYearlyData($query, $dateColumn),
            default => $this->getTodayData($query, $dateColumn),
        };
    }

    /**
     * @param Builder $query
     * @param string $dateColumn
     * @return array
     * @throws Exception
     */
    private function getWeeklyData(Builder $query, string $dateColumn): array
    {
        $data = [];
        $startingPoint = $this->getLocaleDate();
        $startingPoint = $startingPoint?->startWeek() ?? $startingPoint->startOfWeek();
        for ($i = 0; $i < 7; $i++) {
            $tmpQuery = clone $query;
            $startDate = $startingPoint->copy()->addDays($i);
            $endDate = $startingPoint->copy()->addDays($i);
            $data[] = [
                'label' => $this->getWeeklyLabel($startDate),
                'data' => $tmpQuery->whereBetween($dateColumn, [
                    $startDate?->startDay()->toCarbon()->timezone($this->getAppTimezone()) ?? $startDate->startOfDay(),
                    $endDate?->endDay()->toCarbon()->timezone($this->getAppTimezone()) ?? $endDate->endOfDay(),
                ])->count(),
            ];
        }
        return $data;
    }

    /**
     * @param Carbon|null $date
     * @return Carbon|Verta
     */
    private function getLocaleDate(?Carbon $date = null): Carbon|Verta
    {
        if (app()->getLocale() == 'fa') {
            return vertaTz($date, $this->getCompanyTimezone());
        }

        return !is_null($date)
            ? Carbon::instance($date)->timezone($this->getCompanyTimezone())
            : Carbon::now($this->getCompanyTimezone());
    }

    /**
     * @param Carbon|Verta $weekDate
     * @return string
     */
    private function getWeeklyLabel(Carbon|Verta $weekDate): string
    {
        if (app()->getLocale() == 'fa') {
            $weekDate = vertaTz($weekDate);
        }

        return $weekDate->format('j F');
    }

    /**
     * @param Builder $query
     * @param string $dateColumn
     * @return array
     * @throws Exception
     */
    private function getMonthlyData(Builder $query, string $dateColumn): array
    {
        $data = [];
        $startingPoint = $this->getLocaleDate();
        $startingPoint = $startingPoint?->startMonth() ?? $startingPoint->startOfMonth();
        for ($i = 0; $i < 4; $i++) {
            $tmpQuery = clone $query;
            $startDate = $startingPoint->copy()->addWeeks($i);
            $startDate = $startDate?->startDay() ?? $startDate->startOfDay();
            $endDate = $startingPoint->copy()->addWeeks($i + 1)->subDay();
            $endDate = $endDate?->endDay() ?? $endDate->endOfDay();
            $data[] = [
                'label' => $this->getMonthlyLabel($startDate),
                'data' => $tmpQuery->whereBetween($dateColumn, [
                    $startDate?->toCarbon()->timezone($this->getAppTimezone()) ?? $startDate,
                    $endDate?->toCarbon()->timezone($this->getAppTimezone()) ?? $endDate,
                ])->count(),
            ];
        }
        return $data;
    }

    /**
     * @param Carbon|Verta $monthDate
     * @return string
     */
    private function getMonthlyLabel(Carbon|Verta $monthDate): string
    {
        if (app()->getLocale() == 'fa') {
            $monthDate = vertaTz($monthDate);
        }

        return $monthDate->format('j F') .
            trans('periodic.labels.monthly') .
            $monthDate->addWeek()->subDay()->format('j F');
    }

    /**
     * @param Builder $query
     * @param string $dateColumn
     * @return array
     * @throws Exception
     */
    private function getQuarterlyData(Builder $query, string $dateColumn): array
    {
        $data = [];
        $currentQuarter = $this->getLocaleDate()->subMonths(3);
        for ($i = 1; $i <= 3; $i++) {
            $data = $this->filterDataForMonth($query, $currentQuarter, $i, $dateColumn, $data);
        }
        return $data;
    }

    /**
     * @param Builder $query
     * @param Verta|Carbon $currentQuarter
     * @param int $i
     * @param string $dateColumn
     * @param array $data
     * @return array
     * @throws Exception
     */
    private function filterDataForMonth(
        Builder      $query,
        Verta|Carbon $currentQuarter,
        int          $i,
        string       $dateColumn,
        array        $data
    ): array
    {
        $tmpQuery = clone $query;
        $startDate = $currentQuarter->copy()->addMonths($i);
        $endDate = $currentQuarter->copy()->addMonths($i);
        $data[] = [
            'label' => $this->getQuarterlyLabel($startDate),
            'data' => $tmpQuery->whereBetween($dateColumn, [
                $startDate?->startMonth()->toCarbon()->timezone($this->getAppTimezone()) ?? $startDate->startOfMonth(),
                $endDate?->endMonth()->toCarbon()->timezone($this->getAppTimezone()) ?? $endDate->endOfMonth(),
            ])->count(),
        ];
        return $data;
    }

    /**
     * @param Carbon|Verta $monthDate
     * @return string
     */
    private function getQuarterlyLabel(Carbon|Verta $monthDate): string
    {
        if (app()->getLocale() == 'fa') {
            $monthDate = vertaTz($monthDate);
        }

        return $monthDate->format('F Y');
    }

    /**
     * @param Builder $query
     * @param string $dateColumn
     * @return array
     * @throws Exception
     */
    private function getSixMonthsData(Builder $query, string $dateColumn): array
    {
        $data = [];
        $currentMonth = $this->getLocaleDate()->subMonths(6);
        for ($i = 1; $i <= 6; $i++) {
            $data = $this->filterDataForMonth($query, $currentMonth, $i, $dateColumn, $data);
        }
        return $data;
    }

    /**
     * @param Builder $query
     * @param string $dateColumn
     * @return array
     * @throws Exception
     */
    private function getYearlyData(Builder $query, string $dateColumn): array
    {
        $data = [];
        $currentYear = $this->getLocaleDate();
        $currentYear = $currentYear?->startYear() ?? $currentYear->startOfYear();
        for ($i = 0; $i < 12; $i++) {
            $data = $this->filterDataForMonth($query, $currentYear, $i, $dateColumn, $data);
        }
        return $data;
    }

    /**
     * @param Builder $query
     * @param string $dateColumn
     * @return array
     * @throws Exception
     */
    private function getTodayData(Builder $query, string $dateColumn): array
    {
        $data = [];
        $startingPoint = $this->getLocaleDate();
        $startingPoint = $startingPoint?->startDay() ?? $startingPoint->startOfDay();
        for ($i = 0; $i < 24; $i++) {
            $tmpQuery = clone $query;
            $startDate = $startingPoint->copy()->addHours($i);
            $endDate = $startingPoint->copy()->addHours($i);
            $data[] = [
                'label' => $this->getDailyLabel($i),
                'data' => $tmpQuery->whereBetween($dateColumn, [
                    $startDate?->startHour()->toCarbon()->timezone($this->getAppTimezone()) ?? $startDate->startOfHour(),
                    $endDate?->endHour()->toCarbon()->timezone($this->getAppTimezone()) ?? $endDate->endOfHour(),
                ])->count(),
            ];
        }
        return $data;
    }

    /**
     * @param int $hour
     * @return string
     */
    private function getDailyLabel(int $hour): string
    {
        return (mb_strlen($hour) == 1 ? '0' : '') . $hour .
            trans('periodic.labels.daily') .
            (mb_strlen($hour + 1) == 1 ? '0' : '') . ($hour + 1);
    }
}
