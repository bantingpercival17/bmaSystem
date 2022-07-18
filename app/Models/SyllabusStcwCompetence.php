<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusStcwCompetence extends Model
{
    use HasFactory;
    protected $fillable = [
        'stcw_function_id',
        'competence_content'
    ];
    public function kup_content()
    {
        return $this->hasMany(SyllabusStcwKup::class, 'stcw_competence_id')->where('is_removed', false);
    }
}
