<?php

namespace Core\Traits;

use Illuminate\Support\Facades\Storage;

trait HasLogoUrl
{
    public function getLogoUrlAttribute(): string
    {
        return $this->logo
            ? Storage::disk($this->defaultDisk())->url($this->logo)
            : $this->defaultLogoUrl();
    }

    protected function defaultDisk(): string
    {
        return config('filesystems.default', 'public');
    }

    protected function defaultLogoUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode((string)$this->name) . '&color=7F9CF5&background=EBF4FF';
    }
}
