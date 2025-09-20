<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ReportTypeFilter extends Filter
{
    public $name = 'Type';

    public function apply(Request $request, $query, $value)
    {
        return $query->where('type', $value);
    }

    #[\Override]
    public function options(Request $request)
    {
        return [
            __('Bug')              => 'bug',
            __('Suggestion')       => 'suggestion',
            __('Question')         => 'question',
            __('Abuse')            => 'abuse',
            __('Testimonial')      => 'testimonial',
        ];
    }
}
