<?php

namespace App\Http\Controllers;

use App\Imports\StaffImport;
use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\CurriculumSubject;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;

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
            Excel::import(new StaffImport, $_request->file('_file_users'));
            return back()->with('message', "Successfully Setup the Users");
        }
    }
    public function import_course($_files)
    {
        foreach ($_files as $_data) {
            CourseOffer::create(['course_name' => $_data->course_name, 'course_code' => $_data->course_code, 'school_level' => $_data->school_level, 'is_removed' => 0]);
        }
        //CourseOffer::create($_files);
        return back()->with('message', "Successfully Setup the Course Offers");
    }
    public function import_academic($_files)
    {
        foreach ($_files as $key => $file) {
            AcademicYear::create(['school_year' => $file->school_year, 'semester' => $file->semester, 'is_active' => $file->is_active, 'created_by' => 'Percival Banting', 'is_removed' => 0]);
        }
        return back()->with('message', "Successfully Setup the Academic Year");
    }
    public function import_curriculum($_files)
    {
        foreach ($_files as $key => $file) {
            Curriculum::create(['curriculum_name' => $file->curriculum_name, 'curriculum_year' => $file->curriculum_year, 'created_by' => 'Percival Banting', 'is_removed' => 0]);
        }
        return back()->with('message', "Successfully Setup the Curriculum");
    }
    public function import_subject($_files)
    {

        foreach ($_files as $_file) {
            $_subject = array(
                'subject_code' => $_file->subject_code,
                'subject_name' => $_file->subject_name,
                'units' => $_file->units, 'lecture_hours' => $_file->lecture_hours, 'laboratory_hours' => $_file->laboratory_hours,
                'created_by' => $_file->created_by, 'is_removed' => 0
            );
            $_subject = Subject::create($_subject);
            echo $_file->subject_code . "<br>";
            $_cs = [];
            foreach ($_file->curriculum_subjects as $_curriculum_subject) {
                $_cs = array(
                    'curriculum_id' => $_curriculum_subject->curriculum_id,
                    'subject_id' => $_subject->id,
                    'course_id' => $_curriculum_subject->course_id,
                    'year_level' => $_curriculum_subject->year_level,
                    'semester' => $_curriculum_subject->semester,
                    'created_by' => $_curriculum_subject->created_by, 'is_removed' => 0
                );
                CurriculumSubject::create($_cs);
                echo "      -   " . $_curriculum_subject->curriculum_id .
                    'subject_id' . $_subject->id .
                    'course_id' . $_curriculum_subject->course_id .
                    'year_level' . $_curriculum_subject->year_level .
                    'semester' . $_curriculum_subject->semester . "<br>";
            }
            echo "<br>";
        }
        return back()->with('message', "Successfully Setup the Curriculum Subjects");
    }
    public function debugTracker()
    {
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
        $filename =  $_student_number . '/' . $_folder . '/' . time() . '.' . $_file->getClientOriginalExtension();
        // File Path Format : $_path.'/'.student-number.'/'.$_folder
        $_path = $_path;
        Storage::disk($_path)->put($filename, fopen($_file, 'r+'));
        return URL::to('/') . '/storage/' . $_path . '/' . $filename;
    }
}
