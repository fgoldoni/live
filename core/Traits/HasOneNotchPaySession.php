<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\NotchPays\Models\NotchPaySession;

trait HasOneNotchPaySession
{
    public function notchPaySession(): HasOne
    {
        return $this->hasOne(NotchPaySession::class)->withDefault();
    }
}
