<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Events\Models\Podium;

trait HasManyPodium
{
    public function podia(): HasMany
    {
        return $this->hasMany(Podium::class);
    }
}
