<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Artisan::call('model-permissions:sync', ['--with-roles' => true, '--reset' => true]);

        User::factory()->withTeam()->asSuperAdmin()->create([
            'name' => 'Test User',
            'email' => 'admin@admin.com',
            'phone' => '+4917647159315',
        ]);

        User::factory(3)->withTeam()->asManager()->create();
        User::factory(3)->withTeam()->asSeller()->create();
        User::factory(3)->withTeam()->asUser()->create();
    }
}
