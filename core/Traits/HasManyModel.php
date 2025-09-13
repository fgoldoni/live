<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Models\Models\Model;

trait HasManyModel
{
    public function models(): HasMany
    {
        return $this->hasMany(Model::class);
    }
}
