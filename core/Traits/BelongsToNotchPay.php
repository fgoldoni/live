<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\NotchPays\Models\NotchPay;

trait BelongsToNotchPay
{
    public function notchPay(): belongsTo
    {
        return $this->belongsTo(NotchPay::class)->withDefault();
    }
}
