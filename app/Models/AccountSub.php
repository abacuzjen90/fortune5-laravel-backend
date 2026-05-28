<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountSub extends Model
{
    use HasFactory;

    protected $table = 'sys_accountsub';

    protected $fillable = [
        'haccountid',
        'subtitle',
        'subsequenceno'
    ];

    // Relationship to account header if needed
    public function accountHeader()
    {
        return $this->belongsTo(AccountHeader::class, 'haccountid');
    }
}