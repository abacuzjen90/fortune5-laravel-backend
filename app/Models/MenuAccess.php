<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuAccess extends Model
{
    use HasFactory;

    protected $table = 'emp_menu_access';

    protected $fillable = [
        'employee_id',
        'menu_id',
        'submenu_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
