<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Modules\Events\Http\Controllers\EventsController;

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::resource('events', EventsController::class)->names('events');
});
