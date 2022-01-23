<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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

    public function profile_pic($_data)
    {
        $_formats = ['.jpeg', '.jpg', '.png'];
        $_path = 'http://bma.edu.ph/img/student-picture/';
        //$_path = 'assets/image/student-picture/';
        $_image = "http://bma.edu.ph/img/student-picture/midship-man.jpg";
        foreach ($_formats as $format) {
            $_image = @fopen($_path . $_data->student_number . $format, 'r') ? $_path . $_data->student_number . $format : $_image;
        }
        return $_image;
    }
    public function enrollment_assessment()
    {
        return $this->hasOne(EnrollmentAssessment::class, 'student_id')->where('is_removed', 0)->orderBy('id', 'desc');
    }
    public function enrollment_history()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'student_id')->where('is_removed', 0)->orderBy('id', 'desc');
    }
    public function enrollment_application()
    {
        return $this->hasOne(EnrollmentApplication::class, 'student_id');
    }
    public function account()
    {
        return $this->hasOne(StudentAccount::class, 'student_id');
    }
    public function educational_background()
    {
        return $this->hasMany(EducationalDetails::class, 'student_id');
    }
    public function parent_details()
    {
        return $this->hasOne(ParentDetails::class, 'student_id');
    }
    public function section($_academic)
    {
        return $this->hasOne(StudentSection::class, 'student_id')->select('student_sections.id', 'student_sections.student_id', 'student_sections.section_id')
            ->join('sections', 'sections.id', 'student_sections.section_id')->where('sections.academic_id', $_academic)->where('student_sections.is_removed', false);
    }

    /* Student Search Query */
    public function student_search($_data)
    {
        $_student = explode(',', $_data);
        $_count = count($_student);
        //$_students = 
        if ($_count > 1) {
            $_students = StudentDetails::where('is_removed', false)
                ->where('last_name', 'like', "%" . $_student[0] . "%")
                ->where('first_name', 'like', "%" . trim($_student[1]) . "%")
                ->orderBy('last_name', 'asc')->get();
        } else {
            $_students = StudentDetails::where('is_removed', false)
                ->where('last_name', 'like', "%" . $_student[0] . "%")
                ->orderBy('last_name', 'asc')->get();
        }
        return $_students;
    }
    /* Enrollment Application */
    public function enrollment_application_list()
    {
        $_academic = request()->input('_academic') ? AcademicYear::find(base64_decode(request()->input('_academic'))) : AcademicYear::where('is_active', 1)->first();
        //$_students = $this->hasMany(EnrollmentApplication::class, 'student_id')->where('academic_id', $_academic->id);
        $_students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')->join('enrollment_applications as ea', 'ea.student_id', 'student_details.id')->where('ea.academic_id', $_academic->id)->where('is_approved', null);
        return $_students->get();
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

        $_final_grade = 0;
        $midtermGradeLecture = $this->lec_grade([$_data, 'midterm']);
        $midtermGradeLaboratory = $this->lab_grade([$_data, 'midterm']);;
        $finalGradeLecture = $this->lec_grade([$_data, 'finals']);
        $finalGradeLaboratory = $this->lab_grade([$_data, 'finals']);
        if ($_period == 'midterm') {
            if ($midtermGradeLaboratory > 0) {
                $_final_grade = $midtermGradeLecture + $midtermGradeLaboratory; // Midterm Grade Formula With Laboratory
            } else {
                $_final_grade = $midtermGradeLecture / .4; // Midterm Grade Formula without Laboratory
            }
        } else {
            if ($finalGradeLaboratory > 0) {
                if ($midtermGradeLaboratory > 0) {
                    $_final_grade =  (($midtermGradeLecture + $midtermGradeLaboratory) * .5) + (($finalGradeLecture + $finalGradeLaboratory) * .5);
                } else {
                    $_final_grade =  (($midtermGradeLecture / .4) * .5) + (($finalGradeLecture + $finalGradeLaboratory) * .5);
                }
            } else {
                $_final_grade = (($midtermGradeLecture / .4)) + (($finalGradeLecture / .4));
            }
        }
        return $_final_grade;
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

    /* Shipboard Model */

    public function shipboard_training()
    {
        return $this->hasOne(ShipBoardInformation::class, 'student_id');
    }
    public function shipboard_journals($_data)
    {
        return $this->hasMany(ShipboardJournal::class, 'student_id')->where('journal_type', $_data)->where('is_removed', false)->orderBy('month', 'Asc');
    }
    public function narative_report()
    {
        return $this->hasMany(ShipboardJournal::class, 'student_id')->select('month', DB::raw('count(*) as total'))->groupBy('month');
    }
    public function clearance($_data)
    {
        return $this->hasOne(StudentClearance::class, 'student_id')->where('subject_class_id', $_data)->where('is_removed', false)->first();
    }
    public function non_academic_clearance($_data)
    {
        return $this->hasOne(StudentNonAcademicClearance::class, 'student_id')->where('non_academic_type', $_data)->where('is_removed', false)->first();
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

    public function upload_student_details($_student)
    {

        //return dd($_student);
        $_campus_email = $_student->student_details->student_number . "." . mb_strtolower(str_replace(' ', '', $_student->student_details->last_name)) . "@bma.edu.ph";
        $_data_student = StudentDetails::where(['first_name' => $_student->student_details->first_name, 'last_name' => $_student->student_details->last_name])->first();
        $_data_to_log[] =  date("Y-m-d H:i:s"); //Date and time
        $_data_to_log[] =  $_SERVER['REMOTE_ADDR']; //IP address
        $_data_to_log[] =  $_campus_email; // Student Email
        $_data_to_log[] .= PHP_EOL;
        // Checking the Student Details 
        if ($_student->student_details->first_name != '' && $_student->student_details->last_name != '') {
            if (!$_data_student) {
                // If the Student is not exist to the it will be Created on the database
                // Create Student Details
                foreach ($_student->student_details as $key => $value) {
                    if ($key != 'email') {
                        $_create_student[$key] = $value;
                    }
                } // Create an array for StudentDetails
                // Then Additional Attributes for StudentDetails
                $_create_student['religion'] = '';
                $_create_student['is_removed'] = false;
                $_data_to_log[] = ':: Creating New Student Details';
                $_data_to_log[] .= PHP_EOL;
                $_store_student = StudentDetails::create($_create_student);
                $_data_to_log[] = ':: tored New Student Details';
                $_data_to_log[] .= PHP_EOL;
                // Create a StudentAccount
                $_data_to_log[] = ':: Creating Student Accounts';
                $_data_to_log[] .= PHP_EOL;
                $_create_account = array(
                    'student_id' => $_store_student->id,
                    'campus_email' => $_student->student_details->student_number . "." . mb_strtolower(str_replace(' ', '', $_student->student_details->last_name)) . "@bma.edu.ph",
                    'personal_email' => $_student->student_details->email,
                    'student_number' => $_student->student_details->student_number,
                    'password' => Hash::make($_student->student_details->student_number),
                    'is_actived' => 1,
                    'is_removed' => false
                );
                $_account = StudentAccount::where('student_number', $_student->student_details->student_number)->first();
                if ($_account) {
                    $_data_to_log[] = ':: Student Account is Already Exited';
                    $_data_to_log[] .= PHP_EOL;
                } else {
                    StudentAccount::create($_create_account);
                    $_data_to_log[] = ':: Stored Student Account';
                    $_data_to_log[] .= PHP_EOL;
                }
                // Store Parent Details
                // Get Parent Details to the array
                $_data_to_log[] = ':: Storing Student Parent Details';
                $_data_to_log[] .= PHP_EOL;
                if ($_student->parent_details->_parent_details) {
                    foreach ($_student->parent_details->_parent_details as $key => $value) {
                        if ($key != 'id' && $key != 'created_at' && $key != 'updated_at') {
                            $_parent_details[$key] = $value;
                        }
                    }
                    $_parent_details['student_id'] = $_store_student->id;
                    $_parent_details['is_removed'] = 0;
                }
                $_student->parent_details->_parent_details ? ParentDetails::create($_parent_details) : []; // Save Parent Details
                $_data_to_log[] = ':: Stored Successfully';
                $_data_to_log[] .= PHP_EOL;

                // Educatinal Background
                $_data_to_log[] = ':: Storing Educational Background';
                $_data_to_log[] .= PHP_EOL;
                foreach ($_student->educational_background->_educational as $key => $value) {
                    $_educational = array(
                        "student_id" => $_store_student->id,
                        "school_name" => $value->school_name,
                        "school_address" => $value->address,
                        "graduated_year" => $value->year,
                        "school_category" => '',
                        "school_level" => $value->school_level,
                        "is_removed" => 0
                    ); // Set Educational Details
                    EducationalDetails::create($_educational);
                    $_data_to_log[] = ':: tored Educational Background : ' . $value->year;
                    $_data_to_log[] .= PHP_EOL;
                } // Save and Get the Student Educational Background
                // Enrollment Assessment
                foreach ($_student->enrollment_assessment as $key => $value) {
                    // Enrollment Assessment Details
                    $_data_to_log[] = ':: Storing Enrollment Details : Academic ID:' . $value->academic_id;
                    $_data_to_log[] .= PHP_EOL;
                    $_enrollment = array(
                        "student_id" => $_store_student->id,
                        "academic_id" => $value->academic_id,
                        "course_id" => $value->course_id,
                        "year_level" => $value->year_level,
                        "curriculum_id" => $value->curriculum_id ==  null ? 1 : $value->curriculum_id,
                        "bridging_program" => $value->bridging_program == null ? 'without' : $value->bridging_program,
                        "staff_id" => 5,
                        "is_removed" => 0,
                        "created_at" => $value->created_at,
                        "updated_at" => $value->updated_at
                    ); // Enrollemtn Assessment Details
                    $_enrollment = EnrollmentAssessment::create($_enrollment); // Save Enrollment Details
                    $_data_to_log[] = ':: Stored Enrollment Details';
                    $_data_to_log[] .= PHP_EOL;
                    // Payment Assessment
                    if ($value->payment_assessment) {
                        $_data_to_log[] = '::Storing Payment Assessment';
                        $_data_to_log[] .= PHP_EOL;
                        $_payment = array(
                            'enrollment_id' => $_enrollment->id,
                            'payment_mode' => $value->payment_assessment->mode_of_payment,
                            'voucher_amount' => $value->payment_assessment->voucher_amount,
                            'total_payment' => $value->payment_assessment->total_payment,
                            'staff_id' => 6,
                            'is_removed' => 0,
                            "created_at" => $value->created_at,
                            "updated_at" => $value->updated_at
                        ); // Payment Assessment Details
                        //echo dd($value->payment_assessment);
                        $_payment = PaymentAssessment::create($_payment);
                        $_data_to_log[] = '::Stored Payment Assessment';
                        $_data_to_log[] .= PHP_EOL;
                        // Payment Transaction
                        if ($_payment) {
                            foreach ($value->payment_assessment->payments as $key => $transaction) {
                                $_data_to_log[] = '::Storing Payment Transaction';
                                $_data_to_log[] .= PHP_EOL;
                                $_transaction = array(
                                    'assessment_id' => $_payment->id,
                                    'or_number' => $transaction->or_number,
                                    'payment_amount' => $transaction->payment_amount,
                                    'payment_method' => $transaction->payment_method,
                                    'remarks' => $transaction->remarks,
                                    'payment_transaction' => 'TUITION FEE',
                                    'transaction_date' => $transaction->transaction_date ?: '2021-01-02',
                                    'staff_id' => 6,
                                    'is_removed' => 0,
                                    "created_at" => $value->created_at,
                                    "updated_at" => $value->updated_at
                                ); // Payment Transaction Details
                                PaymentTransaction::create($_transaction);
                                $_data_to_log[] = '::Stored Payment Transaction';
                                $_data_to_log[] .= PHP_EOL;
                            }
                        }
                    } else {
                        $_data_to_log[] = ':: Payment Assessment Details Empty';
                        $_data_to_log[] .= PHP_EOL;
                    }
                } // Enrollment Assessment
                // Shipboard Training
                if ($_student->ship_board_training->_shipboard_training) {
                    $_data_to_log[] = ':: Storing Shipboard Training Details';
                    $_data_to_log[] .= PHP_EOL;
                    foreach ($_student->ship_board_training->_shipboard_training as $key => $value) {
                        if ($key != 'id' && $key != 'student_id' && $key != 'remove_st') {
                            $_training_details[$key] = $value;
                        }
                    }
                    $_training_details['student_id'] = $_store_student->id;
                    $_training_details['is_removed'] = false;
                    ShipBoardInformation::create($_training_details);
                    $_data_to_log[] = ':: Stored Shipboard Training Details';
                    $_data_to_log[] .= PHP_EOL;
                }
            } else {
                // Then if Student exist all the data Updates


                if ($_student->ship_board_training->_shipboard_training) {
                    $_data_to_log[] = ':: Updating Shipboard Training Details';
                    $_data_to_log[] .= PHP_EOL;

                    foreach ($_student->ship_board_training->_shipboard_training as $key => $value) {
                        if ($key != 'id' && $key != 'student_id' && $key != 'remove_st') {
                            $_shipboard_training[$key] = $value;
                        }
                    }
                    $_shipping = ShipBoardInformation::where('student_id', $_data_student->id)->update($_shipboard_training);
                    //$_shipping->update($_shipboard_training);
                    $_data_to_log[] = ':: Updated Shipboard Training Details';
                    $_data_to_log[] .= PHP_EOL;
                }
            }
        } else {
            $_data_to_log[] = ':: Empty Data';
            $_data_to_log[] .= PHP_EOL;
        }

        $_data_to_log = implode(" ", $_data_to_log);
        $_file_name = 'logs/upload_student.log';
        //$_file_name = 'logs/'.$_campus_email.'.log';
        Storage::disk('public')->append($_file_name, $_data_to_log, null);
        //dd($_student);
    }
}
