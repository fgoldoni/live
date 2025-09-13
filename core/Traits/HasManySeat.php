<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Seats\Models\Seat;

trait HasManySeat
{
    public function seats(): HasMany
    {
        return $this->hasMany(Seat::class);
    }
}
