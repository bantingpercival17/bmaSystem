<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExaminationCategory extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
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
        return $this->hasMany(ExaminationQuestion::class, 'category_id')->where('is_removed', false);
    }
    function question_lists()
    {
        return $this->hasMany(ExaminationQuestion::class, 'category_id')->select('id', 'category_id', 'question', 'image_path')->with('choices_v2')->where('is_removed', false);
    }
    function question_list_with_answer()
    {
        return $this->hasMany(ExaminationQuestion::class, 'category_id')->select('id', 'category_id', 'question', 'image_path')->with('choices_v2')->where('is_removed', false);
    }
}
