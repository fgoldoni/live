<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Categories\Http\Controllers\CategoriesController;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::resource('categories', CategoriesController::class)->names('categories');
});
