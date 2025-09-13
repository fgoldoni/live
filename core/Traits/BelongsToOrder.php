<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Orders\Models\Order;

trait BelongsToOrder
{
    public function order(): belongsTo
    {
        return $this->belongsTo(Order::class)->withDefault();
    }
}
