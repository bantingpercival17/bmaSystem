<?php

namespace App\Http\Controllers;

use App\Imports\StaffImport;
use App\Mail\ExecutiveReportMail;
use App\Mail\OnboardingScheduleEmail;
use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\DebugReport;
use App\Models\Subject;
use App\Models\User;
use App\Models\DubegReport;
use App\Models\Section;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    public function index()
    {
        $_academic = AcademicYear::all();
        $_course = CourseOffer::all();
        $_curriculum = Curriculum::all();
        $_subject = CurriculumSubject::all();
        $_user = User::all();
        if ($_academic->count() > 0 && $_course->count() > 0 && $_curriculum->count() > 0 && $_subject->count() > 0 && $_user->count() > 0) {
            return redirect('/login');
        } else {
            return redirect('/setup');
        }
    }
    public function setup()
    {
        $_academic = AcademicYear::all();
        $_course = CourseOffer::all();
        $_curriculum = Curriculum::all();
        $_subject = CurriculumSubject::all();
        $_user = User::all();
        return view('setup', compact('_course', '_academic', '_curriculum', '_subject', '_user'));
    }
    public function setup_store(Request $_request)
    {
        if ($_request->_file_course) {
            $_files = json_decode(file_get_contents($_request->file('_file_course')));
            return $this->import_course($_files);
        }
        if ($_request->_file_academic) {
            $_files = json_decode(file_get_contents($_request->file('_file_academic')));
            return $this->import_academic($_files);
        }
        if ($_request->_file_curriculum) {
            $_files = json_decode(file_get_contents($_request->file('_file_curriculum')));
            return $this->import_curriculum($_files);
        }
        if ($_request->_file_subjects) {
            $_files = json_decode(file_get_contents($_request->file('_file_subjects')));
            return $this->import_subject($_files);
        }
        if ($_request->_file_users) {
            Excel::import(new StaffImport(), $_request->file('_file_users'));
            return back()->with('message', 'Successfully Setup the Users');
        }
    }
    public function import_course($_files)
    {
        foreach ($_files as $_data) {
            CourseOffer::create(['course_name' => $_data->course_name, 'course_code' => $_data->course_code, 'school_level' => $_data->school_level, 'is_removed' => 0]);
        }
        //CourseOffer::create($_files);
        return back()->with('message', 'Successfully Setup the Course Offers');
    }
    public function import_academic($_files)
    {
        foreach ($_files as $key => $file) {
            AcademicYear::create(['school_year' => $file->school_year, 'semester' => $file->semester, 'is_active' => $file->is_active, 'created_by' => 'Percival Banting', 'is_removed' => 0]);
        }
        return back()->with('message', 'Successfully Setup the Academic Year');
    }
    public function import_curriculum($_files)
    {
        foreach ($_files as $key => $file) {
            Curriculum::create(['curriculum_name' => $file->curriculum_name, 'curriculum_year' => $file->curriculum_year, 'created_by' => 'Percival Banting', 'is_removed' => 0]);
        }
        return back()->with('message', 'Successfully Setup the Curriculum');
    }
    public function import_subject($_files)
    {
        foreach ($_files as $_file) {
            $_subject = [
                'subject_code' => $_file->subject_code,
                'subject_name' => $_file->subject_name,
                'units' => $_file->units,
                'lecture_hours' => $_file->lecture_hours,
                'laboratory_hours' => $_file->laboratory_hours,
                'created_by' => $_file->created_by,
                'is_removed' => 0,
            ];
            $_subject = Subject::create($_subject);
            echo $_file->subject_code . '<br>';
            $_cs = [];
            foreach ($_file->curriculum_subjects as $_curriculum_subject) {
                $_cs = [
                    'curriculum_id' => $_curriculum_subject->curriculum_id,
                    'subject_id' => $_subject->id,
                    'course_id' => $_curriculum_subject->course_id,
                    'year_level' => $_curriculum_subject->year_level,
                    'semester' => $_curriculum_subject->semester,
                    'created_by' => $_curriculum_subject->created_by,
                    'is_removed' => 0,
                ];
                CurriculumSubject::create($_cs);
                echo '      -   ' . $_curriculum_subject->curriculum_id . 'subject_id' . $_subject->id . 'course_id' . $_curriculum_subject->course_id . 'year_level' . $_curriculum_subject->year_level . 'semester' . $_curriculum_subject->semester . '<br>';
            }
            echo '<br>';
        }
        return back()->with('message', 'Successfully Setup the Curriculum Subjects');
    }
    public function debugTracker($error)
    {
        $_current_url = sprintf('%s://%s/%s', isset($_SERVER['HTTPS']) ? 'https' : 'http', $_SERVER['HTTP_HOST'], trim($_SERVER['REQUEST_URI'], '/\\'));

        $_data = [
            'type_of_user' => 'employee',
            'user_name' => Auth::user()->name,
            'user_ip_address' => $_SERVER['REMOTE_ADDR'] . ', ' . $_SERVER['HTTP_USER_AGENT'],
            'error_message' => $error->getMessage(),
            'url_error' => $_current_url,
            'is_status' => 0,
        ];
        if (!DebugReport::where($_data)->first()) {
            DebugReport::create($_data);
        }

        // User
        // IP Address
        // Device
        // Date / Time
    }
    public function debugTrackerStudent($error)
    {
        $_current_url = sprintf('%s://%s/%s', isset($_SERVER['HTTPS']) ? 'https' : 'http', $_SERVER['HTTP_HOST'], trim($_SERVER['REQUEST_URI'], '/\\'));

        $_data = [
            'type_of_user' => 'student',
            'user_name' => auth()->user(),
            'user_ip_address' => $_SERVER['REMOTE_ADDR'] . ', ' . $_SERVER['HTTP_USER_AGENT'],
            'error_message' => $error->getMessage(),
            'url_error' => $_current_url,
            'is_status' => 0,
        ];
        if (!DebugReport::where($_data)->first()) {
            DebugReport::create($_data);
        }

        // User
        // IP Address
        // Device
        // Date / Time
    }
    public function saveFiles($_file, $_path = 'public', $_folder = 'extra')
    {
        if (!$_file) {
            return null;
        }
        // Get Student Number
        $_student_number = Auth::user() ? str_replace('@bma.edu.ph', '', trim(Auth::user()->email)) : str_replace('@gmail.com', '', trim(Auth::user()->personal_email));
        // Get the extention of files
        $filename = $_student_number . '/' . $_folder . '/' . time() . '.' . $_file->getClientOriginalExtension();
        // File Path Format : $_path.'/'.student-number.'/'.$_folder
        $_path = $_path;
        Storage::disk($_path)->put($filename, fopen($_file, 'r+'));
        return URL::to('/') . '/storage/' . $_path . '/' . $filename;
    }
    public function send_email()
    {
        $_academic = AcademicYear::where('is_active', true)->first();
        $_deck = Section::where('course_id', 2)
            ->where('academic_id', $_academic->id)
            ->where('is_removed', false)->get();
        $_engine = Section::where('course_id', 1)
            ->where('academic_id', $_academic->id)
            ->where('is_removed', false)->get();
        // Additional Data
        $_time_arrival = array(
            array('year_level' => 4, 'time_arrival' => 1730),
            array('year_level' => 3, 'time_arrival' => 1800),
            array('year_level' => 2, 'time_arrival' => 1830),
            array('year_level' => 1, 'time_arrival' => 1900)
        );
        $_absent_on_deck = Section::where('course_id', 2)
            ->where('is_removed', false)
            ->where('academic_id', $_academic->id)->orderBy('year_level', 'desc')->get();
        $_absent_on_engine = Section::where('course_id', 1)
            ->where('is_removed', false)
            ->where('academic_id', $_academic->id)->orderBy('year_level', 'desc')->get();
        /*  $_sections = $_absent_on_deck; */
        /*  $_layout =  'widgets.report.executive.onboarding-absent-report';
        // Import PDF Class
        $pdf = PDF::loadView($_layout, compact('_sections'));
        // Set the Filename of report
        $file_name =  ' - LIST OF MIDSHIPMAN ABSENT - ' . date('Ymd') . '.pdf';
        $pdf->setPaper([0, 0, 612.00, 1008.00], 'portrait'); // Set the Paper sizw
        $file_name = 'executive/report/absent/' . $file_name . ' - ' . date('Ymd') . '.pdf'; // File name
        Storage::disk('public')->put($file_name, $pdf->output()); // Store to Local folder
        return $pdf->stream(); */
        $mail = new ExecutiveReportMail($_deck, $_engine, $_time_arrival, $_absent_on_deck, $_absent_on_engine);
        Mail::to('p.banting@bma.edu.ph')->send($mail); // Testing Email
        /*   $other_email = ['qmr@bma.edu.ph', 'ict@bma.edu.ph', 'exo@bma.edu.ph'];
        Mail::to('report@bma.edu.ph')->bcc($other_email)->send($mail); // Offical Emails */
        return "Sent";
    }
}
