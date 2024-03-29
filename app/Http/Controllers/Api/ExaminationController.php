<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use App\Models\ExaminationCategory;
use Illuminate\Http\Request;

class ExaminationController extends Controller
{
    function review_examination()
    {
        try {
            $examination = Examination::where('examination_name', 'ENTRANCE EXAMINATION')->with('category_lists')->first();
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
}
