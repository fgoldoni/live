<?php

use Core\Traits\Database\DisableForeignKeys;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use \Core\Traits\Database\Migration;
    use DisableForeignKeys;
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->ulid()->unique();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $this->addAvatar($table);
            $table->string('phone')->nullable()->nullable()->unique();
            $table->timestamp('email_verified_at')->nullable()->index();
            $table->timestamp('phone_verified_at')->nullable()->index();
            $table->string('password')->nullable();
            $table->string('birth_date')->nullable();
            $table->rememberToken();
            $table->timestamp('archived_at')->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token')->index();
            $table->timestamp('created_at')->nullable()->index();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable()->index();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $this->disableForeignKeys();
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
        $this->enableForeignKeys();
    }
};
