<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Teams\Models\Klarna;

trait HasOneKlarna
{
    public function klarna(): HasOne
    {
        return $this->hasOne(Klarna::class)->withDefault();
    }
}
