<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Casts\Attribute;

trait HasStatusForUser
{
    public function status(): Attribute
    {
        return Attribute::get(fn (): string => $this->deleted_at ? __('deleted') : __('active'));
    }
}
