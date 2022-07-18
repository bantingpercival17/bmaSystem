<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusStcwReference extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_syllabus_id',
        'stcw_table'
    ];

    public function function_content()
    {
        return $this->hasMany(SyllabusStcwFunction::class, 'stcw_reference_id')->where('is_removed', 0);
    }
}
