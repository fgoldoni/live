<?php

namespace Core\Traits;

use Illuminate\Support\Carbon;

trait SuspendedTrait
{
    public function suspended(): bool
    {
        return !is_null($this->suspended_until) && Carbon::now()->lessThan($this->suspended_until);
    }
}
