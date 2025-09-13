<?php

namespace Core\Traits;

use Illuminate\Support\Facades\Storage;

trait HasQrCodeUrl
{
    public function getQrCodeUrlAttribute(): string
    {
        return $this->qr_code
            ? Storage::disk($this->defaultDisk())->url($this->qr_code)
            : $this->defaultQrCodeUrl();
    }

    protected function defaultDisk(): string
    {
        return config('filesystems.default', 'public');
    }

    protected function defaultQrCodeUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode((string)$this->uid) . '&color=7F9CF5&background=EBF4FF';
    }
}
