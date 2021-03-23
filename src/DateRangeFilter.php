<?php

namespace Ampeco\Filters;

use Laravel\Nova\Filters\Filter;

abstract class DateRangeFilter extends Filter
{
    public $component = 'date-range-filter';

    public function enableTime()
    {
        return $this->withMeta(['enableTime' => true]);
    }

    public function modeFormat()
    {
        return $this->withMeta(['mode' => $this->mode]);
    }

    public function dateFormat($format)
    {
        return $this->withMeta(['dateFormat' => $format]);
    }

    public function placeholder($placeholder)
    {
        return $this->withMeta(['placeholder' => $placeholder]);
    }
}
