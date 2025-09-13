<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Stripes\Models\Stripe;

trait HasOneStripe
{
    public function stripe(): HasOne
    {
        return $this->hasOne(Stripe::class)->withDefault();
    }
}
