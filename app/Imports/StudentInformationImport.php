<?php

namespace App\Imports;

use App\Models\EnrollmentAssessment;
use App\Models\ShipBoardInformation;
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
                    'last_name' => ucwords(mb_strtolower(trim($_data[2]))),
                    'first_name' => ucwords(mb_strtolower(trim($_data[1]))),
                ])->first(); // GET THE STUDENT DETIALIES
                echo  ucwords(mb_strtolower(trim($_data[1] . ', ' . $_data[2]))) . " " . $_data[0] .  " <br>";
                //CHECK IF THE STUDENT IS EXSITING
                if ($_student) {
                    // Account Update
                    $_account = StudentAccount::where('student_number', $_data[0])->first();
                    if ($_account) {
                        $this->update_account($_account, $_data, $_student);
                        echo "Updated Account <br>";
                    } else {
                        $this->create_account($_student, $_data);
                        echo "Saved Account <br>";
                    }
                } else {
                    //Create New Student
                    $_student = $this->create_student($_data);
                    echo "Saved New Student | ";
                    $_account = $this->create_account($_student, $_data);
                    echo "Saved New Account <br>";
                    $_enrollment = array(
                        "student_id" => $_student->id,
                        "academic_id" => $_data[5],
                        "course_id" => $_data[4],
                        "year_level" => 2,
                        'staff_id' => 1,
                        "curriculum_id" => 1,
                        'is_removed' => false
                    );
                    EnrollmentAssessment::create($_enrollment);
                    echo "Saved Enrollment. <br>";
                }
                echo "Complete Process <br>";
                echo "<br>";
            }
        }
    }

    public function old_algo(Collection $collection)
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
                        StudentAccount::create($_details);
                    }
                }
            }
        }
    }

    public function student_account($collection)
    {
        foreach ($collection as $key => $_data) {
            if ($key > 0 && !empty($_data[0])) {
                $_account = StudentAccount::where('student_number', $_data[0])->first();
                if ($_account) {
                    // Existing 
                    echo  ucwords(mb_strtolower(trim($_data[1] . ', ' . $_data[2]))) . ": Saved.<br>";
                    echo $_account->campus_email . "<br>";
                    // What next's?
                } else {
                    // Missing Email 
                    echo  ucwords(mb_strtolower(trim($_data[1] . ', ' . $_data[2]))) . "<br>";
                    // Find Student Name
                    $_student = StudentDetails::where([
                        'last_name' => ucwords(mb_strtolower(trim($_data[2]))),
                        'first_name' => ucwords(mb_strtolower(trim($_data[1]))),
                    ])->first();
                    if ($_student) {
                        $this->create_account($_student, $_data);
                        echo "Saved Email. <br>";
                    } else {
                        $_create = $this->create_student($_data);
                        echo "Create Student Done <br>";
                        $this->create_account($_create, $_data);
                        echo "Saved Email. <br>";
                        $_enrollment = array(
                            "student_id" => $_create->id,
                            "academic_id" => $_data[5],
                            "course_id" => $_data[4],
                            "year_level" => 2,
                            'staff_id' => 1,
                            "curriculum_id" => 1,
                        );
                        EnrollmentAssessment::create($_enrollment);
                        echo "Saved Enrollment. <br>";
                    }
                }
                echo "Complete Process <br>";
                echo "<br>";
            }
        }
    }

    public function create_student($_data)
    {
        $_details = array(
            'last_name' => ucwords(mb_strtolower(trim($_data[2]))),
            'first_name' => ucwords(mb_strtolower(trim($_data[1]))),
            'middle_name' => ucwords(mb_strtolower(trim($_data[3]))),
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
        return StudentDetails::create($_details);
    }
    public function create_account($_student, $_data)
    {
        try {
            $_details = array(
                'student_id' => $_student->id,
                'campus_email' => $_data[0] . "." . mb_strtolower(trim(str_replace(' ', '', $_data[2]))) . "@bma.edu.ph",
                'personal_email' => $_data[0] . "." . mb_strtolower(trim(str_replace(' ', '', $_data[2]))) . "@bma.edu.ph",
                'student_number' => $_data[0],
                'password' => Hash::make($_data[0]),
                'is_active' => true,
                'is_removed' => false
            );
            StudentAccount::create($_details);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function update_account($_account, $_data, $_student)
    {
        $_account->student_number = $_data[0];
        $_account->student_id = $_student->id;
        $_account->campus_email = $_data[0] . "." . mb_strtolower(trim(str_replace(' ', '', $_data[2]))) . "@bma.edu.ph";
        $_account->save();
    }
    public function create_enrollment($_student, $_data)
    {
        # code...
    }
}
