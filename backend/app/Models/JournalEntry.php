<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model
{
    protected $fillable = ['date', 'description', 'reference_type', 'reference_id', 'created_by'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function lines()     { return $this->hasMany(JournalEntryLine::class); }
    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }
}
