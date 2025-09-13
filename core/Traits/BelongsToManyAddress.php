<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Addresses\Models\Address;

trait BelongsToManyAddress
{
    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class);
    }
}
