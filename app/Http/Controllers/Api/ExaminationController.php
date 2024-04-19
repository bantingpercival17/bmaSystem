<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\ExaminationCategory;
use App\Models\ThirdDatabase\StudentReviewerScore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExaminationController extends Controller
{
    function review_examination()
    {
        try {
            $examination = Examination::where('examination_name',  'REVIEW EXAMINATION')->where('department','college')->with('category_lists')->first();
            return response(compact('examination'), 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function review_examination_view(Request $request)
    {
        try {
            $category = ExaminationCategory::with('question_list_with_answer')->find($request->category);
            return response(compact('category'), 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function review_examination_score(Request $request)
    {
        try {
            $data = User::find($request->id);
            $data = array(
                'student_id' => $request->student_id,
                'score' => $request->score,
                'examination_id' => $request->examination,
                'category_id' => $request->category
            );
            StudentReviewerScore::create($data);
            return response(['data' => 'Success'], 200);
        } catch (\Throwable $th) {
            return response(['message' => $th->getMessage()], 500);
        }
    }
}
