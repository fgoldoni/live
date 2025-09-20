<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;

class OnlineFilter extends Filter
{
    #[\Override]
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('online', $value);
    }


    #[\Override]
    public function options(NovaRequest $request)
    {
        return [
            'Yes' => true,
            'No'  => false
        ];
    }
}
