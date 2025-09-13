<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Teams\Models\Klarna;

trait BelongsToKlarna
{
    public function klarna(): belongsTo
    {
        return $this->belongsTo(Klarna::class)->withDefault();
    }
}
