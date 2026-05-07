<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            'company_name'        => 'Al-Ghani Trading',
            'company_address'     => '',
            'company_phone'       => '',
            'currency_symbol'     => 'Rs',
            // Fiscal year — month (1-12) that the FY starts in. July = 7, January = 1.
            'fiscal_start_month'  => '7',
            // Required-account settings (referenced during posting; null until owner picks an account)
            'sales_account_id'    => null,
            'purchase_account_id' => null,
            'cash_account_id'     => null,
            'bank_account_id'     => null,
        ];

        foreach ($defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
