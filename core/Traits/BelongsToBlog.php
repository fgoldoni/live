<?php

namespace Core\Traits;

use Modules\Blog\Entities\Blog;

trait BelongsToBlog
{
    public function blog()
    {
        return $this->belongsTo(Blog::class)->withDefault();
    }
}
