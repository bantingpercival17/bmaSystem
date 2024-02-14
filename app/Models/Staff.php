<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use NumberFormatter;

class Staff extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $fillable = [
        'user_id',
        'staff_no',
        'first_name',
        'last_name',
        'middle_name',
        'job_description',
        'department',
        'created_by'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function profile_pic($_data)
    {
        if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', $_data->user->name)) . '.jpg'))) {
            $_image = strtolower(str_replace(' ', '_', $_data->user->name)) . '.jpg';
        } else {
            $_image = 'avatar.png';
        }
        return '/assets/img/staff/' . $_image;
    }
    function profile_picture()
    {
        $profile_picture = $this->hasOne(StaffPictures::class, 'staff_id')->orderBy('id', 'desc')->where('is_removed', false)->first();
        $_image = asset('/assets/img/staff/avatar.png');
        if ($this->user) {
            if (file_exists(public_path('assets/img/staff/' . strtolower(str_replace(' ', '_', $this->user->name)) . '.jpg'))) {
                $_image = strtolower(str_replace(' ', '_', $this->user->name)) . '.jpg';
                $_image = asset('/assets/img/staff/' . $_image);
            }
            if ($profile_picture) {
                $_image =  Storage::url($profile_picture->image_path);;
            }
        }
        return  $_image;
    }

    public function subject_handles()
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')
            ->where('academic_id', Auth::user()->staff->current_academic()->id)
            ->where('is_removed', false);
    }
    public function subject_handles_v2($academic)
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')
            ->where('academic_id', base64_decode($academic))
            ->where('is_removed', false)->get();
    }
    public function grade_submission_midterm()
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')->with('midterm_grade_submission')
            ->where('subject_classes.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('subject_classes.is_removed', false);
    }
    public function grade_submission_finals()
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')->with('finals_grade_submission')
            ->where('subject_classes.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('subject_classes.is_removed', false);
    }
    // Grade Submission Version
    function grade_submission_v2($academic, $period)
    {
        $gradeSubmission = $period == 'midterm' ? 'midterm_grade_submission' : 'finals_grade_submission';
        return $this->hasMany(SubjectClass::class, 'staff_id')->with($gradeSubmission)
            ->where('subject_classes.academic_id', base64_decode($academic))
            ->where('subject_classes.is_removed', false)->get();
    }
    // Staff Attendance
    public function attendance()
    {
        return $this->hasMany(EmployeeAttendance::class, 'staff_id')->where('time_in', 'like', '%' . now()->format('Y-m-d') . '%')->latest();
    }
    public function daily_attendance()
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('time_in', 'like', '%' . now()->format('Y-m-d') . '%')->latest();
    }
    public function daily_attendance_report()
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('time_in', 'like', '%' . request()->input('_date') . '%')->latest();
    }
    function daily_time_in($date)
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('time_in', 'like', '%' . $date . '%')->orderBy('id', 'asc')->first();
    }
    function daily_time_out($date)
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('time_in', 'like', '%' . $date . '%')->orderBy('id', 'desc')->first();
    }
    function compute_late_per_day($arrivalTime)
    {
        // Scheduled arrival time
        $scheduledTime = strtotime('08:00:00');
        // Actual arrival time
        $actualTime = strtotime($arrivalTime);
        if ($scheduledTime <= $actualTime) {
            $latenessInSeconds = ($actualTime - $scheduledTime) / 60;
            return number_format($latenessInSeconds, 1);
        } else {
            return '-';
        }
    }
    function compute_tardines_per_day($arrivalTime)
    {
        $scheduledEndTime = strtotime('17:00:00');
        // Actual end time
        $actualEndTime = strtotime($arrivalTime);
        if ($scheduledEndTime >= $actualEndTime) {
            $undertimeInMinutes = ($scheduledEndTime - $actualEndTime) / 60;
            return number_format($undertimeInMinutes, 1);
        } else {
            return '-';
        }
    }
    public function attendance_list()
    {
        return $this->hasMany(EmployeeAttendance::class, 'staff_id');
    }
    public function date_attendance($date)
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('time_in', 'like', '%' . $date . '%')->orderBy('id', 'desc')->first();
    }
    public function current_academic()
    {
        $academic = AcademicYear::where('is_active', 1)->first();

        # Get the id of Teacher Role
        $role = Role::where('name', 'teacher')->first();
        $teacher = StaffDepartment::where('staff_id', $this->id)->where('role_id', $role->id)->first();
        if ($teacher) {
            $academic =  SubjectClass::select('academic_years.*')
                ->join('academic_years', 'academic_years.id', 'subject_classes.academic_id')
                ->where('subject_classes.staff_id', $this->id)->groupBy('subject_classes.academic_id')->orderBy('academic_years.id', 'desc')->first();
        }
        $role = Role::where('name', 'superadministrator')->first();
        $admin = StaffDepartment::where('staff_id', $this->id)->where('role_id', $role->id)->first();
        if ($admin) {
            $academic = AcademicYear::where('is_active', 1)->first();
        }
        if (request()->input('_academic')) {
            $academic = AcademicYear::find(base64_decode(request()->input('_academic')));
        }
        return $academic;
    }
    public function academics()
    {
        # Get the id of Teacher Role
        $role = Role::where('name', 'teacher')->first();
        $teacher = StaffDepartment::where('staff_id', $this->id)->where('role_id', $role->id)->first();
        $role = Role::where('name', 'superadministrator')->first();
        $admin = StaffDepartment::where('staff_id', $this->id)->where('role_id', $role->id)->first();
        $subjectClass = SubjectClass::select('academic_years.*')
            ->join('academic_years', 'academic_years.id', 'subject_classes.academic_id')
            ->where('subject_classes.staff_id', $this->id)->groupBy('subject_classes.academic_id')->orderBy('academic_years.id', 'desc')->get();
        $academicList =  AcademicYear::where('is_removed', false)->orderBy('id', 'Desc')->get();
        if ($teacher) {
            $academicList = $subjectClass;
        }
        if ($admin) {
            $academicList = AcademicYear::where('is_removed', false)->orderBy('id', 'Desc')->get();
        }
        return $academicList;
    }
    public function convert_year_level($_data)
    {
        $_level = $_data ==  11 ? 'Grade 11' : '';
        $_level = $_data ==  12 ? 'Grade 12' : $_level;
        $_level = $_data ==  1 ? '1st Class' : $_level;
        $_level = $_data ==  2 ? '2nd Class' : $_level;
        $_level = $_data ==  3 ? '3rd Class' : $_level;
        $_level = $_data ==  4 ? '4th Class' : $_level;
        return $_level;
    }
    public function registrar()
    {
        $_staff = Staff::where('job_description', 'DEPARTMENT HEAD')->where('department', 'REGISTRAR')->first();
        return  trim($_staff->first_name) . " " . trim($_staff->middle_name) . " " . trim($_staff->last_name);
    }
    public function department_head_signature($_department)
    {
        $_staff = Staff::where('job_description', 'DEPARTMENT HEAD')->where('department', $_department)->first();
        return $_staff->user->email;
    }
    public function academic_head($_course)
    {
        $_course = $_course == 1 ? 'BSMARE' : ($_course == 2 ? 'BSMT' : '');
        $_staff = Staff::where('job_description', 'DEPARTMENT HEAD')->where('department', $_course)->first();
        return trim($_staff->first_name) . " " . trim($_staff->middle_name) . " " . trim($_staff->last_name);
    }
    public function academic_head_signature($_course)
    {
        $_course = $_course == 1 ? 'BSMARE' : ($_course == 2 ? 'BSMT' : '');
        $_staff = Staff::where('job_description', 'DEPARTMENT HEAD')->where('department', $_course)->first();
        return $_staff->user->email;
    }
    public function dean_signature($_department)
    {
        $_staff = Staff::where('job_description', 'SCHOOL DIRECTOR')/* ->where('department', $_department) */->first();
        return $_staff->user->email;
    }

    public function enrollment_count()
    {
        return EnrollmentAssessment::select('enrollment_assessments.*')
            ->join('payment_assessments', 'enrollment_assessments.id', 'payment_assessments.enrollment_id')
            ->join('payment_transactions', 'payment_assessments.id', 'payment_transactions.assessment_id')
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('enrollment_assessments.is_removed', false)
            ->where('payment_transactions.is_removed', false)
            ->groupBy('enrollment_assessments.id')
            ->orderBy('payment_transactions.created_at', 'DESC')->get();
    }
    public function total_applicants()
    {
        $_course = CourseOffer::all();
        $_total = 0;
        foreach ($_course as $key => $value) {
            $_total += count($value->applicant_registrants);
        }
        return $_total;
    }
    public function side_bar_items()
    {
        return  [
            [
                'role_id' => 0,
                'role_name' => 'Employee',
                'role_icon' => 'icon-user',
                'role_routes' => [['Attendance', 'employee.attendance'], ['Profile', 'employee.change-password']],
            ],
            [
                'role_id' => 1,
                'role_name' => 'Administrator',
                'role_icon' => 'icon-job',
                'role_routes' => [['Dashboard', 'admin.dashboard'], ['Semestral Clearance', 'admin.semestral-clearance'], ['Students', 'admin.students'], ['Applicants', 'applicant.overview'], ['Accounts', 'admin.accounts'], ['Attendance', 'admin.attendance'], ['Subjects', 'admin.subjects'], ['Section', 'admin.sections'], ['Setting', 'admin.setting'], ['Ticketing', 'admin.ticket'], ['Examination', 'admin.examination'], ['Revision Task', 'admin.request-task']],
            ],
            [
                'role_id' => 2,
                'role_name' => 'Administrative',
                'role_icon' => 'icon-job',
                'role_routes' => [['Dashboard', 'administrative.dashboard'], ['Attendace', 'administrative.attendance'], ['Employees', 'administrative.employees']],
            ],
            [
                'role_id' => 3,
                'role_name' => 'Registrar',
                'role_icon' => 'icon-job',
                'role_routes' => [['Dashboard', 'registrar.dashboard'], /* ['Enrollment', 'registrar.enrollment'], */ ['Enrollment', 'enrollment.view-v2'], ['Semestral Grades', 'registrar.semestral-grades'], ['Students', 'registrar.students'], ['Applicants', 'applicant.overview'], ['Section', 'registrar.section-view'], ['Subjects', 'registrar.subject-view'], ['Semestral Clearance', 'registrar.semestral-clearance'], ['Scholarship Grants', 'registrar.scholarship-grants']],
            ],
            [

                'role_id' => 4,
                'role_name' => 'Accounting',
                'role_icon' => 'icon-job',
                'role_routes' => [['Dashboard', 'accounting.dashboard'], ['Assessment', 'accounting.assessments'], ['Assessment v2', 'accounting.assessments-v2'], ['Payment Transaction', 'accounting.payment-transactions-v2'], ['Fees', 'accounting.fees'], ['Particulars', 'accounting.particulars'], ['Semestral Clearance', 'accounting.semestral-clearance'], ['Applicant Payment', 'accounting.applicant-transaction'], ['Payroll', 'accounting.payroll-view'], ['Employee', 'accounting.employee-view'], ['Report', 'accounting.report'], ['Void Transaction', 'accounting.payment-void']],
            ],
            [
                'role_id' => 5,
                'role_name' => 'Onboard Training',
                'role_icon' => 'icon-job',
                'role_routes' => [['Dashboard', 'onboard.dashboard'], ['Midshipman', 'onboard.midshipman-v2'], ['MOPM', 'onboard.shipboard']],
            ],
            [
                'role_id' => 6,
                'role_name' => 'Teacher',
                'role_icon' => 'icon-job',
                'role_routes' => [['Subjects', 'teacher.subject-list'], ['Course Syllabus', 'teacher.course-syllabus']],
            ],

            [
                'role_id' => 7,
                'role_name' => 'Maintenance',
                'role_icon' => 'icon-job',
                'role_routes' => [['Dashboard', 'administrative.dashboard'], ['Enrollment', 'admin.enrollment']],
            ],
            [
                'role_id' => 8,
                'role_name' => 'Executive',
                'role_icon' => 'icon-job',
                'role_routes' => [['Dashboard', 'exo.dashboard'], ['Student', 'student.view'], ['Staff Attendance', 'exo.staff-attendance'], ['Student Onboarding', 'exo.student-onboarding'], ['Semestral Clearance', 'exo.semestral-clearance'], ['Qr Code Scanner', 'exo.qrcode-scanner']],
            ],
            [
                'role_id' => 9,
                'role_name' => 'Department Head',
                'role_icon' => 'icon-job',
                'role_routes' => [['Grade Submission', 'department-head.grade-submission'], ['E-Clearance', 'department-head.e-clearance']],
            ],
            [
                'role_id' => 10,
                'role_name' => 'Dean',
                'role_icon' => 'icon-job',
                'role_routes' => [['Grade Submission', 'dean.grade-submission'], ['E-Clearance', 'dean.e-clearance']],
            ],
            [
                'role_id' => 11,
                'role_name' => 'Librarian',
                'role_icon' => 'icon-job',
                'role_routes' => [['Semestral Clearance', 'librarian.semestral-clearance']],
            ],
            [
                'role_id' => 12,
                'role_name' => 'Medical',
                'role_icon' => 'icon-job',
                'role_routes' => [['Overview', 'medical.applicant-view'], ['Student', 'medical.student-view'], ['Medical Schedule', 'medical.appoitnment-schedule']],
            ],
        ];
    }
    public function navigation_dropdown_url()
    {
        $_route = route('registrar.enrollment');
        $_links = array(
            array('registrar/dashboard*', 'registrar.dashboard'),
            array('registrar/enrollment*', 'registrar.enrollment'),
            array('registrar/enrollment*', 'registrar.enrollment'),
            array('registrar/enrollment*', 'registrar.enrollment'),
            array('registrar/semestral-clearance*', 'registrar.semestral-clearance'),
            array('registrar/sections*', 'registrar.section-view'),
            array('registrar/subjects*', 'registrar.subject-view'),
            array('registrar/semestral-grade*', 'registrar.semestral-grades'),
            array('teacher/subjects*', 'teacher.subject-list'),
            array('department-head/grade-submission*', 'department-head.grade-submission'),
            array('department-head/semestral-clearance*', 'department-head.e-clearance'),
            array('dean/e-clearance*', 'dean.e-clearance'),
            array('dean/grading-verification*', 'dean.grade-submission'),
            array('accounting/payment-transaction*', 'accounting.payment-transaction'),
            array('accounting/assessment-fee*', 'accounting.payment-assessment'),
            array('accounting/particular/fee*', 'accounting.particular-fee-view'),
            array('accounting/fees*', 'accounting.fees'),
            array('accounting/semestral-clearance*', 'accounting.semestral-clearance'),
            array('executive/semestral-clearance*', 'exo.semestral-clearance'),
            array('librarian/semestral-clearance*', 'librarian.semestral-clearance'),
            array('administrator/semestral-clearance*', 'admin.semestral-clearance'),

            array('administrator/dashboard*', 'admin.dashboard'),
            array('dashboard*', 'admin.dashboard'),
            array('administrator/enrollment*', 'admin.dashboard'),
            array('medical/overview*', 'medical.overview'),
        );
        foreach ($_links as $key => $link) {
            $_route = request()->is($link[0]) ? route($link[1]) : $_route;
        }

        return $_route;
    }
    public function routes_navigation()
    {
        return  [
            'registrar/dashboard*',
            'registrar/enrollment*',
            'registrar/semestral-clearance*',
            'registrar/semestral-grade*',
            'registrar/sections*',
            'registrar/subjects*',
            'teacher/subjects*',
            'department-head/grade-submission*',
            'department-head/semestral-clearance*',
            'dean/e-clearance*', 'accounting/fees*',
            'accounting/dashboard*',
            'accounting/particular/fee*',
            'accounting/semestral-clearance*',
            'executive/semestral-clearance*',
            'librarian/semestral-clearance*',
            'administrator/semestral-clearance*',
            'dean/grading-verification*',
            'administrator/dashboard*',
            'administrator/enrollment*'
        ];
    }
    public function salary_details()
    {
        return $this->hasOne(StaffSalaryDetailes::class, 'staff_id')->where('is_removed', false);
    }
    public function message_ticket_concern()
    {
        $_department = Department::where('code', $this->department)->first();;

        if ($_department) {
            return $_issues =  TicketIssue::select('ticket_concerns.*')
                ->join('ticket_concerns', 'ticket_concerns.issue_id', 'ticket_issues.id')
                ->join('tickets', 'tickets.id', 'ticket_concerns.ticket_id')
                ->where('ticket_concerns.is_removed', false)
                ->where('ticket_concerns.is_ongoing', false)
                ->where('tickets.name', '!=', 'HenryScord')
                ->where('ticket_issues.department_id', $_department->id)
                /* ->where('ticket_issues.is_removed', false) */->orderBy('ticket_concerns.created_at', 'desc')->get();
        } else {
            return [];
        }
    }
    public function amount_to_words($_amount)
    {
        /* $digit = new NumberFormatter('en-US', NumberFormatter::CURRENCY);
        $_amount = $digit->formatCurrency($_amount, 'USD'); */
        return $this->numberToWords($_amount);
        $digit = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return strtoupper($digit->format($_amount));
    }
    public function numberToWords($_amount)
    {
        /* The above code is creating an array of numbers from 0 to 19, an array of tens from 0 to 90,
       and an array of hundreds. */
        $_amount = strval($_amount);
        $ones = array(0 => "ZERO", 1 => "ONE", 2 => "TWO", 3 => "THREE", 4 => "FOUR", 5 => "FIVE", 6 => "SIX", 7 => "SEVEN", 8 => "EIGHT", 9 => "NINE", 10 => "TEN", 11 => "ELEVEN", 12 => "TWELVE", 13 => "THIRTEEN", 14 => "FOURTEEN", 15 => "FIFTEEN", 16 => "SIXTEEN", 17 => "SEVENTEEN", 18 => "EIGHTEEN", 19 => "NINETEEN");
        $tens = array(0 => "ZERO", 1 => "TEN", 2 => "TWENTY", 3 => "THIRTY", 4 => "FORTY", 5 => "FIFTY", 6 => "SIXTY", 7 => "SEVENTY", 8 => "EIGHTY", 9 => "NINETY");
        $hundreds = array("HUNDRED", "THOUSAND", "MILLION", "BILLION", "TRILLION", "QUARDRILLION");
        $number_to_words = "";
        /* Formatting the number to 2 decimal places. */
        $_amount = number_format($_amount, 2, '.', ',');
        /* Taking the amount and splitting it into an array. */
        $_amount_array = explode('.', $_amount);
        /* Assigning the first element of the array to the variable . */
        $_whole_number  = $_amount_array[0];
        $_decimal_number = $_amount_array[1];
        /* Reversing the array and sorting it in reverse order. */
        $_number_array = array_reverse(explode(",", $_whole_number));
        /* Sorting the array in reverse order. */
        krsort($_number_array, 1);
        //return $_number_array;
        foreach ($_number_array as $key => $i) {
            $i = (float)$i;
            if ($i < 20) {
                // key is not equal to 0  and the value of if is 0 not return a words
                $number_to_words .=  $i != 0 ? $ones[$i] : '';
            } elseif ($i < 100) {
                if (substr($i, 0, 1) != 0) $number_to_words .= $tens[substr($i, 0, 1)];
                if (substr($i, 1, 1) != 0) $number_to_words .= " " . $ones[substr($i, 1, 1)];
            } else {
                if (substr($i, 0, 1) != 0) $number_to_words .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
                if (substr($i, 1, 1) != 0) $number_to_words .= " " . $tens[substr($i, 1, 1)];
                if (substr($i, 2, 1) != 0) $number_to_words .= " " . $ones[substr($i, 2, 1)];
            }
            if ($key > 0) {
                $number_to_words .= " " . $hundreds[$key] . " ";
            }
        }
        /* Checking if the decimal number is greater than 0, if it is, it will add the decimal number
        to the number_to_words variable. */
        if ($_decimal_number > 0)  $number_to_words .= " and " . $_decimal_number . " / 100 only";
        return $number_to_words;
    }
    public function curriculum_list()
    {
        return Curriculum::where('is_removed', false)->get();
    }
    public function medical_appointment_slot($date)
    {
        return ApplicantMedicalAppointment::where('appointment_date', $date)->where('is_removed', false)->count();
    }
    function roles()
    {
        return $this->hasMany(StaffDepartment::class)->where('is_removed', false);
    }
}
