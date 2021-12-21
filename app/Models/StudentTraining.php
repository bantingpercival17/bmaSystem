<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentTraining extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id', 'training_id', 'certificate_number', 'staff_id', 'is_active', 'is_removed'
    ];

    public function training_certificate()
    {
        return $this->belongsTo(TrainingCertificate::class, 'training_id');
    }
    public function student_training()
    {
        return $this->belongsTo(StudentDetails::class, 'student_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
}
