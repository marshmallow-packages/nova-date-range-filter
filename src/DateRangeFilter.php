<?php

namespace Marshmallow\Filters;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class DateRangeFilter extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'nova-date-range-filter';

    protected string $column;

    /**
     * Create a new filter instance.
     *
     * @param  string  $column
     * @return void
     */
    public function __construct($column, $name = null)
    {
        $this->column = $column;
        $this->name = $name ?? $column;
    }

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(NovaRequest $request, $query, $value)
    {
        $from = Carbon::parse($value[0])->startOfDay();
        if (count($value) == 1) {
            $to = Carbon::parse($value[0])->endOfDay();
        } else {
            $to = Carbon::parse($value[1])->endOfDay();
        }

        return $query->whereBetween($this->column, [$from, $to]);
    }

    public function enableTime()
    {
        $this->withMeta(['enableTime' => true]);
        return $this;
    }

    public function dateFormat($format)
    {
        $this->withMeta(['dateFormat' => $format]);
        return $this;
    }

    public function showMonths($months)
    {
        $this->withMeta(['showMonths' => $months]);
        return $this;
    }

    public function minTime($minTime)
    {
        $this->withMeta(['minTime' => $minTime]);
        return $this;
    }

    public function maxTime($maxTime)
    {
        $this->withMeta(['maxTime' => $maxTime]);
        return $this;
    }

    public function placeholder($placeholder)
    {
        $this->withMeta(['placeholder' => $placeholder]);
        return $this;
    }

    public function options(NovaRequest $request)
    {
        return [
            'firstDayOfWeek' => 1,
            'separator' => '-',
            'enableTime' => Arr::get($this->meta, 'enableTime', false),
            'enableSeconds' => false,
            'twelveHourTime' => false,
            'mode' => 'range'
        ];
    }

    /**
     * Get the key for the filter.
     *
     * @return string
     */
    public function key()
    {
        return 'timestamp_' . $this->column;
    }

    /**
     * Get the displayable name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return __('Filter By ') . $this->name;
    }
}
