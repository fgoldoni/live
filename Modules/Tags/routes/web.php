<?php

use Illuminate\Support\Facades\Route;
use Modules\Tags\Http\Controllers\TagsController;

Route::middleware(['auth', 'verified'])->group(function () {
    Route::resource('tags', TagsController::class)->names('tags');
});
