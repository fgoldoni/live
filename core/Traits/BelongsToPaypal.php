<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Paypals\Models\Paypal;

trait BelongsToPaypal
{
    public function paypal(): belongsTo
    {
        return $this->belongsTo(Paypal::class)->withDefault();
    }
}
