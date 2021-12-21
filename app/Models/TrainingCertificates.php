<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCertificates extends Model
{
    use HasFactory;
    protected $fillable = [
        'training_name', 'training_details', 'section', 'is_removed'
    ];
    public function student_training($_data)
    {
        return StudentTraining::where('student_id', $_data[0])
            ->where('training_id', $_data[1])
            ->where('is_active', 1)
            ->first();
    }
}
