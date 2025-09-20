<?php

declare(strict_types=1);

namespace Modules\Countries\Models;

use Laravel\Scout\Searchable;

class Division extends \Khsing\World\Models\Division
{
    use Searchable;

    protected $guarded = [];

    public function searchableAs(): string
    {
        return 'divisions_index';
    }
}
