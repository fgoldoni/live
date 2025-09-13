<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Teams\Models\PaypalConnect;

trait HasOnePaypalConnect
{
    public function paypalConnect(): HasOne
    {
        return $this->hasOne(PaypalConnect::class);
    }
}
