<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = DB::getDriverName();

        Schema::create('whats_app_messages', function (Blueprint $table) use ($driver) {
            $table->id();
            $table->ulid()->unique();
            $table->string('wamid')->unique();
            $table->string('recipientId')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('statusAt')->nullable();
            $table->string('phoneNumberId')->nullable();
            $table->string('displayPhoneNumber')->nullable();
            $table->string('conversationId')->nullable();
            $table->string('conversationOrigin')->nullable();
            $table->string('category')->nullable();
            $table->boolean('billable')->nullable();
            $table->string('pricingModel')->nullable();
            $table->timestamp('sentAt')->nullable();
            $table->timestamp('deliveredAt')->nullable();
            $table->timestamp('readAt')->nullable();
            $table->timestamp('failedAt')->nullable();
            if ($driver === 'pgsql') {
                $table->jsonb('raw')->nullable();
            } else {
                $table->json('raw')->nullable();
            }
            $table->timestamps();
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whats_app_messages');
    }
};
