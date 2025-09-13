<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Drinks\Models\Drink;
use Modules\Drinks\Models\DrinkTicket;

trait BelongsToManyDrink
{
    public function drinks(): BelongsToMany
    {
        return $this->belongsToMany(Drink::class)
            ->orderByDesc('price')
            ->using(DrinkTicket::class)
            ->withPivot('id', 'quantity', 'position')
            ->withTimestamps();
    }
}
