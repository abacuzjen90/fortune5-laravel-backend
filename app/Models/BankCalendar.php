<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankCalendar extends Model
{
    use HasFactory;

    protected $table = 'bankcalendar';

    protected $fillable = [
        'replace_id',
        'bank',
        'checkno',
        'payee',
        'amount',
        'status',
        'date',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
