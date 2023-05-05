<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipboardJournal extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'shipboard_id',
        'month',
        'remark',
        'file_links',
        'journal_type',
        'is_removed',
    ];

    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
