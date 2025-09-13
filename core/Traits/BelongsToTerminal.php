<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Teams\Models\Terminal;

trait BelongsToTerminal
{
    public function terminal(): belongsTo
    {
        return $this->belongsTo(Terminal::class)->withDefault();
    }
}
