<?php

namespace Core\Traits;

trait WithinDays
{
    public function scopeRegisteredWithinDays($query, $days)
    {
        return $query->where('created_at', '>=', now()->subDays($days)->startOfDay());
    }

    public function scopeLiveWithinDays($query, $days)
    {
        return $query->where('live_at', '>=', now()->subDays($days)->startOfDay());
    }
}
