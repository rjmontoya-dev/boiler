<?php

namespace Boiler\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait CanFilterDate
{
    /**
     * Apply a date range
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @param \Carbon\Carbon|string|array|null $range
     * @param string $field
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function dateRange(Builder $builder, Carbon | string | array | null $range, string $field = 'created_at'): Builder
    {
        [$start, $end] = match (true) {
            $range instanceof Carbon => [$range, $range],
            is_string($range) => [$this->parseDate($range), $this->parseDate($range)],
            is_array($range) && count($range) === 2 => [$this->parseDate($range[0]), $this->parseDate($range[1])],
            default => [null, null],
        };

        return !is_null($start) && !is_null($end)
        ? $builder->whereBetween($field, [$start->startOfDay(), $end->endOfDay()])
        : $builder;
    }

    /**
     * Parses a date string and adjusts it to the application's timezone.
     *
     * @param string $date the date string to parse
     *
     * @return \Carbon\Carbon the parsed date as a Carbon instance
     */
    protected function parseDate(string $date): Carbon
    {
        return Carbon::parse($date)->setTimezone(config('app.timezone'));
    }
}
