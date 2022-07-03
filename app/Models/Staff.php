<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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
    public function subject_handles()
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')
            ->where('academic_id', Auth::user()->staff->current_academic()->id)
            ->where('is_removed', false);
    }
    public function grade_submission_midterm()
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')->with('midterm_grade_submission')
            ->where('subject_classes.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('subject_classes.is_removed', false);
        return $this->hasMany(SubjectClass::class, 'staff_id')
            ->leftJoin('grade_submissions as gs', 'gs.subject_class_id', 'subject_classes.id')
            ->where('gs.form', 'ad1')
            ->where('gs.period', 'midterm')
            /*  ->where('gs.is_approved',true) *//* ->orWhere('gs.is_approved','=','null') */
            ->with('midterm_grade_submission')
            ->where('subject_classes.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('subject_classes.is_removed', false);
    }
    public function grade_submission_finals()
    {
        return $this->hasMany(SubjectClass::class, 'staff_id')->with('finals_grade_submission')
            ->where('subject_classes.academic_id', Auth::user()->staff->current_academic()->id)
            ->where('subject_classes.is_removed', false);
    }
    // Staff Attendance
    public function attendance()
    {
        return $this->hasMany(EmployeeAttendance::class, 'staff_id')->where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->latest();
    }
    public function daily_attendance()
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('created_at', 'like', '%' . date('Y-m-d') . '%')->latest();
    }
    public function daily_attendance_report()
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('created_at', 'like', '%' . request()->input('_date') . '%')->latest();
    }
    public function attendance_list()
    {
        return $this->hasMany(EmployeeAttendance::class, 'staff_id');
    }
    public function date_attendance($_date)
    {
        return $this->hasOne(EmployeeAttendance::class, 'staff_id')->where('created_at', 'like', '%' . $_date . '%')->first();
    }
    public function current_academic()
    {
        $_academic = request()->input('_academic') ? AcademicYear::find(base64_decode(request()->input('_academic'))) : AcademicYear::where('is_active', 1)->first();
        return $_academic;
    }
    public function academics()
    {
        return AcademicYear::where('is_removed', false)->orderBy('id', 'Desc')->get();
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
        $_course = $_course == 1 ? 'MARINE ENGINEERING' : ($_course == 2 ? 'MARINE TRANSPORTATION' : '');
        $_staff = Staff::where('job_description', 'DEPARTMENT HEAD')->where('department', $_course)->first();
        return trim($_staff->first_name) . " " . trim($_staff->middle_name) . " " . trim($_staff->last_name);
    }
    public function academic_head_signature($_course)
    {
        $_course = $_course == 1 ? 'MARINE ENGINEERING' : ($_course == 2 ? 'MARINE TRANSPORTATION' : '');
        $_staff = Staff::where('job_description', 'DEPARTMENT HEAD')->where('department', $_course)->first();
        return $_staff->user->email;
    }
    public function dean_signature($_department)
    {
        $_staff = Staff::where('job_description', 'SCHOOL DIRECTOR')->where('department', $_department)->first();
        return $_staff->user->email;
    }

    public function enrollment_count()
    {
        return EnrollmentAssessment::join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->leftJoin('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment')
            ->where('enrollment_assessments.is_removed', false)
            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
            ->groupBy('pt.assessment_id')
            ->orderBy('pa.created_at', 'DESC')->get();
    }
    public function total_applicants()
    {
        $_course = CourseOffer::all();
        $_total = 0;
        foreach ($_course as $key => $value) {
            $_total += count($value->verified_applicants);
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
                'role_routes' => [['Dashboard', 'admin.dashboard'], ['Semestral Clearance', 'admin.semestral-clearance'], ['Students', 'admin.students'], ['Accounts', 'admin.accounts'], ['Attendance', 'admin.attendance'], ['Subjects', 'admin.subjects'], ['Section', 'admin.sections'], ['Setting', 'admin.setting'], ['Ticketing', 'admin.ticket'], ['Examination', 'admin.examination']],
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
                'role_routes' => [['Dashboard', 'registrar.dashboard'], ['Enrollment', 'registrar.enrollment'], ['Semestral Grades', 'registrar.semestral-grades'], ['Students', 'registrar.students'], ['Section', 'registrar.section-view'], ['Subjects', 'registrar.subject-view'], ['Semestral Clearance', 'registrar.semestral-clearance']],
            ],
            [
                'role_id' => 4,
                'role_name' => 'Accounting',
                'role_icon' => 'icon-job',
                'role_routes' => [['Dashboard', 'accounting.dashboard'], ['Assessment', 'accounting.assessments'], ['Payment Transaction', 'accounting.payment-transactions'], ['Fees', 'accounting.fees'], ['Particulars', 'accounting.particulars'], ['Semestral Clearance', 'accounting.semestral-clearance'], ['Applicant Payment', 'accounting.applicant-transaction'], ['Payroll', 'accounting.payroll-view'], ['Report', 'accounting.report']],
            ],
            [
                'role_id' => 5,
                'role_name' => 'Onboard Training',
                'role_icon' => 'icon-job',
                'role_routes' => [['Dashboard', 'onboard.dashboard'], ['Midshipman', 'onboard.midshipman'], ['Shipboard', 'onboard.shipboard']],
            ],
            [
                'role_id' => 6,
                'role_name' => 'Teacher',
                'role_icon' => 'icon-job',
                'role_routes' => [['Subjects', 'teacher.subject-list']],
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
                'role_routes' => [['Dashboard', 'exo.dashboard'], ['Staff Attendance', 'exo.staff-attendance'], ['Semestral Clearance', 'exo.semestral-clearance'], ['Qr Code Scanner', 'exo.qrcode-scanner']],
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
                'role_routes' => [['Overview', 'medical.overview']],
            ],
        ];
    }
    public function navigation_dropdown_url()
    {
        $_url = route('registrar.enrollment');
        $_url = request()->is('registrar/dashboard*') ? route('registrar.dashboard') : $_url;
        $_url = request()->is('registrar/semestral-clearance*') ? route('registrar.semestral-clearance') : $_url;
        $_url = request()->is('registrar/sections*') ? route('registrar.section-view') : $_url;
        $_url = request()->is('registrar/subjects*') ? route('registrar.subject-view') : $_url;
        $_url = request()->is('teacher/subjects*') ? route('teacher.subject-list') : $_url;
        $_url = request()->is('department-head/grade-submission*') ? route('department-head.grade-submission') : $_url;
        $_url = request()->is('department-head/semestral-clearance*') ? route('department-head.e-clearance') : $_url;
        $_url = request()->is('dean/e-clearance*') ? route('dean.e-clearance') : $_url;
        $_url = request()->is('dean/grading-verification*') ? route('dean.grade-submission') : $_url;
        $_url = request()->is('accounting/particular/fee*') ? route('accounting.particular-fee-view') : $_url;
        $_url = request()->is('accounting/fees*') ? route('accounting.fees') : $_url;
        $_url = request()->is('accounting/semestral-clearance*') ? route('accounting.semestral-clearance') : $_url;
        $_url = request()->is('executive/semestral-clearance*') ? route('exo.semestral-clearance') : $_url;
        $_url = request()->is('librarian/semestral-clearance*') ? route('librarian.semestral-clearance') : $_url;
        $_url = request()->is('administrator/semestral-clearance*') ? route('admin.semestral-clearance') : $_url;
        $_url = request()->is('registrar/semestral-grade*') ? route('registrar.semestral-grades') : $_url;
        $_url = request()->is('administrator/dashboard*') ? route('admin.dashboard') : $_url;
        $_url = request()->is('dashboard*') ? route('admin.dashboard') : $_url;
        $_url = request()->is('administrator/enrollment*') ? route('admin.dashboard') : $_url;
        $_url = request()->is('medical/overview*') ? route('medical.overview') : $_url;
        return $_url;
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
        return $_issues =  TicketIssue::select('ticket_concerns.*')
            ->join('ticket_concerns', 'ticket_concerns.issue_id', 'ticket_issues.id')
            ->join('tickets', 'tickets.id', 'ticket_concerns.ticket_id')
            ->where('ticket_concerns.is_removed', false)
            ->where('ticket_concerns.is_ongoing', false)
            ->where('tickets.name', '!=', 'HenryScord')
            ->where('ticket_issues.department_id', $_department->id)
            /* ->where('ticket_issues.is_removed', false) */->orderBy('ticket_concerns.created_at', 'desc')->get();
    }
}
