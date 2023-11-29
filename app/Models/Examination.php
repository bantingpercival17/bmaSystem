<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = [
        'examination_name',
        'description',
        'department',
        'due_date',
        'exp_date',
    ];
    public function categories()
    {
        return $this->hasMany(ExaminationCategory::class, 'examination_id');
    }
    public function category_lists()
    {
        return $this->hasMany(ExaminationCategory::class, 'examination_id')->select('id','examination_id','category_name','subject_name','instruction','image_path')->with('question_lists')->where('is_removed', false);
    }
}
