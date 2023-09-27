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
        return $this->hasOne(ApplicantDocuments::class, 'document_id')
            ->where('applicant_id', base64_decode(request()->input('_applicant')))->where('is_removed', false);
    }
    public function applicant_requirements_v2()
    {
        return $this->hasOne(ApplicantDocuments::class, 'document_id')->where('is_removed', false);
    }
    public function student_document_requirement()
    {
        return $this->hasOne(DocumentRequirements::class, 'document_id')->where('student_id', base64_decode(request()->input('_midshipman')))->where('is_removed', false);
    }
    public function student_upload_documents()
    {
        return $this->hasOne(DocumentRequirements::class, 'document_id')->where('student_id', auth()->user()->student_id)->where('is_removed', false)->with('staff');
    }
}
