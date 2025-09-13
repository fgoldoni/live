<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Tickets\Models\Ticket;

trait BelongsToTicket
{
    public function ticket(): belongsTo
    {
        return $this->belongsTo(Ticket::class)->withDefault();
    }
}
