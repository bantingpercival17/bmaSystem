<?php

namespace App\Http\Controllers;

use App\Exports\CourseApplicantMedicalList;
use App\Exports\CourseStudentMedicalList;
use App\Exports\WorkBook\MedicalMonitoringBook;
use App\Models\ApplicantAccount;
use App\Models\CourseOffer;
use App\Models\MedicalAppointmentSchedule;
use App\Models\StudentDetails;
use App\Models\StudentMedicalAppointment;
use App\Models\StudentMedicalResult;
use App\Report\MedicalReport;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class MedicalController extends Controller
{
    public function student_medical_appointment(Request $_request)
    {
        try {
            StudentMedicalAppointment::all();
            $_courses = CourseOffer::all();
            $_applicants = [];
            $_table_content = array(
                array('scheduled', 'student_medical_scheduled'),
                array('waiting for result', 'student_medical_waiting_for_result'),
                array('passed', 'student_medical_passed'),
                array('pending', 'student_medical_pending'),
                array('failed', 'student_medical_failed')
            );
            if ($_request->_course) {
                $_course = CourseOffer::find(base64_decode($_request->_course));
                foreach ($_table_content as $key => $content) {
                    $_applicants = $_request->view == $content[0] ? $_course[$content[1]] : $_applicants;
                }
            }
            return view('pages.medical.view', compact('_courses', '_applicants', '_table_content'));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            $this->debugTracker($error);
        }
    }

    public function student_medical_appointment_approved(Request $_request)
    {
        try {
            try {
                $_appointment = StudentMedicalAppointment::find(base64_decode($_request->appointment));
                $_appointment->is_approved = 1;
                $_appointment->save();
                return back()->with('success', 'Appointment Approved');
            } catch (Exception $error) {
                return back()->with('error', $error->getMessage());
            }
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            $this->debugTracker($error);
        }
    }
    public function student_medical_result(Request $_request)
    {
        try {
            $_student = StudentDetails::find(base64_decode($_request->student));
            if ($_request->result) {
                $_details = array('student_id' => base64_decode($_request->student), 'is_fit' => base64_decode($_request->result), 'remarks' => $_request->remarks, 'staff_id' => Auth::user()->staff->id);
            } else {
                $_details = array('student_id' => base64_decode($_request->student), 'is_pending' => 0, 'remarks' => $_request->remarks, 'staff_id' => Auth::user()->staff->id);
            }
            $_medical_result = StudentMedicalResult::where('student_id', $_student->id)->where('is_removed', false)->first();
            if ($_medical_result) {
                $_medical_result->is_removed = true;
                $_medical_result->save();
                StudentMedicalResult::create($_details);
            } else {
                StudentMedicalResult::create($_details);
            }
            return back()->with('success', 'Successfully Transact');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            $this->debugTracker($error);
        }
    }

    public function applicant_medical_list_report(Request $_request)
    {
        $_file_name = strtoupper(str_replace('_', '-', $_request->category)) . '-' . date('mdy') . '.xlsx'; // Set Filename
        $_report = new CourseApplicantMedicalList($_request->category);
        // $_report->setAutoSize(true);
        $_file = Excel::download($_report, $_file_name); // Download the File
        ob_end_clean();
        return $_file;
    }
    public function student_medical_list_report(Request $_request)
    {
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_file_name =  strtoupper(str_replace('_', '-', $_request->category)) . '-' . $_course->course_code . '-' . date('mdy') . '.xlsx'; // Set Filename
        $_report = new CourseStudentMedicalList($_request->category, $_course);
        $_file = Excel::download($_report, $_file_name); // Download the File
        ob_end_clean();
        return $_file;
    }
    public function appointment_view(Request $request)
    {
        try {
            $dates = MedicalAppointmentSchedule::orderBy('date', 'desc')->get();
            return view('pages.medical.schedule', compact('dates'));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            $this->debugTracker($error);
        }
    }
    public function appointment_store(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'capacity' => 'required'
        ]);
        try {
            $data = MedicalAppointmentSchedule::where('date', $request->date)->first();
            if (!$data) {
                MedicalAppointmentSchedule::create([
                    'date' => $request->date,
                    'capacity' => $request->capacity
                ]);
                return back()->with('success', 'Successfuly Created');
            }
            return back()->with('error', 'This Date is already Existing.');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
            $this->debugTracker($error);
        }
    }
    function applicant_medical_report(Request $request)
    {
        $report = new MedicalReport();
        $dataContent = array(
            array('name' => 'waiting_for_scheduled', 'value' => $this->applicant_medical_version_2('waiting_for_scheduled', $request->_academic)),
            // array('name' => 'medical_scheduled', 'value' => $this->applicant_medical_version_2('medical_scheduled', $request->_academic)),
            array('name' => 'waiting_for_medical_result', 'value' => $this->applicant_medical_version_2('waiting_for_medical_result', $request->_academic)),
            array('name' => 'medical_result_passed', 'value' => $this->applicant_medical_version_2('medical_result_passed', $request->_academic)),
            array('name' => 'medical_result_pending', 'value' => $this->applicant_medical_version_2('medical_result_pending', $request->_academic)),
            array('name' => 'medical_result_failed', 'value' => $this->applicant_medical_version_2('medical_result_failed', $request->_academic))
        );
        $fileExport = new MedicalMonitoringBook($dataContent);
        $fileName = "MEDICAL MONITORING - " . Auth::user()->staff->current_academic()->school_year . '_' . strtoupper(str_replace(' ', '_', Auth::user()->staff->current_academic()->semester));
        $_respond =  Excel::download($fileExport, $fileName . '.xlsx', \Maatwebsite\Excel\Excel::XLSX); // Download the File
        ob_end_clean();
        return $_respond;
        /*  return $report->applicant_medical_report($dataContent); */
    }
    function applicant_medical_version_2($category, $academic)
    {
        $tblApplicantExamination = env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations';
        $tblApplicantExaminationResult = env('DB_DATABASE_SECOND') . '.applicant_entrance_examination_results';
        $tblApplicantAlumia = env('DB_DATABASE_SECOND') . '.applicant_alumnias';
        $tblApplicantExamination = env('DB_DATABASE_SECOND') . '.applicant_entrance_examinations';
        $tblApplicantMedicalScheduled = env('DB_DATABASE_SECOND') . '.applicant_medical_appointments';
        $tblApplicantMedicalResult = env('DB_DATABASE_SECOND') . '.applicant_medical_results';
        $dataList = ApplicantAccount::select('applicant_accounts.*')
            ->where('applicant_accounts.is_removed', false)
            ->where('applicant_accounts.academic_id', base64_decode($academic));
        /*  if ($course != 'ALL COURSE') {
            $dataList = $dataList->where('applicant_accounts.course_id', $course);
        } */
        if ($category == 'waiting_for_scheduled') {
            $dataList->join($tblApplicantExamination, $tblApplicantExamination . '.applicant_id', 'applicant_accounts.id')
                ->join($tblApplicantExaminationResult, $tblApplicantExaminationResult . '.examination_id', $tblApplicantExamination . '.id')
                ->where($tblApplicantExamination . '.is_removed', false)
                ->where($tblApplicantExamination . '.is_finish', true)
                ->where($tblApplicantExaminationResult . '.result', true)
                ->leftJoin($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalScheduled . '.applicant_id')
                ->orderBy('applicant_accounts.course_id', 'asc')
                ->orderBy($tblApplicantExamination . '.examination_start', 'desc')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'shs_alumia_for_medical_schedule') {
            $dataList->join($tblApplicantAlumia, $tblApplicantAlumia . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantAlumia . '.is_removed', false)
                ->orderBy('applicant_accounts.course_id', 'asc')
                ->orderBy('applicant_accounts.created_at', 'desc')
                ->leftJoin($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalScheduled . '.applicant_id')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'waiting_for_medical_result') {
            $dataList->join($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                ->where($tblApplicantMedicalScheduled . '.is_removed', false)
                ->leftJoin($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                ->whereNull($tblApplicantMedicalResult . '.applicant_id')
                ->groupBy('applicant_accounts.id');
        } elseif ($category == 'medical_result_passed' || $category == 'medical_result_pending' || $category == 'medical_result_failed') {
            if ($category == 'medical_result_passed') {
                $dataList->join($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalScheduled . '.is_removed', false)
                    ->join($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalResult . '.is_fit', 1)
                    ->where($tblApplicantMedicalResult . '.is_removed', false)
                    ->orderBy('applicant_accounts.course_id', 'asc')
                    ->orderBy($tblApplicantMedicalResult . '.created_at', 'desc')
                    ->groupBy('applicant_accounts.id');
            } elseif ($category == 'medical_result_pending') {
                $dataList->join($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalScheduled . '.is_removed', false)
                    ->join($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalResult . '.is_pending', false)
                    ->where($tblApplicantMedicalResult . '.is_removed', false)
                    ->orderBy('applicant_accounts.course_id', 'asc')
                    ->orderBy($tblApplicantMedicalResult . '.created_at', 'desc')
                    ->groupBy('applicant_accounts.id');
            } elseif ($category == 'medical_result_failed') {
                $dataList->join($tblApplicantMedicalScheduled, $tblApplicantMedicalScheduled . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalScheduled . '.is_removed', false)
                    ->join($tblApplicantMedicalResult, $tblApplicantMedicalResult . '.applicant_id', 'applicant_accounts.id')
                    ->where($tblApplicantMedicalResult . '.is_fit', 2)
                    ->where($tblApplicantMedicalResult . '.is_removed', false)
                    ->orderBy('applicant_accounts.course_id', 'asc')
                    ->orderBy($tblApplicantMedicalResult . '.created_at', 'desc')
                    ->groupBy('applicant_accounts.id');
            }
        } else {
            $dataList;
        }
        return $dataList->get();
    }
    function applicant_medical($data, $academic)
    {
        $query =  ApplicantAccount::select('applicant_accounts.*')
            ->where('applicant_accounts.academic_id', base64_decode($academic))
            ->where('applicant_briefings.is_removed', false)
            ->groupBy('applicant_accounts.id')
            ->join('applicant_briefings', 'applicant_briefings.applicant_id', 'applicant_accounts.id')
            ->where('applicant_accounts.is_removed', false);
        if ($data === 'waiting_for_scheduled') {
            return  $query->leftJoin('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_accounts.id')
                ->whereNull('ama.applicant_id')->get();
        }
        if ($data == 'medical_scheduled') {
            return  $query->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('is_approved', false)
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($data == 'waiting_for_medical_result') {
            return  $query->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)->leftJoin('applicant_medical_results', 'applicant_medical_results.applicant_id', 'ama.applicant_id')
                ->whereNull('applicant_medical_results.applicant_id')
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($data == 'medical_result_passed') {
            return $query->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)
                ->join('applicant_medical_results', 'applicant_medical_results.applicant_id', 'applicant_briefings.applicant_id')
                ->where('applicant_medical_results.is_fit', true)
                ->where('applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($data == 'medical_result_pending') {
            return $query->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)
                ->join('applicant_medical_results', 'applicant_medical_results.applicant_id', 'applicant_briefings.applicant_id')
                ->where('applicant_medical_results.is_pending', false)
                ->where('applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->get();
        }
        if ($data == 'medical_result_failed') {
            return $query->join('applicant_medical_appointments as ama', 'ama.applicant_id', 'applicant_briefings.applicant_id')
                ->where('ama.is_removed', false)
                ->where('is_approved', true)
                ->join('applicant_medical_results', 'applicant_medical_results.applicant_id', 'applicant_briefings.applicant_id')
                ->where('applicant_medical_results.is_fit', 2)
                ->where('applicant_medical_results.is_removed', false)
                ->groupBy('applicant_accounts.id')->get();
        }
    }

    function student_medical_report(Request $request)
    {
        $report = new MedicalReport();
        return $report->student_medical_report($request->academic);
    }
}
