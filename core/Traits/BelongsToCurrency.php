<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Currencies\Models\Currency;

trait BelongsToCurrency
{
    public function currency(): belongsTo
    {
        return $this->belongsTo(Currency::class)->withDefault();
    }
}
