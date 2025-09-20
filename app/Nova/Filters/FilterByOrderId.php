<?php

namespace App\Nova\Filters;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Laravel\Nova\Filters\Filter;
use Laravel\Nova\Http\Requests\NovaRequest;
use Modules\Paypals\Models\PaypalLog;

class FilterByOrderId extends Filter
{
    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     */
    public function apply(NovaRequest $request, Builder $query, mixed $value): Builder
    {
        return $query->where('order_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @return array<string, string>
     */
    #[\Override]
    public function options(NovaRequest $request): array
    {
        return PaypalLog::query()
            ->distinct()
            ->orderBy('order_id')
            ->pluck('order_id', 'order_id')
            ->toArray();
    }
}
