<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountTitle extends Model
{
    use HasFactory;

    protected $table = 'sys_accounttitle';
    protected $fillable = [
        'haccountid',
        'saccountid',
        'title',
        'description',
        'tsequenceno',
        'chartno'
    ];

    // Relationship to AccountHeader (assuming you have an AccountHeader model)
    public function header()
    {
        return $this->belongsTo(AccountHeader::class, 'haccountid');
    }

    // Relationship to AccountSub
    public function subClass()
    {
        return $this->belongsTo(AccountSub::class, 'saccountid');
    }
}