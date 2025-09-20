<?php

namespace App\Nova\Filters;

use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;
use Modules\Votes\Models\Nominee;

class NomineeFilter extends Filter
{
    public $name = 'Nominé';

    public function component()
    {
        return 'select-filter';
    }

    public function apply(Request $request, $query, $value)
    {
        return $query->where('nominee_id', $value);
    }

    #[\Override]
    public function options(Request $request)
    {
        return Nominee::orderBy('name')
            ->pluck('id', 'name')
            ->toArray();
    }
}
