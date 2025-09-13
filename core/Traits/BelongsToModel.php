<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Models\Models\Model;

trait BelongsToModel
{
    public function model(): belongsTo
    {
        return $this->belongsTo(Model::class)->withDefault([]);
    }
}
