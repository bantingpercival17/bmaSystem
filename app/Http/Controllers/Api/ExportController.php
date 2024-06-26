<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\User;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function export_course()
    {
        $course = CourseOffer::all();
        return response(['data' => $course], 200);
    }
    public function export_staff()
    {
        $staff = User::with('staff')->get();
        return response(['data' => $staff], 200);
    }
    public function export_academic_year()
    {
        $data = AcademicYear::all();
        return response(['data' => $data], 200);
    }
}
