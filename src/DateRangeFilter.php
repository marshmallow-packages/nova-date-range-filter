<?php

namespace Ampeco\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

abstract class DateRangeFilter extends Filter
{
    public $component = 'date-range-filter';
    
    public function __construct()
    {
        $this->dateFormat('Y-m-d');
    }
    
    
    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        //
    }
    
    public function dateFormat($format){
        $this->withMeta(['dateFormat' => $format]);
    }
    
    public function placeholder($placeholder)
    {
        $this->withMeta(['placeholder' => $placeholder]);
    }
}
