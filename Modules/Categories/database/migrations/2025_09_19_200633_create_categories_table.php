<?php

declare(strict_types=1);

use Core\Traits\Database\DisableForeignKeys;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use \Core\Traits\Database\Migration;
    use DisableForeignKeys;

    public function up(): void
    {
        $this->disableForeignKeys();

        Schema::create('categories', function (Blueprint $blueprint): void {
            $blueprint->id();
            $this->addAvatar($blueprint);
            $blueprint->text('name');
            $blueprint->text('description')->nullable();
            $this->addSlug($blueprint);
            $blueprint->boolean('online')->default(false);
            $blueprint->string('model')->nullable();
            $blueprint->foreignId('parent_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $blueprint->timestamp('archived_at')->nullable()->index();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });

        if (Schema::hasTable('events') && !Schema::hasColumn('events', 'category_id')) {
            Schema::table('events', function (Blueprint $blueprint): void {
                $blueprint->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            });
        }

        $this->enableForeignKeys();
    }

    public function down(): void
    {
        $this->disableForeignKeys();

        if (Schema::hasTable('events') && Schema::hasColumn('events', 'category_id')) {
            Schema::table('events', function (Blueprint $blueprint): void {
                $blueprint->dropForeign(['category_id']);
                $blueprint->dropColumn('category_id');
            });
        }

        Schema::dropIfExists('categories');

        $this->enableForeignKeys();
    }
};
