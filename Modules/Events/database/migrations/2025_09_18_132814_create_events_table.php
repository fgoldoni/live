<?php

declare(strict_types=1);

use Core\Traits\Database\DisableForeignKeys;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Categories\Models\Category;

return new class extends Migration {
    use \Core\Traits\Database\Migration;
    use DisableForeignKeys;

    public function up(): void
    {
        Schema::create('events', function (Blueprint $blueprint): void {
            $blueprint->id();
            $this->addAvatar($blueprint);
            $blueprint->text('name');
            $driver = DB::getDriverName();
            $blueprint->string('slug')->index()->nullable();
            if ($driver === 'pgsql') {
                $blueprint->jsonb('description')->nullable();
                $blueprint->jsonb('phones')->nullable();
                $blueprint->jsonb('languages')->nullable();
            } else {
                $blueprint->json('description')->nullable();
                $blueprint->json('phones')->nullable();
                $blueprint->json('languages')->nullable();
            }
            $blueprint->longText('content')->nullable();
            $blueprint->string('address')->nullable();
            $blueprint->string('dress_code', 32)->nullable();
            $blueprint->unsignedInteger('whatsapp_limit_per_client')->default(100);
            $blueprint->unsignedInteger('sms_limit_per_client')->default(100);
            $blueprint->boolean('online')->default(true);
            $blueprint->decimal('latitude', 10, 7)->nullable()->index();
            $blueprint->decimal('longitude', 10, 7)->nullable()->index();
            $this->addUserField($blueprint);
            $blueprint->foreignId('manager_id')->nullable()->constrained('users')->nullOnDelete();
            $blueprint->unsignedBigInteger('views')->default(0);
            $blueprint->unsignedBigInteger('love_count')->default(0);
            $blueprint->unsignedBigInteger('love_count_pending')->default(0);
            $blueprint->decimal('price', 10)->default(0);
            $this->addTeamField($blueprint);
            $this->addLocationField($blueprint);
            $this->addSeoFields($blueprint);
            $blueprint->timestamp('archived_at')->nullable()->index();
            $blueprint->softDeletes();
            $blueprint->timestamps();
        });

        if (Schema::hasTable('categories') && !Schema::hasColumn('events', 'category_id')) {
            Schema::table('events', function (Blueprint $blueprint): void {
                $blueprint->foreignIdFor(Category::class)->nullable()->constrained()->cascadeOnDelete();
            });
        }

        if (Schema::hasTable('teams') && !Schema::hasColumn('teams', 'event_id')) {
            Schema::table('teams', function (Blueprint $blueprint): void {
                $blueprint->foreignId('event_id')->nullable()->index()->after('owner_id')->references('id')->on('events')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        $this->disableForeignKeys();

        if (Schema::hasTable('teams') && Schema::hasColumn('teams', 'event_id')) {
            Schema::table('teams', function (Blueprint $blueprint): void {
                $blueprint->dropForeign(['event_id']);
                $blueprint->dropColumn('event_id');
            });
        }

        Schema::dropIfExists('events');
        $this->enableForeignKeys();
    }
};
