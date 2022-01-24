<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CourseOffer;
use App\Models\Section;
use App\Models\StudentNonAcademicClearance;
use App\Models\StudentSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('dean');
    }
    public function dashboard(Request $_request)
    {
        $_course = CourseOffer::where('course_code', '!=', 'pbm')->get();
        return view('pages/dean/grade-book/view', compact('_course'));
    }

    public function e_clearance_view(Request $_request)
    {
        $_course = CourseOffer::where('course_code', '!=', 'pbm')->get();
        return view('pages/dean/clearance/view', compact('_course'));
    }
    public function e_clearance_section_view(Request $_request)
    {
        $_section = Section::find(base64_decode($_request->_section));
        $_students = $_section->student_section;
        return view('pages/dean/clearance/section_view', compact('_section', '_students'));
    }
    public function e_clearance_section_store(Request $_request)
    {
        if (is_array($_request->dean)) {
            foreach ($_request->dean as $key => $value) {
                $_checker = StudentNonAcademicClearance::where(['student_id' => $value, 'non_academic_type' => 'dean', 'is_approved' =>  1,])->first();
                $_data = array(
                    'student_id' => $value,
                    'non_academic_type' => 'dean',
                    'staff_id' => Auth::user()->staff->id,
                    'is_approved' =>  1, // nullable
                    'is_removed' => 0
                );
                $_checker ? '' : StudentNonAcademicClearance::create($_data);
                //echo $value . "<br>";
            }
        }
        return back()->with('success', 'Successfully Submitted.');
    }
}
