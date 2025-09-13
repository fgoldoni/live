<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Teams\Models\Sofort;

trait HasOneSofort
{
    public function sofort(): HasOne
    {
        return $this->hasOne(Sofort::class)->withDefault();
    }
}
