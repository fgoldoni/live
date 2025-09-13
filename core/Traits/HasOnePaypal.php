<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Paypals\Models\Paypal;

trait HasOnePaypal
{
    public function paypal(): HasOne
    {
        return $this->hasOne(Paypal::class);
    }
}
