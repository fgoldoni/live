<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Collections\Models\Collection;

trait BelongsToCollection
{
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class)->withDefault(['name' => '']);
    }
}
