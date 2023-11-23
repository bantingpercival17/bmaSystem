<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\Request;

class ApplicantEnrollmentController extends Controller
{
    function enrollment_overview(Request $request)
    {
        try {
            // Enrollment Procudure
            $semester = AcademicYear::where('semester', 'First Semester')->orderBy('id', 'decs')->first();
            return $student = auth()->user()->student_details;
            /*     $application = auth()->user()->student->student_enrollment_application;
            $academic = AcademicYear::where('is_active', true)->first();
            $medical_result = auth()->user()->student->prev_enrollment_assessment->medical_result; */
            // Tuition Fee Assessment
            $enrollment_assessment = auth()->user()->student->current_enrollment;
        } catch (\Throwable $th) {
            $this->debugTrackerApplicant($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
