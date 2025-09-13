<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Teams\Models\StripeConnect;

trait HasOneStripeConnect
{
    public function stripeConnect(): HasOne
    {
        return $this->hasOne(StripeConnect::class);
    }
}
