<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use \Core\Traits\Database\Migration;
    use \Core\Traits\Database\DisableForeignKeys;
    public function up(): void
    {
        $driver = DB::getDriverName();

        Schema::create('one_time_passwords', function (Blueprint $table) use ($driver) {
            $table->id();
            $table->ulid()->unique();

            $table->string('password');
            if ($driver === 'pgsql') {
                $table->jsonb('origin_properties')->nullable();
            } else {
                $table->json('origin_properties')->nullable();
            }

            $table->dateTime('expires_at');
            $table->morphs('authenticatable');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->disableForeignKeys();
        Schema::dropIfExists('one_time_passwords');
        $this->enableForeignKeys();
    }
};
