<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasStatus
{
    public function status(): Attribute
    {
        return Attribute::get(fn (): string => $this->deleted_at ? 'deleted' : ($this->online ? 'online' : 'offline'));
    }
}
