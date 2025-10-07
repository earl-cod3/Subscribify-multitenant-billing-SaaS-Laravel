<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there's a unique index on roles.name (recommended)
        // If you don't already have one, add a migration later.

        $now = now();

        // Use updateOrInsert keyed by 'name' so re-running the seeder won't fail.
        DB::table('roles')->updateOrInsert(
            ['name' => 'Admin'],
            ['description' => 'Admin user has full access', 'updated_at' => $now, 'created_at' => $now]
        );

        DB::table('roles')->updateOrInsert(
            ['name' => 'Creator'],
            ['description' => 'Creator user can add new users', 'updated_at' => $now, 'created_at' => $now]
        );

        DB::table('roles')->updateOrInsert(
            ['name' => 'Member'],
            ['description' => 'Member user has minimal access', 'updated_at' => $now, 'created_at' => $now]
        );
    }
}
