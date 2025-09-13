<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Countries\Models\Country;

trait BelongsToCountry
{
    public function country(): belongsTo
    {
        return $this->belongsTo(Country::class)->withDefault(['name' => '']);
    }
}
