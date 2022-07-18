<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyllabusStcwFunction extends Model
{
    use HasFactory;
    protected $fillable = [
        'stcw_reference_id',
        'function_content'
    ];
    public function competence_content()
    {
        return $this->hasMany(SyllabusStcwCompetence::class, 'stcw_function_id')->where('is_removed',false);
    }
}
