<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->asSuperAdmin()->create([
            'name'  => 'Test User',
            'email' => 'admin@admin.com',
            'phone' => '+4917647159315',
        ]);

        User::factory(10)->asUser()->create();
    }
}
