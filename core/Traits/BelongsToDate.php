<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Events\Models\Date;

trait BelongsToDate
{
    public function date(): belongsTo
    {
        return $this->belongsTo(Date::class)->withDefault();
    }
}
