<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Teams\Models\Terminal;

trait HasOneTerminal
{
    public function terminal(): HasOne
    {
        return $this->hasOne(Terminal::class)->withDefault();
    }
}
