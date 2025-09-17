<?php

use App\Http\Controllers\WhatsAppWebhookController;
use Illuminate\Support\Facades\Route;


Route::webhooks('webhooks/whatsapp', 'whatsapp');
Route::get('webhooks/whatsapp', WhatsAppWebhookController::class);
