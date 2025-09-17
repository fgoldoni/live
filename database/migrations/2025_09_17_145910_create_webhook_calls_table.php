<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use \Core\Traits\Database\Migration;
    use \Core\Traits\Database\DisableForeignKeys;

    public function up()
    {
        Schema::create('webhook_calls', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name');
            $table->string('url', 512);
            $table->json('headers')->nullable();
            $table->json('payload')->nullable();
            $table->text('exception')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        $this->disableForeignKeys();
        Schema::dropIfExists('webhook_calls');
        $this->enableForeignKeys();
    }
};
