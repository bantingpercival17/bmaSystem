<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
    /* This portal the Back-end Side */
    public function profile_pic($_data)
    {
        $_formats = ['.jpeg', '.jpg', '.png'];
        $_path = 'http://20.0.0.120/img/student-picture/';
        //$_path = 'assets/image/student-picture/';
        $_image = "http://20.0.0.120/img/student-picture/midship-man.jpg";
        foreach ($_formats as $format) {
            $_image = @fopen($_path . $_data->student_number . $format, 'r') ? $_path . $_data->student_number . $format : $_image;
        }
        return $_image;
    }
    /* This for the Front-end Side */
    public function profile_picture()
    {
        $_formats = ['.jpeg', '.jpg', '.png'];
        $_path = 'http://20.0.0.120/img/student-picture/';
        $_image = "http://20.0.0.120/img/student-picture/midship-man.jpg";
        foreach ($_formats as $format) {
            $_image = @fopen($_path . $this->account->student_number . $format, 'r') ? $_path . $this->account->student_number . $format : $_image;
        }
        return $_image;
    }
    public function enrollment_assessment()
    {
        return $this->hasOne(EnrollmentAssessment::class, 'student_id')
            /* ->where('academic_id', Auth::user()->staff->current_academic()->id) */
            ->where('is_removed', 0)->orderBy('id', 'desc');
    }
    public function enrollment_assessment_v2()
    {
        return $this->hasOne(EnrollmentAssessment::class, 'student_id')
            ->where('academic_id', Auth::user()->staff->current_academic()->id)
            ->where('is_removed', 0)->orderBy('id', 'desc');
    }

    public function enrollment_status()
    {
        //return Auth::user()->staff->current_academic()->id;
        $_query = $this->hasOne(EnrollmentAssessment::class, 'student_id')
            ->where('is_removed', 0)->orderBy('id', 'desc');
        $_query = request()->input('_academic') ? $_query->where('academic_id', Auth::user()->staff->current_academic()->id) : $_query;
        return $_query;
        //return $this->hasOne(EnrollmentAssessment::class, 'student_id')->where('academic_id', Auth::user()->staff->current_academic()->id)->where('is_removed', 0)->orderBy('id', 'desc');
    }
    public function enrollment_history()
    {
        return $this->hasMany(EnrollmentAssessment::class, 'student_id')->where('is_removed', 0)->orderBy('id', 'desc');
    }
    public function enrollment_application()
    {
        return $this->hasOne(EnrollmentApplication::class, 'student_id')->whereNull('is_approved')->where('is_removed', false);
    }
    public function enrollment_application_v2()
    {
        return $this->hasOne(EnrollmentApplication::class, 'student_id')->where('is_removed', false)->where('academic_id', Auth::user()->staff->current_academic()->id);
    }
    public function enrollment_application_status($_data)
    {
        return $this->hasOne(EnrollmentAssessment::class, 'student_id')->where('is_removed', 0)->where('academic_id', $_data->id);
    }
    public function enrollment_application_payment()
    {
        return $this->hasOne(EnrollmentApplication::class, 'student_id')->where('is_approved', true)->where('academic_id', Auth::user()->staff->current_academic()->id)->where('is_removed', false);
    }
    public function account()
    {
        return $this->hasOne(StudentAccount::class, 'student_id')->where('is_actived', true)->orderBy('id', 'desc');
    }
    public function account_list()
    {
        return $this->hasMany(StudentAccount::class, 'student_id')->orderBy('id', 'desc');
    }
    public function educational_background()
    {
        return $this->hasMany(EducationalDetails::class, 'student_id')->where('is_removed', false);
    }
    public function parent_details()
    {
        return $this->hasOne(ParentDetails::class, 'student_id')->latest()/* ->where('is_removed', false) */;
    }
    public function section($_academic)
    {
        return $this->hasOne(StudentSection::class, 'student_id')
            ->select('student_sections.id', 'student_sections.student_id', 'student_sections.section_id')
            ->join('sections', 'sections.id', 'student_sections.section_id')
            ->where(
                'sections.section_name',
                'not like',
                '%BRIDGING%'
            )->where('sections.academic_id', $_academic)->where('student_sections.is_removed', false);
    }
    public function current_section()
    {
        return $this->hasOne(StudentSection::class, 'student_id')->where('is_removed', false)->orderBy('id', 'desc');
    }
    /* Student Search Query */
    public function student_search($_data)
    {
        $_student = explode(',', $_data);
        $_count = count($_student);
        //$_students =
        if (is_numeric($_data) == 1) {
            $_students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name', 'student_details.extention_name')->join('student_accounts', 'student_accounts.student_id', 'student_details.id')
                ->where('student_accounts.student_number', 'like', "%" . $_data . "%")->orderBy('student_details.last_name', 'asc')->get();
        } else {
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
        }

        return $_students;
    }
    /* Enrollment Application */
    public function enrollment_application_list()
    {
        $_academic = Auth::user()->staff->current_academic();
        $_students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
            ->leftJoin('enrollment_applications as ea', 'ea.student_id', 'student_details.id')
            ->where('ea.academic_id', $_academic->id)
            ->whereNull('ea.is_approved')
            ->where('ea.is_removed', false);
        return $_students->paginate(10);
    }
    public function enrollment_application_list_view_course($_data)

    {
        $_academic = Auth::user()->staff->current_academic();
        $_prevous_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)->orderBy('id', 'desc')->first();
        $_students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
            ->join('enrollment_assessments as eas', 'eas.student_id', 'student_details.id')
            ->join('enrollment_applications as ea', 'ea.student_id', 'student_details.id')
            ->where('ea.academic_id', $_academic->id)
            ->whereNull('ea.is_approved')
            ->where('eas.course_id', base64_decode($_data))
            ->where('eas.is_removed', false)
            ->where('eas.academic_id', $_prevous_academic->id);
        return $_students->paginate(10);
    }
    /* Grading Query */
    public function subject_score($_data)
    {
        $_score =  $this->hasOne(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_data[0])
            ->where('period', $_data[1])
            ->where('is_removed', false)
            ->where('type', $_data[2])->first();
        return $_score ? $_score->score : null;
    }
    public function subject_average_score($_data)
    {
        $_subject_class = SubjectClass::find($_data[0]);
        // Set the for Formula by Academic
        if ($_subject_class->academic_id > 4) {
            $_percent = $_data['2'] == 'Q' ? .15 : ($_data['2'] == 'O' || $_data['2'] == 'R' ? .20 : .45);
        } else {
            $_percent = $_data['2'] == 'Q' || $_data['2'] == 'O' || $_data['2'] == 'R'  ? .15 : .55;
        }
        // $_percent = $_data['2'] == 'Q' || $_data['2'] == 'O' || $_data['2'] == 'R'  ? .15 : .55;
        //Compute the total Average
        $computetion =  $this->hasMany(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_data[0])
            ->where('period', $_data[1])
            ->where('is_removed', false)
            ->where('type', 'like',  $_data[2] . "%")->average('score');
        // Convert into Percentage
        return $_data['2'] == 'CO' ? $computetion : ($computetion  * $_percent);
    }
    public function lecture_grade($_data)
    {
        $_tScore = 0;
        $_categories = ['Q', 'O', 'R', $_data[1][0] . 'E'];
        foreach ($_categories as $key => $value) {
            $_tScore += $this->subject_average_score([$_data[0], $_data[1], $value]);
        }
        $_subject_class = SubjectClass::find($_data[0]);
        $_percent = $_subject_class->academic_id > 4 ? .50 : .40;
        return $_tScore * $_percent;
    }

    public function laboratory_grade($_data)
    {
        // Get the Percentage
        $_subject_class = SubjectClass::find($_data[0]);
        $_percent = $_subject_class->academic_id > 4 ? .50 : .60;
        // Compute Laboratory Grades
        return $this->hasMany(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_data[0])
            ->where('period', $_data[1])
            ->where('type', 'like',  "A%")
            ->where('is_removed', false)->average('score') * $_percent;
    }
    public function laboratory_item($_data)
    {
        // Count the total number of items in Laboratory
        return $this->hasMany(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_data[0])
            ->where('period', $_data[1])
            ->where('type', 'like',  "A%")
            ->where('is_removed', false)->count();
    }
    public function final_grade_v2($_data, $_period)
    {
        $_final_grade = 0;
        $midtermGradeLecture = $this->lecture_grade([$_data, 'midterm']);
        $midtermGradeLaboratory = $this->laboratory_grade([$_data, 'midterm']);
        $midtermLaboratoryItem = $this->laboratory_item([$_data, 'midterm']);
        $finalGradeLecture = $this->lecture_grade([$_data, 'finals']);
        $finalGradeLaboratory = $this->laboratory_grade([$_data, 'finals']);
        $_subject_class = SubjectClass::find($_data);
        if ($_subject_class->academic_id >= 5) {
            if ($_subject_class->curriculum_subject->subject->laboratory_hours > 0) {
                if ($midtermGradeLecture > 0) {
                    $_final_grade = $_period == 'midterm' ? ($midtermGradeLecture + $midtermGradeLaboratory) : ($finalGradeLecture + $finalGradeLaboratory);
                } else {
                    $_final_grade = ($_period == 'midterm' ? $midtermGradeLaboratory : $finalGradeLaboratory) / .5;
                }
            } else {
                $_final_grade = ($_period == 'midterm' ? $midtermGradeLecture : $finalGradeLecture) / .5;
            }
        } else {
            if ($_period == 'midterm') {
                if ($midtermLaboratoryItem > 0) {
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
                    if ($finalGradeLecture > 0) {
                        if ($midtermGradeLaboratory > 0) {
                            $_final_grade =  (($midtermGradeLecture + $midtermGradeLaboratory) * .5) + (($finalGradeLecture + $finalGradeLaboratory) * .5);
                        } else {
                            $_final_grade =  (($midtermGradeLecture / .4) * .5) + (($finalGradeLecture / .4) * .5);
                        }
                    } else {
                        $_final_grade = null;
                    }
                }
            }
        }
        return $_final_grade;
    }
    public function percentage_grade($_grade)
    {
        $_percent = [
            [0, 69.46, 5.0],
            [69.47, 72.88, 3.0],
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
    /* Formula Revision */
    public function quizzes_average()
    {
        $_subject_class = base64_decode(request()->input('_subject'));
        $_subject_class = SubjectClass::find($_subject_class);
        $_period = request()->input('_period');
        $_percent = .15;
        $computetion =  $this->hasMany(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_subject_class->id)
            ->where('period', $_period)
            ->where('is_removed', false)
            ->where('type', 'like', "Q%")->average('score') * $_percent;
        $cellOne = $this->subject_score([$_subject_class->id, $_period, 'Q1']);
        return $cellOne ? number_format($computetion, 2) : '';
    }
    public function oral_average()
    {
        $_subject_class = base64_decode(request()->input('_subject'));
        $_subject_class = SubjectClass::find($_subject_class);
        $_period = request()->input('_period');
        $_percent = $_subject_class->academic->id > 4 ? .20 : .15;
        $computetion =  $this->hasMany(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_subject_class->id)
            ->where('period', $_period)
            ->where('is_removed', false)
            ->where('type', 'like', "O%")->average('score') * $_percent;
        $cellOne = $this->subject_score([$_subject_class->id, $_period, 'O1']);
        return $cellOne ? number_format($computetion, 2) : '';
    }
    public function research_work_average()
    {
        $_subject_class = base64_decode(request()->input('_subject'));
        $_subject_class = SubjectClass::find($_subject_class);
        $_period = request()->input('_period');
        $_percent = $_subject_class->academic->id > 4 ? .20 : .15;
        $computetion =  $this->hasMany(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_subject_class->id)
            ->where('period', $_period)
            ->where('is_removed', false)
            ->where('type', 'like', "R%")->average('score') * $_percent;
        $cellOne = $this->subject_score([$_subject_class->id, $_period, 'R1']);
        return $cellOne ? number_format($computetion, 2) : '';
    }
    public function examination_average()
    {
        $_subject_class = base64_decode(request()->input('_subject'));
        $_subject_class = SubjectClass::find($_subject_class);
        $_period = request()->input('_period');
        $_percent = $_subject_class->academic->id > 4 ? .45 : .55;
        $cellOne = $this->subject_score([$_subject_class->id, $_period, strtoupper($_period)[0] . 'E1']);
        $computetion = $cellOne * $_percent;
        return $cellOne ? number_format($computetion, 2) : '';
    }
    public function lecture_grade_v2()
    {
        $_subject_class = base64_decode(request()->input('_subject'));
        $_subject_class = SubjectClass::find($_subject_class);
        $quiz = $this->quizzes_average();
        $oral = $this->oral_average();
        $research = $this->research_work_average();
        $examination = $this->examination_average();
        $_percent = $_subject_class->academic_id > 4 ? .50 : .40;
        $grade = '';
        if ($quiz !== '' && $oral !== '' && $research !== '' && $examination !== '') {
            $grade = ($quiz + $oral + $research + $examination) * $_percent;
        }
        return $grade;
    }

    public function laboratory_grade_v2()
    {
        $_subject_class = base64_decode(request()->input('_subject'));
        $_subject_class = SubjectClass::find($_subject_class);
        $_period = request()->input('_period');
        $_percent = $_subject_class->academic_id > 4 ? .50 : .60;
        // Compute Laboratory Grades
        $computetion = $this->hasMany(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_subject_class->id)
            ->where('period', $_period)
            ->where('type', 'like',  "A%")
            ->where('is_removed', false)->average('score') * $_percent;
        $cellOne = $this->subject_score([$_subject_class->id, $_period, 'A1']);
        return $cellOne ? number_format($computetion, 2) : '';
    }
    public function period_final_grade()
    {
        // Get the Subject Class
        $_subject_class = SubjectClass::find(base64_decode(request()->input('_subject')));
        // Final Grade
        $final_grade = '';
        // New Formula
        if ($_subject_class->academic_id >= 5) {
            // Full Computetion Lecture & Laboratory
            if ($_subject_class->curriculum_subject->subject->laboratory_hours > 0 && $_subject_class->curriculum_subject->subject->lecture_hours > 0) {
                if ($this->lecture_grade_v2() !== '' && $this->laboratory_grade_v2() !== '') {
                    $final_grade = number_format($this->lecture_grade_v2() + $this->laboratory_grade_v2(), 2);
                }
            }
            // Laboratory
            if ($_subject_class->curriculum_subject->subject->laboratory_hours > 0) {
                if ($this->laboratory_grade_v2() !== '') {
                    $final_grade =  number_format(($this->laboratory_grade_v2() / .5), 2);
                }
            }
            // Lecture
            if ($_subject_class->curriculum_subject->subject->lecture_hours > 0) {
                if ($this->lecture_grade_v2() !== '') {
                    $final_grade = number_format(($this->lecture_grade_v2() / .5), 2);
                }
            }
        } else {
            # code...
        }
        return $final_grade;
    }
    public function course_outcome_avarage()
    {
        $_subject_class = base64_decode(request()->input('_subject'));
        $_subject_class = SubjectClass::find($_subject_class);
        $_period = request()->input('_period');
        $computetion = $this->hasMany(GradeEncode::class, 'student_id')
            ->where('subject_class_id', $_subject_class->id)
            ->where('period', $_period)
            ->where('type', 'like',  "CO%")
            ->where('is_removed', false)->average('score');
        $cellOne = $this->subject_score([$_subject_class->id, $_period, 'CO1']);
        return $cellOne ? number_format($computetion, 2) : '';
    }
    public function total_final_grade()
    {
        $_subject_class = SubjectClass::find(base64_decode(request()->input('_subject')));
        // Period Grade
        $grade = $this->period_final_grade();
        // Final Grade
        $final_grade = '';
        return $final_grade;
    }
    public function point_grade()
    {
        $_subject_class = SubjectClass::find(base64_decode(request()->input('_subject')));
        // Period Grade
        $grade = $this->period_final_grade();
        // Final Grade
        $final_grade = '';
        if ($_subject_class->curriculum_subject->subject->laboratory_hours > 0 && $_subject_class->curriculum_subject->subject->lecture_hours > 0) {
            if ($this->lecture_grade_v2() !== '' && $this->laboratory_grade_v2() !== '') {
                $final_grade = $grade !== '' ? number_format($this->percentage_grade($grade), 2) : 'INC';
            } else {
                $final_grade = 'INC';
            }
        }
        // Laboratory
        if ($_subject_class->curriculum_subject->subject->laboratory_hours > 0) {
            if ($this->laboratory_grade_v2() !== '') {
                $final_grade = $grade !== '' ? number_format($this->percentage_grade($grade), 2) : 'INC';
            } else {
                $final_grade = 'INC';
            }
        }
        // Lecture
        if ($_subject_class->curriculum_subject->subject->lecture_hours > 0) {
            if ($this->lecture_grade_v2() !== '') {
                $final_grade = $grade !== '' ? number_format($this->percentage_grade($grade), 2) : 'INC';
            } else {
                $final_grade = 'INC';
            }
        }
        return $final_grade;
    }
    public function grade_publish()
    {
        return $this->hasOne(GradePublish::class, 'student_id')->where('academic_id', Auth::user()->staff->current_academic()->id)->where('is_removed', false)/* ->where('is_removed', false) */;
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
        return $this->hasMany(ShipboardJournal::class, 'student_id')->select('month', DB::raw('count(*) as total'), DB::raw('count(is_approved) as is_approved'))->groupBy('month')->where('is_removed', false);
    }
    public function clearance($_data)
    {
        return $this->hasOne(StudentClearance::class, 'student_id')->where('subject_class_id', $_data)->where('is_removed', false)->latest('id')->first();
    }
    public function non_academic_clearance($_data)
    {
        $_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)->orderBy('id', 'desc')->first();
        return $this->hasOne(StudentNonAcademicClearance::class, 'student_id')->where('non_academic_type', str_replace(' ', '-', strtolower($_data)))->where('is_removed', false)->where('academic_id', Auth::user()->staff->current_academic()->id)->latest('id')->first();
    }
    public function non_academic_clearance_for_enrollment($_data)
    {
        $_academic = AcademicYear::where('id', '<', Auth::user()->staff->current_academic()->id)->orderBy('id', 'desc')->first();
        return $this->hasOne(StudentNonAcademicClearance::class, 'student_id')->where('non_academic_type', str_replace(' ', '-', strtolower($_data)))->where('is_removed', false)->where('academic_id', $_academic->id)->latest('id')->first();
    }
    public function academic_clearance_status()
    {
        $_enrollment = $this->hasOne(EnrollmentAssessment::class, 'student_id')->where('is_removed', 0)->latest('id')->first();
        $_section  = $this->hasOne(StudentSection::class, 'student_id')->select('student_sections.id', 'student_sections.student_id', 'student_sections.section_id')
            ->join('sections', 'sections.id', 'student_sections.section_id')->where('sections.academic_id',  Auth::user()->staff->current_academic()->id)->where('student_sections.is_removed', false)->first();
        $_academic_clearance = $this->hasMany(StudentClearance::class, 'student_id')/* ->where('academic_id', Auth::user()->staff->current_academic()->id) */->where('is_approved', true)->where('is_removed', false);
        if ($_section) {
            $_subject_count = SubjectClass::where('section_id', $_section->section_id)->where('is_removed', false)->get();
            $_subject_count =  $_subject_count->count();
            if ($_enrollment->bridging_program == 'without' && $_enrollment->academic->semester == 'First Semester' && $_enrollment->year_level == 4) {
                $_subject_count -= 1;
            }
            return $_subject_count == $_academic_clearance->count() ? 'CLEARED' : 'NOT CLEARED';
        } else {
            return "NO SECTION";
        }
    }
    public function non_academic_clearance_status()
    {
        $_non_academic_count  = 8;
        $_enrollment = $this->hasOne(EnrollmentAssessment::class, 'student_id')->where('is_removed', 0)->latest('id')->first();
        $_student_clearance = $this->hasMany(StudentNonAcademicClearance::class, 'student_id')->where('is_removed', false)->where('academic_id',  Auth::user()->staff->current_academic()->id)->where('is_approved', true);
        return $_non_academic_count == $_student_clearance->count() ? 'CLEARED' : 'NOT CLEARED';
    }
    public function offical_clearance_cleared()
    {
        $_non_academic_count  = 8;
        $_enrollment = $this->hasOne(EnrollmentAssessment::class, 'student_id')->where('is_removed', 0)->latest('id')->first();
        $_section  = $this->hasOne(StudentSection::class, 'student_id')->select('student_sections.id', 'student_sections.student_id', 'student_sections.section_id')
            ->join('sections', 'sections.id', 'student_sections.section_id')->where('sections.academic_id', $_enrollment->academic_id)->where('student_sections.is_removed', false)->first();
        //$_section = $this->hasOne(StudentSection::class, 'student_id')->where('is_removed', 0)->latest('id')->first();
        $_student_non_academic_clearance = $this->hasMany(StudentNonAcademicClearance::class, 'student_id')->where('is_removed', false)->where('is_approved', true)->where('academic_id', $_enrollment->academic_id);
        $_academic_clearance = $this->hasMany(StudentClearance::class, 'student_id')->where('is_approved', true)->where('is_removed', false);
        if ($_section) {
            $_subject_count = SubjectClass::where('section_id', $_section->section_id)->where('is_removed', false)->get();
            $_subject_count =  $_subject_count->count();
            if ($_enrollment->bridging_program == 'without' && $_enrollment->academic->semester == 'First Semester' && $_enrollment->year_level == 4) {
                $_subject_count -= 1;
            }
            //return $_student_non_academic_clearance->count();
            if ($_non_academic_count == $_student_non_academic_clearance->count() && $_subject_count == $_academic_clearance->count()) {
                $_student_cleared = OfficalCleared::where([
                    'student_id' => $_enrollment->student_id,
                    'academic_id' => $_enrollment->academic_id,
                    'course_id' => $_enrollment->course_id,
                ])->first();
                if (!$_student_cleared) {
                    OfficalCleared::create([
                        'student_id' => $_enrollment->student_id,
                        'academic_id' => $_enrollment->academic_id,
                        'course_id' => $_enrollment->course_id,
                        'is_cleared' => true,
                        'is_removed' => false
                    ]);
                }
            } else {
                $_student_cleared = OfficalCleared::where([
                    'student_id' => $_enrollment->student_id,
                    'academic_id' => $_enrollment->academic_id,
                    'course_id' => $_enrollment->course_id,
                ])->first();
                if ($_student_cleared) {
                    $_student_cleared->is_cleared = false;
                    $_student_cleared->save();
                }
            }
            // return $_subject_count == $_academic_clearance->count() ? 'CLEARED' : 'NOT CLEARED';
        }
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
            'email' => $_student->student_details->student_number . "." . mb_strtolower(str_replace(' ', '', $_student->student_details->last_name)) . "@bma.edu.ph",
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
        $_email = $_student->student_details->student_number . "." . mb_strtolower(str_replace(' ', '', $_student->student_details->last_name)) . "@bma.edu.ph";
        $_data_student = StudentDetails::where(['first_name' => $_student->student_details->first_name, 'last_name' => $_student->student_details->last_name])->first();
        $_data_to_log[] =  date("Y-m-d H:i:s"); //Date and time
        $_data_to_log[] =  $_SERVER['REMOTE_ADDR']; //IP address
        $_data_to_log[] =  $_email; // Student Email
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
                    'email' => $_student->student_details->student_number . "." . mb_strtolower(str_replace(' ', '', $_student->student_details->last_name)) . "@bma.edu.ph",
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
                if ($_data_student->account) {
                    $_data_student->account->email = trim(str_replace('-', '', $_data_student->account->email));
                    $_data_student->account->personal_email = trim(str_replace(' ', '', $_data_student->account->personal_email));
                    $_data_student->account->save();
                    $_data_to_log[] = ':: Updating Emails';
                }

                $_data_to_log[] .= PHP_EOL;
                $_data_student->birthday = $_student->student_details->birthday;
                $_data_student->save();
                $_data_to_log[] = ':: Updating Birthday';
                $_data_to_log[] .= PHP_EOL;
                foreach ($_student->enrollment_assessment as $key => $value) {
                    // Enrollment Assessment Details
                    $_data_to_log[] = ':: Storing Enrollment Details : Academic ID:' . $value->academic_id;
                    $_data_to_log[] .= PHP_EOL;
                    $_enrollment = array(
                        "student_id" => $_data_student->id,
                        "academic_id" => $value->academic_id,
                        "course_id" => $value->course_id,
                        "year_level" => $value->year_level,
                        "curriculum_id" => $value->curriculum_id ==  null ? 1 : $value->curriculum_id,
                    ); // Enrollemtn Assessment Details
                    $_enrollment = EnrollmentAssessment::where($_enrollment)->first();
                    $_data_to_log[] = $_enrollment ? 'Update' : 'Empty';
                    /*  $_enrollment->created_at = $value->created_at;
                    $_enrollment->updated_at = $value->updated_at;
                    $_enrollment->save(); */
                    //$_enrollment = EnrollmentAssessment::create($_enrollment); // Save Enrollment Details
                    $_data_to_log[] = ':: Updating Enrollment Details';
                    $_data_to_log[] .= PHP_EOL;
                    // Payment Assessment
                    if ($value->payment_assessment) {
                        $_data_to_log[] = '::Updating Payment Assessment';
                        $_data_to_log[] .= PHP_EOL;
                        /*  $_payment = PaymentAssessment::where('enrollment_id', $_enrollment->id)->first();
                        $_payment = array(
                            'enrollment_id' => $_enrollment->id,
                            'payment_mode' => $value->payment_assessment->mode_of_payment,
                            'voucher_amount' => $value->payment_assessment->voucher_amount,
                            'total_payment' => $value->payment_assessment->total_payment,
                            'staff_id' => 6,
                            'is_removed' => 0,
                            "created_at" => $value->created_at,
                            "updated_at" => $value->updated_at
                        ); */ // Payment Assessment Details
                        //echo dd($value->payment_assessment);
                        //$_payment = PaymentAssessment::create($_payment);
                        //$_data_to_log[] = ':: Updating Payment Assessment';
                        //$_data_to_log[] .= PHP_EOL;
                        // Payment Transaction
                        /*  if ($_payment) {
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
                        } */
                    } else {
                        $_data_to_log[] = ':: Payment Assessment Details Empty';
                        $_data_to_log[] .= PHP_EOL;
                    }
                } // Enrollment Assessment
                /*  if ($_student->ship_board_training->_shipboard_training) {
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
                } */
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
    public function student_shipboard_journals()
    {
        return StudentDetails::select('student_details.*')
            ->join('shipboard_journals', 'shipboard_journals.student_id', 'student_details.id')
            ->where('shipboard_journals.is_approved', null)->groupBy('shipboard_journals.student_id')->where('shipboard_journals.is_removed', false);
    }
    public function shipboard_application()
    {
        return $this->hasOne(DeploymentAssesment::class, 'student_id')/* ->where('is_removed', false)->whereNull('staff_id') */;
    }
    public function shipboard_narative_status()
    {
        return $this->hasMany(ShipboardJournal::class, 'student_id')->groupBy('month')->whereNull('is_approved')->where('is_removed', false);
    }
    public function single_narative_report($_data, $details)
    {
        return $this->hasOne(ShipboardJournal::class, 'student_id')->where('month', 'like', '%' . $_data . '%')->where('journal_type', $details)->where('is_removed', false)->first();
    }
    public function narrative_documents($_data)
    {
        return $this->hasMany(ShipboardJournal::class, 'student_id')->where('month', 'like', '%' . $_data . '%')->where('is_removed', false)->orderBy('journal_type', 'desc');
    }
    public function onboard_examination()
    {
        return $this->hasOne(ShipboardExamination::class, 'student_id')->where('is_removed', false);
    }
    public function assessment_details()
    {
        return $this->hasOne(ShipboardAssessmentDetails::class, 'student_id')->where('is_removed', false);
    }
    public function student_medical_appointment()
    {
        return $this->hasOne(StudentMedicalAppointment::class, 'student_id')->where('is_removed', false);
    }
    public function student_medical_result()
    {
        return $this->hasOne(StudentMedicalResult::class, 'student_id')->where('is_removed', false)->orderBy('id', 'desc');
    }
    public function onboarding_attendance()
    {
        $now =  request()->input('week');
        $day = new DateTime($now);
        $week =  date('l', strtotime($now));
        $modify = $week == 'Sunday' ? 'Sunday' : 'Last Sunday';
        $_week_start = $day->modify($modify);
        $_week_start = $day->format('Y-m-d');
        $_week_end = $day->modify('Next Saturday');
        $_week_end = $day->format('Y-m-d');
        $_week_dates = [$_week_start . '%', $_week_end . '%'];
        return $this->hasOne(StudentOnboardingAttendance::class, 'student_id')->whereBetween('created_at', $_week_dates);
    }
    public function id_verification()
    {
        return $this->hasOne(StudentIDDetails::class, 'student_id')->where('is_removed', false);
    }
}
