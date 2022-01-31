<?php

namespace App\Imports;

use App\Models\EnrollmentAssessment;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentInformationImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $_data) {
            if ($key > 0 && !empty($_data[0])) {
                $_student = StudentDetails::where([
                    'last_name' => ucwords(mb_strtolower(trim($_data[3]))),
                    'first_name' => ucwords(mb_strtolower(trim($_data[4]))),
                ])->first();
                echo  ucwords(mb_strtolower(trim($_data[3] . ', ' . $_data[4] . " " . $_data[5]))) . "<br>";
                if (!$_student) {
                    $_details = array(
                        'last_name' => ucwords(mb_strtolower(trim($_data[3]))),
                        'first_name' => ucwords(mb_strtolower(trim($_data[4]))),
                        'middle_name' => ucwords(mb_strtolower(trim($_data[5]))),
                        'extenttion_name' => '',
                        'birthday' => now(),
                        'birth_place' => '',
                        'sex' => 'Male',
                        'nationality' => '',
                        'civil_status' => '',
                        'street' => '',
                        'barangay' => '',
                        'municipality' => '',
                        'province' => '',
                        'religion' => '',
                        'zip_code' => '',
                        'is_removed' => 0,
                    );
                    $_student = StudentDetails::create($_details);
                    $_enrollment = array(
                        "student_id" => $_student->id,
                        "academic_id" => 2,
                        "course_id" => $_data[6] == "BSMARE" ? 1 : 2,
                        "year_level" => 2,
                        'staff_id' => 1,
                        "curriculum_id" => 1,
                    );
                    EnrollmentAssessment::create($_enrollment);
                    $_details = array(
                        'student_id' => $_student->id,
                        'campus_email' => $_data[2] . "." . mb_strtolower(trim(str_replace(' ', '', $_data[3]))) . "@bma.edu.ph",
                        'personal_email' => $_data[1],
                        'student_number' => $_data[2],
                        'password' => Hash::make($_data[2]),
                        'is_active' => true,
                        'is_removed' => false
                    );
                    StudentAccount::create($_details);
                    echo var_dump($_details) . "<br>";
                } else {
                    echo "Email: :" . ($_student->account ? $_student->account->campus_email : " Not Saved") . "<br>";
                    if (!$_student->account) {
                        $_details = array(
                            'student_id' => $_student->id,
                            'campus_email' => $_data[2] . "." . mb_strtolower(trim(str_replace(' ', '', $_data[3]))) . "@bma.edu.ph",
                            'personal_email' => $_data[1],
                            'student_number' => $_data[2],
                            'password' => Hash::make($_data[2]),
                            'is_active' => true,
                            'is_removed' => false
                        );
                        //echo var_dump($_details);
                        StudentAccount::create($_details);
                    }
                }
            }
        }
    }
}
