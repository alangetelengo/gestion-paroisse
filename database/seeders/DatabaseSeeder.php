<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            ParoisseSeeder::class,
            RolePermissionSeeder::class,
            AdminUserSeeder::class,
            MemberSeeder::class,
            ClergySeeder::class,
            EventSeeder::class,
            RevenueCategorySeeder::class,
            RevenueTypeSeeder::class,
            // RevenueSeeder::class,
            // ExpenseSeeder::class,
        ]);
    }
}
