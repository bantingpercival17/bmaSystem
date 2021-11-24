<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class StudentDetails extends Model
{
    use HasFactory;
    protected $fillable = [
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


    public function enrollment_assessment()
    {
        return $this->hasOne(EnrollmentAssessment::class, 'student_id')->where('is_removed', 0)->orderBy('id', 'desc');
    }
    public function account()
    {
        return $this->hasOne(StudentAccount::class, 'student_id');
    }
    public function section($_academic)
    {
        return $this->hasOne(StudentSection::class, 'student_id')->select('student_sections.id', 'student_sections.student_id', 'student_sections.section_id')
            ->join('sections', 'sections.id', 'student_sections.section_id')->where('sections.academic_id', $_academic)->where('student_sections.is_removed',false);
    }

    /* Grading Query */
    public function subject_score($_data)
    {
        $_score =  $this->hasOne(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_data[0])
            ->where('period', $_data[1])
            ->where('is_removed', false)
            ->where('type', $_data[2])->first();
        return $_score ? $_score->score : '';
    }
    public function subject_average_score($_data)
    {
        $_percent = $_data['2'] == 'Q' || $_data['2'] == 'O' || $_data['2'] == 'R'  ? .15 : .55;
        return $this->hasMany(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_data[0])
            ->where('period', $_data[1])
            ->where('is_removed', false)
            ->where('type', 'like',  $_data[2] . "%")->average('score') * $_percent;
    }
    function lec_grade($_data)
    {
        $_tScore = 0;
        $_category = [['Q', 15], ['O', 15], ['R', 15], [$_data[1][0] . 'E', 55]];
        $_count = 0;
        foreach ($_category as $key => $_categ) {
            $_score = $this->hasMany(GradeEncode::class, 'student_id')
                ->where('subject_class_id', $_data[0])
                ->where('period', $_data[1])
                ->where('is_removed', false)
                ->where('type', 'like',  $_categ[0] . "%")->average('score');
            $_count +=   $_score > 0 ? 1 : 0;
            $_tScore += $_score * ($_categ[1] / 100);
        }
        return 4 == $_count ? $_tScore * .4 : 0;
    }
    public function lab_grade($_data)
    {
        return $this->hasMany(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_data[0])
            ->where('period', $_data[1])
            ->where('type', 'like',  "A%")
            ->where('is_removed', false)->average('score') * .60;
    }
    public function final_grade($_data, $_period)
    {
        $_lec_grade_midterm = $this->lec_grade([$_data, 'midterm']);
        $_lab_grade_midterm = $this->lab_grade([$_data, 'midterm']);;
        $_lec_grade_final = $this->lec_grade([$_data, 'finals']);
        $_lab_grade_final = $this->lab_grade([$_data, 'finals']);
        $_total_final_grade = ($_lec_grade_final + $_lab_grade_final) * .5;



        return  $_period == 'midterm' ?
            // MIDTERM FORUMLA
            ($_lab_grade_midterm > 0 ?  // IF THE GRADE OF LABORATORY ID ENCODED
                (($_lec_grade_midterm + $_lab_grade_midterm)) // FORMULA FOR ENCODED LABORATORY : ((Lecture + Laboratory) * 50%)
                : ($_lec_grade_midterm / .4))
            // FINALS FORMULA
            : ($_lab_grade_midterm > 0 ?  // IF THE GRADE OF LABORATORY IS ENCODED IN MIDTERM

                (($_lec_grade_midterm + $_lab_grade_midterm) * .5) + $_total_final_grade  // FORMULA FOR (MLEC+MLAB)*.5 + (FLEC+FLAB)*.5
                : (($_lec_grade_midterm / .4) * .5) + $_total_final_grade);
    }
    public function percentage_grade($_grade)
    {
        $_percent = [
            [0, 69.4, 5.0],
            [69.5, 72.88, 3.0],
            [72.89, 76.27, 2.75],
            [76.28, 79.66, 2.5],
            [79.67, 83.05, 2.25],
            [83.06, 86.44, 2.00],
            [86.45, 89.83, 1.75],
            [89.84, 93.22, 1.5],
            [93.23, 96.61, 1.25],
            [96.62, 100, 1.0]
        ];
        $_percentage = 0;
        foreach ($_percent as $key => $value) {
            $_percentage = $_grade >= $value[0]  && $_grade <= $value[1] ? $value[2] : $_percentage;
        }
        return $_percentage;
    }
    public function student_single_file_import($_student)
    {
        echo "<b>" . $_student->student_details->last_name . ", " . $_student->student_details->first_name . "</b> <br>";
        foreach ($_student->student_details as $key => $value) {
            if ($key != 'email') {
                $_student_details[$key] = $value;
            }
        } // Set Student Details
        if ($_student->parent_details->_parent_details) {
            foreach ($_student->parent_details->_parent_details as $key => $value) {
                if ($key != 'id' && $key != 'created_at' && $key != 'updated_at') {
                    $_parent_details[$key] = $value;
                }
            }
            $_parent_details['is_removed'] = 0;
        }
        // Set the Parent Details to the Student Details
        // Sets other details
        $_student_details['religion'] = '';
        $_student_details['is_removed'] = 0;

        //return dd($_student_details);
        $_save_student = StudentDetails::create($_student_details); // Save Student Details
        echo "Student Details Saved <br>";
        $_account = array(
            'student_id' => $_save_student->id,
            'campus_email' => $_student->student_details->student_number . "." . mb_strtolower(str_replace(' ', '', $_student->student_details->last_name)) . "@bma.edu.ph",
            'personal_email' => $_student->student_details->email,
            'student_number' => $_student->student_details->student_number,
            'password' => Hash::make($_student->student_details->student_number),
            'is_actived' => 1,
            'is_removed' => 0
        );
        StudentAccount::create($_account);
        $_parent_details['student_id'] = $_save_student->id; // Get the Student Number
        //return dd($_parent_details);
        $_student->parent_details->_parent_details ? ParentDetails::create($_parent_details) : []; // Save Parent Details
        echo "Parent Details Saved <br>";
        // Educatinal Background
        foreach ($_student->educational_background->_educational as $key => $value) {
            $_educational = array(
                "student_id" => $_save_student->id,
                "school_name" => $value->school_name,
                "school_address" => $value->address,
                "graduated_year" => $value->year,
                "school_category" => '',
                "school_level" => $value->school_level,
                "is_removed" => 0
            ); // Set Educational Details
            EducationalDetails::create($_educational);
        } // Save and Get the Student Educational Background
        echo "Educationl Background Details Saved <br>";
        // Enrollment Assessment
        foreach ($_student->enrollment_assessment as $key => $value) {
            // Enrollment Assessment Details
            $_enrollment = array(
                "student_id" => $_save_student->id,
                "academic_id" => $value->academic_id,
                "course_id" => $value->course_id,
                "year_level" => $value->year_level,
                "curriculum_id" => $value->curriculum_id ==  null ? 1 : $value->curriculum_id,
                "bridging_program" => $value->bridging_program == null ? 'without' : $value->bridging_program,
                "staff_id" => 5,
                "is_removed" => 0
            ); // Enrollemtn Assessment Details
            $_enrollment = EnrollmentAssessment::create($_enrollment); // Save Enrollment Details
            echo "Enrollment Assessment Saved <br>";
            // Payment Assessment
            if ($value->payment_assessment) {
                $_payment = array(
                    'enrollment_id' => $_enrollment->id,
                    'payment_mode' => $value->payment_assessment->mode_of_payment,
                    'voucher_amount' => $value->payment_assessment->voucher_amount,
                    'total_payment' => $value->payment_assessment->total_payment,
                    'staff_id' => 6,
                    'is_removed' => 0,
                ); // Payment Assessment Details
                //echo dd($value->payment_assessment);
                $_payment = PaymentAssessment::create($_payment);
                echo "Payment Assessment Saved <br>";
                // Payment Transaction
                if ($_payment) {
                    foreach ($value->payment_assessment->payments as $key => $transaction) {
                        $_transaction = array(
                            'assessment_id' => $_payment->id,
                            'or_number' => $transaction->or_number,
                            'payment_amount' => $transaction->payment_amount,
                            'payment_method' => $transaction->payment_method,
                            'remarks' => $transaction->remarks,
                            'payment_transaction' => 'TUITION FEE',
                            'transaction_date' => $transaction->transaction_date ?: '2021-01-02',
                            'staff_id' => 6,
                            'is_removed' => 0
                        ); // Payment Transaction Details
                        PaymentTransaction::create($_transaction);
                        echo "Transaction Saved <br>";
                    }
                }
            }
        } // Enrollment Assessment
    }
}
