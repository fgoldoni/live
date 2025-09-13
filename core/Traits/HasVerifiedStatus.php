<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasVerifiedStatus
{
    public function markAsVerified(): bool
    {
        return $this->update(['verified_at' => now()]);
    }

    public function unverify(): bool
    {
        return $this->update(['verified_at' => null]);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->whereNotNull('verified_at');
    }

    public function scopeUnverified(Builder $query): Builder
    {
        return $query->whereNull('verified_at');
    }

    public function isVerified(): bool
    {
        return $this->verified_at !== null;
    }
}
