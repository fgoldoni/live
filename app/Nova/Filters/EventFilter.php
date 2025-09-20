<?php

namespace App\Nova\Filters;

use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Events\Models\Event;

class EventFilter extends Filter
{
    public $component = 'select-filter';


    #[\Override]
    public function apply(NovaRequest $request, $query, $value)
    {
        return $query->where('event_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param \Laravel\Nova\Http\Requests\NovaRequest $request
     *
     * @return array
     */
    #[\Override]
    public function options(NovaRequest $request)
    {
        if ($request->user()->hasPermissionTo('manager')) {
            /** @phpstan-ignore-next-line */
            return Event::where('online', true)
                ->orderBy('name')
                ->orderByDesc('created_at')
                ->get()
                ->pluck('id', 'name')
                ->toArray();
        }

        return [];
    }
}
