<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Stripes\Models\Stripe;

trait BelongsToStripe
{
    public function stripe(): belongsTo
    {
        return $this->belongsTo(Stripe::class)->withDefault();
    }
}
