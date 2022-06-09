<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ApplicantDetials extends Model
{
    use HasFactory;
    protected $connection = 'mysql2';
    protected $fillable = [
        "applicant_id",
        "first_name",
        "last_name",
        "middle_name",
        "extention_name",
        "birthday",
        "birth_place",
        "sex",
        "nationality",
        "religion",
        "civil_status",
        "street",
        "barangay",
        "municipality",
        "province",
        "zip_code",
        "is_removed"
    ];
    public function account()
    {
        return $this->belongsTo(ApplicantAccount::class, 'applicant_id');
    }
    public function check_duplicate()
    {
        $_duplication = ApplicantDetials::join('applicant_accounts', 'applicant_accounts.id', 'applicant_detials.applicant_id')
            ->where('applicant_detials.first_name', $this->first_name)
            ->where('applicant_detials.last_name', $this->last_name)
            ->where('applicant_detials.middle_name', $this->middle_name)
            ->where('applicant_accounts.is_removed', false)
            ->get();
        $_applicant = ApplicantDetials::join('applicant_accounts', 'applicant_accounts.id', 'applicant_detials.applicant_id')
        ->where('applicant_detials.first_name', $this->first_name)
            ->where('applicant_detials.last_name', $this->last_name)
            ->where('applicant_detials.middle_name', $this->middle_name)
            ->where('applicant_accounts.is_removed', false)->first();
        $_message = /* $_applicant->account->applicant_number === $this->account->applicant_number ? 'ORIGINAL APPLICANT' :  */'DUPLICATE FOUND ON ' . $_applicant->account->applicant_number;
        return count($_duplication) > 1 ? $_message : 'NO DUPLICATE DETECTED';
    }
}
