<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Tickets\Models\Ticket;

trait BelongsToManyTicket
{
    public function tickets(): BelongsToMany
    {
        return $this->belongsToMany(Ticket::class);
    }
}
