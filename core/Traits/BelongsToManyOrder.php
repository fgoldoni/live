<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Orders\App\Models\Order;

trait BelongsToManyOrder
{
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class);
    }
}
