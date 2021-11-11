<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParentDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        "student_id",
        "father_last_name",
        "father_first_name",
        "father_middle_name",
        "father_educational_attainment",
        "father_employment_status",
        "father_working_arrangement",
        "father_contact_number",
        "mother_last_name",
        "mother_first_name",
        "mother_middle_name",
        "mother_educational_attainment",
        "mother_employment_status",
        "mother_working_arrangement",
        "mother_contact_number",
        "guardian_last_name",
        "guardian_first_name",
        "guardian_middle_name",
        "guardian_educational_attainment",
        "guardian_employment_status",
        "guardian_working_arrangement",
        "guardian_contact_number",
        "household_income",
        "dswd_listahan",
        "homeownership",
        "car_ownership",
        "available_devices",
        "available_connection",
        "available_provider",
        "learning_modality",
        "distance_learning_effect",
        "is_removed"
    ];
}
