<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Examination;
use Illuminate\Http\Request;

class ExaminationController extends Controller
{
    function review_examination()
    {
        try {
            $examination = Examination::where('examination_name', 'ONBOARD EXAMINATION BSMT')->with('category_lists')->first();
            return response(compact('examination'), 200);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
