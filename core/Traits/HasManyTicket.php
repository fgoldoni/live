<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Tickets\Models\Ticket;

trait HasManyTicket
{
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class)
            ->orderBy('price');
    }
}
