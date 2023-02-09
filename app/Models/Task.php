<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable =  [
        'staff_id',
        'task',
        'status',
        'task_approved'
    ];
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
