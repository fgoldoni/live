<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Transactions\App\Models\Transaction;

trait MorphManyTransaction
{
    public function transactions(): MorphMany
    {
        return $this->morphMany(Transaction::class, 'model');
    }
}
