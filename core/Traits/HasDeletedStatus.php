<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasDeletedStatus
{
    public function deletedStatus(): Attribute
    {
        return Attribute::get(fn (): string => $this->deleted_at
            ? 'deleted'
            : 'active');
    }
}
