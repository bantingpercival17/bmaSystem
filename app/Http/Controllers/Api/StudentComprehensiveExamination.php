<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ComprehensiveExamination;
use Illuminate\Http\Request;

class StudentComprehensiveExamination extends Controller
{
    function comprehensive_examination()
    {
        try {
            $user = auth()->user();
            $course = $user->student->enrollment_assessment;
            $examination = ComprehensiveExamination::select('id', 'competence_code', 'competence_name', 'file_name')->where('course_id', $course->course_id)->get();
            return response(compact('examination'), 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function examination_view(Request $request)
    {
        try {
            $examination = ComprehensiveExamination::find(base64_decode($request->id));
            $examination_list = ComprehensiveExamination::select('id', 'competence_code', 'competence_name', 'file_name')->where('course_id', $examination->course_id)->get();
            return response(compact('examination', 'examination_list'));
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    /*  function retrive_comprehensive_examination(Request $request)
    {
        try {
            $comprehensiveList = ComprehensiveExamination::where('course_id', $request->course)->where('is_removed', false)->get();
            return response(compact('comprehensiveList'), 200);
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], 503);
        }
    }
    function comprehensive_examination(Request $request)
    {
        try {
            $comprehensive = ComprehensiveExamination::find($request->id);
            return response(compact('comprehensive'), 200);
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], 503);
        }
    } */
}
