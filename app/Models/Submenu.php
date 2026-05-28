<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submenu extends Model
{
    use HasFactory;

    protected $table = 'sys_submenu';

    protected $fillable = [
        'menu_id',
        'submenu_name',
        'secondlevel',
        'path_direction',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
