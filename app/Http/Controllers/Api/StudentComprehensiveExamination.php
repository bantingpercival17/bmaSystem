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
            return response(compact('examination'));
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
}
