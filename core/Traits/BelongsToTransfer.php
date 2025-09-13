<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Teams\Models\Transfer;

trait BelongsToTransfer
{
    public function transfer(): belongsTo
    {
        return $this->belongsTo(Transfer::class)->withDefault();
    }
}
