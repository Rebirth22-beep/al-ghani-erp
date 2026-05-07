<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            FiscalYearSeeder::class,
            ChartOfAccountSeeder::class,
            SettingSeeder::class,
        ]);
    }
}
