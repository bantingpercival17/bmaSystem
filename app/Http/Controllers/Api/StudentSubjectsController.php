<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EnrollmentAssessment;
use App\Models\Subject;
use App\Models\SubjectClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentSubjectsController extends Controller
{
    function subject_lists(Request $request)
    {
        try {
            $query = EnrollmentAssessment::select('enrollment_assessments.*')
                ->with('academic')
                ->with('course')
                ->with('curriculum')
                ->where('student_id', Auth::user()->student_id)
                ->where('enrollment_assessments.is_removed', false)
                ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
                ->where('payment_assessments.is_removed', false)
                ->join('payment_transactions', 'payment_transactions.assessment_id', 'payment_assessments.id')
                ->where('payment_transactions.is_removed', false)->orderBy('enrollment_assessments.id', 'desc');

            if ($request->key) {
                $enrollment = EnrollmentAssessment::select('enrollment_assessments.*')
                    ->with('academic')
                    ->with('course')
                    ->with('curriculum')
                    ->find(base64_decode($request->key));

                if ($enrollment->student_id !== Auth::user()->student_id) {
                    return response(['status' => '404', 'message' => 'Invalid Account'], 200);
                }
            } else {
                // Get First the Current and Paid Enrollment Assessment
                $enrollment = $query->first();
            }
            $section =  $enrollment->student_section;
            return response(['data' => $section], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function subject_view(Request $request)
    {
        try {
            // Get the Subject
            $subject = SubjectClass::find(base64_decode($request->subject));
            $subject->curriculum_subjects;
            $lesson =  $subject->course_syllabus;
            $scheduled = $subject->class_schedule;
            
            return response(compact('subject'), 200);
        } catch (\Throwable $error) {
            $this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
}
