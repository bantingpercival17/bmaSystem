<?php

namespace App\Http\Controllers\GeneralController;

use App\Http\Controllers\Controller;
use App\Models\CourseSyllabus;
use App\Models\Subject;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseSyllabusController extends Controller
{
    #Teacher Portal 
    public function course_syllabus_view(Request $_request)
    {
        try {
            return view('pages.teacher.course-syllabus.view');
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function course_syllabus_create(Request $_request)
    {
        try {
            $_subject = $_request->_subject ? Subject::find(base64_decode($_request->_subject)) : [];
            $_subjects = Subject::where('is_removed', false)->orderBy('subject_code')->get();
            return view('pages.teacher.course-syllabus.create-syllabus', compact('_subjects', '_subject'));
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }

    public function course_syllabus_store(Request $_request)
    {

        try {
            $_data = array(
                'subject_id' => $_request->subject,
                'course_id' => $_request->course,
                'course_description' => $_request->course_description ?: 'N/A',
                'prerequisite' => $_request->prerequisite,
                'corequisite' => $_request->co_requisite,
                'semester' => $_request->semester,
                'creator_id' => Auth::user()->staff->id
            );
            $_course_syllabus = CourseSyllabus::create($_data);
            return redirect(route('teacher.course-syllabus-editor') . '?course_syllabus' . base64_encode($_course_syllabus->id))->with('success', 'Successfuly Created');
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function course_syllabus_editor(Request $_request)
    {
        try {
           return $_course_syllabus = CourseSyllabus::find(base64_decode($_request->course_syllabus));
            return view('pages.teacher.course-syllabus.editor-syllabus', compact($_course_syllabus));
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
}
