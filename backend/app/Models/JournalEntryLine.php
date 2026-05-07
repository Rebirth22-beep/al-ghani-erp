<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntryLine extends Model
{
    protected $fillable = ['journal_entry_id', 'chart_of_account_id', 'debit_paisas', 'credit_paisas', 'description'];

    protected function casts(): array
    {
        return ['debit_paisas' => 'integer', 'credit_paisas' => 'integer'];
    }

    public function entry()          { return $this->belongsTo(JournalEntry::class, 'journal_entry_id'); }
    public function chartOfAccount() { return $this->belongsTo(ChartOfAccount::class); }
}
