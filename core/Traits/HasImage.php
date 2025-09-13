<?php

namespace Core\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasImage
{
    /**
     * Update the user's avatar photo.
     *
     * @return void
     */
    public function updateImage(UploadedFile $photo)
    {
        tap($this->image_path, function ($previous) use ($photo) {
            $this->forceFill([
                'image_path' => $photo->storePublicly(
                    '/',
                    ['disk' => $this->imageDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->imageDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's avatar photo.
     *
     * @return void
     */
    public function deleteImage()
    {
        Storage::disk($this->imageDisk())->delete($this->image_path);

        $this->forceFill([
            'image_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's avatar photo.
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return match ($this->subdomain) {
            'netflix'       => asset('images/logos/netflix.png'),
            'tesla'         => asset('images/logos/tesla.png'),
            'deutsche-bank' => asset('images/logos/deutsche-bank.png'),
            default         => $this->image_path
                ? Storage::disk($this->imageDisk())->url($this->image_path)
                : $this->defaultImageUrl(),
        };
    }

    /**
     * Get the default avatar photo URL if no avatar photo has been uploaded.
     */
    protected function defaultImageUrl(): string
    {
        return 'https://ui-avatars.com/api/?name=' . urlencode((string) $this->name) . '&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the disk that avatar photos should be stored on.
     */
    protected function imageDisk(): string
    {
        return 'uploads';
    }
}
