<?php

declare(strict_types=1);

namespace Modules\Countries\Models;

use Laravel\Scout\Searchable;

class Country extends \Khsing\World\Models\Country
{
    use Searchable;

    protected $guarded = [];

    public function searchableAs(): string
    {
        return 'countries_index';
    }
}
