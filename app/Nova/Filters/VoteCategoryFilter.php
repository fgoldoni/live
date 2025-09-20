<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Modules\Votes\Models\VoteCategory;

class VoteCategoryFilter extends Filter
{
    public $name = 'Catégorie de vote';

    /**
     * The filter's component.
     */
    public function component()
    {
        return 'select-filter';
    }

    /**
     * Apply the filter to the given query.
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('vote_category_id', $value);
    }

    /**
     * Get the filter's available options.
     */
    #[\Override]
    public function options(Request $request)
    {
        $locale = app()->getLocale();

        return VoteCategory::query()
            ->select(['id', 'name', 'created_at'])
            ->orderByDesc('created_at')
            ->get()
            ->sortBy(fn ($cat) => mb_strtolower(
                $cat->getTranslation('name', $locale)
                ?? ($cat->name[$locale] ?? (is_string($cat->name) ? $cat->name : reset((array) $cat->name)))
            ))
            ->mapWithKeys(function ($cat) use ($locale) {
                $label = $cat->getTranslation('name', $locale)
                    ?? ($cat->name[$locale] ?? (is_string($cat->name) ? $cat->name : reset((array) $cat->name)));
                return [$label => $cat->id];
            })
            ->toArray();
    }

}
