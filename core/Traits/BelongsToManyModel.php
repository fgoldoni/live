<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Models\Models\Model;

trait BelongsToManyModel
{
    public function models(): BelongsToMany
    {
        return $this->belongsToMany(Model::class);
    }
}
