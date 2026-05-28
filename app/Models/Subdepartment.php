<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subdepartment extends Model
{
    use HasFactory;

    protected $table = 'emp_department_sub';

    protected $fillable = [
        'department_header_id',
        'department_sub',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
