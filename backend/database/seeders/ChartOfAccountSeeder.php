<?php

namespace Database\Seeders;

use App\Models\ChartOfAccount;
use Illuminate\Database\Seeder;

class ChartOfAccountSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            // Assets
            ['code' => '1000', 'name' => 'Cash',             'account_type' => 'asset',    'is_system' => true],
            ['code' => '1100', 'name' => 'Bank',             'account_type' => 'asset',    'is_system' => true],
            ['code' => '1200', 'name' => 'Accounts Receivable','account_type'=> 'asset',   'is_system' => true],
            ['code' => '1300', 'name' => 'Inventory',        'account_type' => 'asset',    'is_system' => true],
            // Liabilities
            ['code' => '2000', 'name' => 'Accounts Payable', 'account_type' => 'liability','is_system' => true],
            // Equity
            ['code' => '3000', 'name' => 'Capital',          'account_type' => 'equity',   'is_system' => true],
            ['code' => '3100', 'name' => 'Retained Earnings','account_type' => 'equity',   'is_system' => true],
            // Revenue
            ['code' => '4000', 'name' => 'Sales Revenue',    'account_type' => 'revenue',  'is_system' => true],
            ['code' => '4100', 'name' => 'Purchase Returns Revenue', 'account_type' => 'revenue', 'is_system' => true],
            // Expenses
            ['code' => '5000', 'name' => 'Cost of Goods Sold','account_type'=> 'expense',  'is_system' => true],
            ['code' => '5100', 'name' => 'Worker Wages',     'account_type' => 'expense',  'is_system' => true],
            ['code' => '5200', 'name' => 'Operating Expenses','account_type'=> 'expense',  'is_system' => true],
        ];

        foreach ($accounts as $account) {
            ChartOfAccount::firstOrCreate(['code' => $account['code']], $account);
        }
    }
}
