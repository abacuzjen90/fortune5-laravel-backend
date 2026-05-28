<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountHeader extends Model
{
    use HasFactory;

    protected $table = 'sys_accountheader';
    protected $fillable = ['description', 'hsno'];
}
