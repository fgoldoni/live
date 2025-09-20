<?php

declare(strict_types=1);

namespace Modules\Countries\Models;

use Core\Traits\BelongsToCountry;
use Laravel\Scout\Searchable;

class City extends \Khsing\World\Models\City
{
    use Searchable;
    use BelongsToCountry;

    public $timestamps = false;

    protected $guarded = [];

    public function searchableAs(): string
    {
        return 'cities_index';
    }
}
