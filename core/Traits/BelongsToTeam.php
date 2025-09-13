<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Teams\Models\Team;

trait BelongsToTeam
{
    public function team(): belongsTo
    {
        return $this->belongsTo(Team::class)->withDefault();
    }
}
