<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Transactions\Models\Transaction;

trait HasManyTransaction
{
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
