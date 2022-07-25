<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusCourseLearningTopicMaterials extends Model
{
    use HasFactory;
    protected $fillable = ['topic_id', 'presentation_link', 'youtube_link'];
}
