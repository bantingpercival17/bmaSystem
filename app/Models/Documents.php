<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Documents extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = [
        "department_id",
        "document_name",
        "document_code",
        "document_details",
        "document_propose",
        "year_level",
        "is_removed"
    ];
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function applicant_document()
    {
        return $this->belongsTo(ApplicantDocuments::class, 'document_id');
        //->where('applicant_id', base64_decode(request()->input('_student')))->where('is_removed', false);
    }
}
