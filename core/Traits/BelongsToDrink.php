<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Drinks\Models\Drink;

trait BelongsToDrink
{
    public function drink(): BelongsTo
    {
        return $this->belongsTo(Drink::class)->withDefault();
    }
}
