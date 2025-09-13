<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\CheckinSessions\Models\CheckinSession;

trait BelongsToCheckinSession
{
    public function checkinSession(): belongsTo
    {
        return $this->belongsTo(CheckinSession::class)->withDefault();
    }
}
