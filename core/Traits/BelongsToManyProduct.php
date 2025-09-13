<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Products\Models\Product;
use Modules\Products\Models\ProductTicket;

trait BelongsToManyProduct
{
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)
            ->using(ProductTicket::class)
            ->withPivot('id', 'quantity', 'position')
            ->withTimestamps();
    }
}
