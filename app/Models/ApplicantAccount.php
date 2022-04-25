<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class ApplicantAccount extends  Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $connection = 'mysql2';
    protected $fillable = [
        'name',
        'email',
        'password',
        'applicant_number',
        'course_id',
        'academic_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function applicant()
    {
        return $this->hasOne(ApplicantDetials::class, 'applicant_id');
    }
    public function course()
    {
        return $this->belongsTo(CourseOffer::class, 'course_id');
    }
    public function academic()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_id');
    }
    public function applicant_documents()
    {
        return $this->hasMany(ApplicantDocuments::class, 'applicant_id')->where('is_removed', false)->orderBy('document_id');
    }
    public function empty_documents()
    {
        $_level = $this->course_id == 3 ? 11 : 4;
        return Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->get();
    }
    public function document_status()
    {
        return $this->hasMany(ApplicantDocuments::class, 'applicant_id')->where('is_approved', 1)->where('is_removed', false);
    }
    public function document_history($_data)
    {
        return $this->hasMany(ApplicantDocuments::class, 'applicant_id')->where('document_id', $_data)->where('is_removed', true);
    }

    public function payments()
    {
        return $this->hasMany(ApplicantPayment::class, 'applicant_id')->where('is_removed', false);
    }
    public function search_applicants($_data)
    {
        //return ApplicantAccount::where('name', 'like', '%' . $_data . '%')->with('applicant_details')->get();
        return $this->where('name', 'like', '%' . $_data . '%')->orWhere('applicant_number', 'like', '%' . $_data . '%')->get();
    }
    public function applicant_payments()
    {
        return $this->select('applicant_accounts.*')->join('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id')->whereNull('is_approved')->where('applicant_payments.is_removed', false)->get();
    }
    public function applicant_examination()
    {
        return $this->hasOne(ApplicantEntranceExamination::class, 'applicant_id')->where('is_removed', false)->where('is_finish', true);
    }
}
