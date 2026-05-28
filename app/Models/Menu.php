<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'sys_menu';

    protected $fillable = [
        'menu_name',
        'secondlevel',
        'path_direction',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
