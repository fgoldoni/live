<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Attachments\App\Models\Attachment;

trait AttachableTrait
{
    public static function bootAttachableTrait(): void
    {
        self::deleted(function ($subject) {
            if ($subject->isForceDeleting()) {
                foreach ($subject->attachments()->get() as $attachment) {
                    $attachment->deleteFile();
                }
                $subject->attachments()->delete();
            }
        });
    }

    public function attachments(): MorphMany
    {
        return $this->morphMany(Attachment::class, 'attachable');
    }
}
