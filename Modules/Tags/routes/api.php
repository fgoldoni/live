<?php

use Illuminate\Support\Facades\Route;
use Modules\Tags\Http\Controllers\TagsController;

Route::middleware(['auth:sanctum'])->prefix('v1')->group(function () {
    Route::apiResource('tags', TagsController::class)->names('tags');
});
