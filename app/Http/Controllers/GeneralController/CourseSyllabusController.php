<?php

namespace App\Http\Controllers\GeneralController;

use App\Http\Controllers\Controller;
use App\Models\CourseSyllabus;
use App\Models\Subject;
use App\Models\SyllabusCourseDetails;
use App\Models\SyllabusCourseOutcome;
use App\Models\SyllabusStcwCompetence;
use App\Models\SyllabusStcwFunction;
use App\Models\SyllabusStcwKup;
use App\Models\SyllabusStcwReference;
use App\Report\CourseSyllabusReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class CourseSyllabusController extends Controller
{
    #Teacher Portal 
    public function course_syllabus_view(Request $_request)
    {
        try {
            $_syllabus = CourseSyllabus::where('creator_id', Auth::user()->staff->id)->where('is_removed', false)->get();
            return view('pages.teacher.course-syllabus.view', compact('_syllabus'));
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
            return redirect(route('teacher.course-syllabus-editor') . '?course_syllabus=' . base64_encode($_course_syllabus->id))->with('success', 'Successfuly Created');
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function course_syllabus_editor(Request $_request)
    {
        try {
            $_course_syllabus = CourseSyllabus::find(base64_decode($_request->course_syllabus));
            if ($_request->part) {
                return view('pages.teacher.course-syllabus.editor-syllabus-part', compact('_course_syllabus'));
            } else {
                return view('pages.teacher.course-syllabus.editor-syllabus', compact('_course_syllabus'));
            }
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function course_syllabus_report(Request $_request)
    {
        try {
            $_course_syllabus = CourseSyllabus::find(base64_decode($_request->_course_syllabus));
            $_report = new CourseSyllabusReport();
            return $_report->part_one($_course_syllabus);
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function course_syllabus_remove(Request $_request)
    {
        try {
            $_course_syllabus = CourseSyllabus::find(base64_decode($_request->course_syllabus));
            $_course_syllabus->is_removed = 1;
            $_course_syllabus->save();
            return back()->with('success', 'Successfuly Removed');
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }

    #Store STCW
    public function store_stcw_reference(Request $_request)
    {
        try {
            //CourseSyllabusStcwReference
            $_data = array(
                'course_syllabus_id' => $_request->syllabus,
                'stcw_table' => $_request->stcw_table ?: 'N/A',
            );
            $_stcw = SyllabusStcwReference::create($_data);
            //StcwFunction
            $_function_content = array(
                'stcw_reference_id' => $_stcw->id,
                'function_content' => $_request->function ?: 'N/A'
            );
            $_function = SyllabusStcwFunction::create($_function_content);
            //StcwCompentence
            $_competence_content = array(
                'stcw_function_id' => $_function->id,
                'competence_content' => $_request->competence ?: 'N/A'
            );
            $_competence = SyllabusStcwCompetence::create($_competence_content);
            //StcwKup
            $_kup_content = array(
                'stcw_competence_id' => $_competence->id,
                'kup_content' => $_request->kup ?: 'N/A'
            );
            SyllabusStcwKup::create($_kup_content);
            return back()->with('success', 'Successfully Create STCW Reference!');
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function remove_stcw_reference(Request $_request)
    {
        try {
            if ($_request->stcw_reference == 'stcw-table') {
                $_data = SyllabusStcwReference::find(base64_decode($_request->stcw));
                $_data->is_removed = true;
                $_data->save();
                return back()->with('success', 'Successfully Removed STCW REFERENCE TABLE!');
            }
            if ($_request->stcw_reference == 'function') {
                $_data = SyllabusStcwFunction::find(base64_decode($_request->stcw));
                $_data->is_removed = true;
                $_data->save();
                return back()->with('success', 'Successfully Removed STCW REFERENCE FUNCTION!');
            }
            if ($_request->stcw_reference == 'competence') {
                $_data = SyllabusStcwCompetence::find(base64_decode($_request->stcw));
                $_data->is_removed = true;
                $_data->save();
                return back()->with('success', 'Successfully Removed STCW REFERENCE COMPETENCE!');
            }
            if ($_request->stcw_reference == 'kup') {
                $_data = SyllabusStcwKup::find(base64_decode($_request->stcw));
                $_data->is_removed = true;
                $_data->save();
                return back()->with('success', 'Successfully Removed STCW REFERENCE KUP!');
            }
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function add_stcw_reference(Request $_request)
    {
        try {
            if ($_request->stcw_reference == 'function') {
                $_content = array(
                    'stcw_reference_id' => base64_decode($_request->stcw),
                    'function_content' => $_request->content ?: 'N/A'
                );
                $_function = SyllabusStcwFunction::create($_content);
                return back()->with('success', 'Successfully Added to STCW REFERENCE FUNCTION!');
            }
            if ($_request->stcw_reference == 'competence') {

                $_content = array(
                    'stcw_function_id' => base64_decode($_request->stcw),
                    'competence_content' => $_request->content ?: 'N/A'
                );
                $_competence = SyllabusStcwCompetence::create($_content);
                return back()->with('success', 'Successfully Added to STCW REFERENCE COMPETENCE!');
            }
            if ($_request->stcw_reference == 'kup') {
                $_kup_content = array(
                    'stcw_competence_id' =>  base64_decode($_request->stcw),
                    'kup_content' => $_request->content ?: 'N/A'
                );
                SyllabusStcwKup::create($_kup_content);
                return back()->with('success', 'Successfully Added to STCW REFERENCE KUP!');
            }
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    # SYLLABUS COURSE OUTCOME
    public function store_course_outcome(Request $_request)
    {
        try {
            $_content = array(
                'course_syllabus_id' => base64_decode($_request->_syllabus),
                'program_outcome' => $_request->_program_outcome,
                'course_outcome' => $_request->_course_outcome
            );
            SyllabusCourseOutcome::create($_content);
            return back()->with('success', 'Successfully Add Course Outcome');
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    # SYLLABUS COURSE DETAILS
    public function store_course_details(Request $_request)
    {
        try {
            $_content = array(
                'course_syllabus_id' => base64_decode($_request->_syllabus),
                'course_intake_limitations' => $_request->course_limitations ?: "N/A",
                'faculty_requirements' => $_request->faculty_requirements ?: "N/A",
                'teaching_facilities_and_equipment' => $_request->teaching_facilities ?: "N/A",
                'teaching_aids' => $_request->teaching_aids ? json_encode(explode(PHP_EOL, trim($_request->teaching_aids))) : "N/A",
                'references' => $_request->references ? json_encode(explode(PHP_EOL, trim($_request->references))) : "N/A"
            );
            //dd($_content);
            if ($_request->_details) {
                SyllabusCourseDetails::where('id', $_request->_details)->update($_content);
                return back()->with('success', 'Successfully Update the Additional Details');
            } else {
                SyllabusCourseDetails::create($_content);
                return back()->with('success', 'Successfully Add the Additional Details');
            }
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function store_course_learning_outline(Request $_request)
    {
        try {
            $_content = array(
                'course_syllabus_id' => base64_decode($_request->_syllabus),
                'course_outcome_id' => base64_decode($_request->_course_outcome),
                'learning_outcomes' => $_request->_learning_outcome,
                'theoretical' => $_request->_theoretical,
                'demonstration' => $_request->_demostration,
                'weeks' => $_request->_weeks,
                'references' => $_request->references,
                'teaching_aids' => $_request->_teaching_aids,
                'term' => $_request->_term
            );
            return dd($_content);
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
}
