<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Agendas\Models\Agenda;

trait HasManyAgenda
{
    public function agendas(): HasMany
    {
        return $this->hasMany(Agenda::class);
    }
}
