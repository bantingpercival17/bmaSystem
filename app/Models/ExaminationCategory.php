<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'examination_id',
        'subject_name',
        'category_name',
        'instruction',
        'image_path',
    ];
    public function examination()
    {
        return $this->belongsTo(Examination::class, 'examination_id');
    }
    public function question()
    {
        return $this->hasMany(ExaminationQuestion::class, 'category_id');
    }
}
