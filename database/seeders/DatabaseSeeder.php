<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PositionSeeder::class,
            DivisionSeeder::class,
            WorkUnitSeeder::class,
            EmployeeSeeder::class,
            RolePermissionSeeder::class,
            AtkItemSeeder::class,
            SupplierSeeder::class,
        ]);
    }
}
