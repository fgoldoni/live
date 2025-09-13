<?php

namespace Core\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasMenu
{
    /**
     * Update the user's avatar photo.
     *
     * @return void
     */
    public function updateMenu(UploadedFile $photo)
    {
        tap($this->menu_path, function ($previous) use ($photo) {
            $this->forceFill([
                'menu_path' => $photo->storePublicly(
                    '/',
                    ['disk' => $this->menuDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->menuDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's avatar photo.
     *
     * @return void
     */
    public function deleteMenu()
    {
        Storage::disk($this->menuDisk())->delete($this->image_path);

        $this->forceFill([
            'menu_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's avatar photo.
     *
     * @return string
     */
    public function getMenuUrlAttribute()
    {
        return match ($this->subdomain) {
            'netflix'       => asset('images/logos/netflix.png'),
            'tesla'         => asset('images/logos/tesla.png'),
            'deutsche-bank' => asset('images/logos/deutsche-bank.png'),
            default         => $this->menu_path
                ? Storage::disk($this->menuDisk())->url($this->menu_path)
                : $this->defaultMenuUrl(),
        };
    }

    /**
     * Get the default avatar photo URL if no avatar photo has been uploaded.
     */
    protected function defaultMenuUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode((string) $this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the disk that avatar photos should be stored on.
     */
    protected function menuDisk(): string
    {
        return 'uploads';
    }
}
