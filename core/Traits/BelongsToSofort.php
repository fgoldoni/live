<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Teams\Models\Sofort;

trait BelongsToSofort
{
    public function sofort(): belongsTo
    {
        return $this->belongsTo(Sofort::class)->withDefault();
    }
}
