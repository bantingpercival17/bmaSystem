<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentRequirements extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'document_id',
        'file_path',
        'document_path',
        'document_status',
        'deployment_id'
    ];
    public function student()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
    public function documents()
    {
        return $this->belongsTo(Documents::class, 'document_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
