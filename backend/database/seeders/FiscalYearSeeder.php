<?php

namespace Database\Seeders;

use App\Models\FiscalYear;
use Illuminate\Database\Seeder;

class FiscalYearSeeder extends Seeder
{
    public function run(): void
    {
        $year = now()->year;
        FiscalYear::firstOrCreate(
            ['name' => "FY {$year}"],
            [
                'start_date' => "{$year}-01-01",
                'end_date'   => "{$year}-12-31",
                'is_active'  => true,
            ]
        );
    }
}
