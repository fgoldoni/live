<?php

use Core\Traits\Database\DisableForeignKeys;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use DisableForeignKeys;

    public function up(): void
    {
        $driver = DB::getDriverName();

        Schema::create('passwordless_tokens', function (Blueprint $table) use ($driver) {
            $table->id();
            $table->ulid()->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('token', 255);
            $table->timestamp('expires_at')->index();
            $table->timestamp('used_at')->nullable();
            if ($driver === 'pgsql') {
                $table->jsonb('metadata')->nullable();
            } else {
                $table->json('metadata')->nullable();
            }
            $table->timestamps();
            $table->index(['user_id', 'used_at']);
        });
    }

    public function down(): void
    {
        $this->disableForeignKeys();
        Schema::dropIfExists('passwordless_tokens');
        $this->enableForeignKeys();
    }
};
