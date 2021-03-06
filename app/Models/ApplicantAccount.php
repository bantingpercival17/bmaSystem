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
    public function applicant_documents_status()
    {
        $_level = $this->course_id == 3 ? 11 : 4;
        $_document_count =  Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->orderBy('id')->count();
        $_document_approved = $this->hasMany(ApplicantDocuments::class, 'applicant_id')->where('is_removed', false)->where('is_approved', true)->limit($_document_count)->get();
        return $_document_count == count($_document_approved) ? true : false;
    }
    public function is_alumnia()
    {
        return $this->hasOne(ApplicantAlumnia::class, 'applicant_id')->where('is_removed', false);
    }
    public function empty_documents()
    {
        $_level = $this->course_id == 3 ? 11 : 4;
        return Documents::where('department_id', 2)->where('year_level', $_level)->where('is_removed', false)->orderBy('id')->get();
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
    public function search_applicants()
    {
        //return ApplicantAccount::where('name', 'like', '%' . $_data . '%')->with('applicant_details')->get();
        return $this->where('name', 'like', '%' . request()->input('_applicants') . '%')->orWhere('applicant_number', 'like', '%' . request()->input('_applicants') . '%')->get();
    }
    public function applicant_payments()
    {
        return $this->select('applicant_accounts.*')->join('applicant_payments', 'applicant_payments.applicant_id', 'applicant_accounts.id')->whereNull('is_approved')->where('applicant_payments.is_removed', false)->get();
    }
    public function payment()
    {
        return $this->hasOne(ApplicantPayment::class, 'applicant_id')->where('is_removed', false);
    }
    public function applicant_examination()
    {
        return $this->hasOne(ApplicantEntranceExamination::class, 'applicant_id')->where('is_removed', false)/* ->where('is_finish', true) */;
    }
    public function examination()
    {
        return $this->hasOne(ApplicantEntranceExamination::class, 'applicant_id')->where('is_finish', true);
    }
    public function examination_list()
    {
        return $this->hasMany(ApplicantEntranceExamination::class, 'applicant_id');
    }
    public function image()
    {
        $_level = $this->course_id == 3 ? 11 : 4;
        $_document = Documents::where('department_id', 2)->where('year_level', $_level)->where('document_name', '2x2 Picture')->where('is_removed', false)->first();
        return $this->hasOne(ApplicantDocuments::class, 'applicant_id')->where('document_id', $_document->id)->where('is_removed', false);
    }
    public function virtual_orientation()
    {
        return $this->hasOne(ApplicantBriefing::class, 'applicant_id')->where('is_removed', false);
    }
    public function medical_appointment()
    {
        return $this->hasOne(ApplicantMedicalAppointment::class, 'applicant_id')->where('is_removed', false);
    }
    public function similar_account()
    {
        $_details = $this->applicant;
        $_applicant = ApplicantAccount::select('applicant_accounts.*')->join('applicant_detials', 'applicant_accounts.id', 'applicant_detials.applicant_id')
            /* ->join('applicant_documents as sd', 'sd.applicant_id', 'applicant_accounts.id') */
            ->where('applicant_detials.first_name', $_details->first_name)
            ->where('applicant_detials.last_name', $_details->last_name)
            ->where('applicant_detials.middle_name', $_details->middle_name)
            ->where('applicant_accounts.id', '!=', $this->id)
            ->where('applicant_accounts.is_removed', false)->first();
        return $_applicant;
    }
    public function medical_result()
    {
        return $this->hasOne(ApplicantMedicalResult::class, 'applicant_id')->where('is_removed', false);
    }
    public function senior_high_school()
    {
        $_school =  trim($this->applicant->senior_high_school_name);
        return strtolower($_school) == strtolower('baliuag maritime academy') ||strtolower($_school) == strtolower('baliwag maritime academy inc.') || strtolower($_school) == strtolower('baliwag maritime academy') || strtolower($_school) == strtolower('baliwag maritime academy inc') || strtolower($_school) == strtolower('baliwag martime academy') ? 1 : 0;
    }
}
