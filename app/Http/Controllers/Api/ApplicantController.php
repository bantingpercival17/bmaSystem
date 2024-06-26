<?php

namespace App\Http\Controllers\Api;

use App\Exports\ApplicantMedicalSchedule;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\ApplicantDetials;
use App\Models\ApplicantDocuments;
use App\Models\ApplicantDocumentVerification;
use App\Models\ApplicantEntranceExamination;
use App\Models\ApplicantEntranceExaminationResult;
use App\Models\ApplicantExaminationAnswer;
use App\Models\ApplicantExaminationEssay;
use App\Models\ApplicantExaminationSchedule;
use App\Models\ApplicantMedicalAppointment;
use App\Models\ApplicantPayment;
use App\Models\CourseOfferV2;
use App\Models\Documents;
use App\Models\Examination;
use App\Models\ExaminationCategory;
use App\Models\MedicalAppointmentSchedule;
use App\Report\ApplicantReport;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApplicantController extends Controller
{
    public function applicant_information()
    {
        $data = auth()->user();
        $data = ApplicantAccount::with('applicant')->with('course')->with('academic')->find($data->id);
        $_level = Auth::user()->course_id == 3 ? 11 : 4;
        $listOfDocuments =  Documents::where('year_level', $_level)
            ->where('documents.department_id', 2)
            #->where('documents.is_removed', false)
            ->get();
        $documents = $data->applicant_documents;
        $this->applicant_information_verification();
        $approvedDocuments = $data->applicant_documents_status();
        $disqualification = $data->not_qualified;
        $documents = compact('documents', 'listOfDocuments', 'approvedDocuments', 'disqualification');
        // Alumnia
        $alumnia = $data->is_alumnia;
        $payment = $data->payment;
        $examinationDetails = $payment ? $data->applicant_examination : [];
        $examinationSchedule = $examinationDetails ? $examinationDetails->examination_scheduled : [];
        $examinationScheduleHistory = $examinationDetails ? count($examinationDetails->examination_scheduled_history) : 0;
        $examinationResult = [];
        $finalResult = [];
        /* EXAMINATION DETAILS */
        if ($examinationDetails) {
            if ($examinationDetails->is_finish) {
                $user = auth()->user();
                $department = $user->course_id === 3 ? 'SENIOR HIGHSCHOOL' : 'COLLEGE';
                $examinationCategory = Examination::where('examination_name', 'ENTRANCE EXAMINATION')->where('department', $department)->with('categories')->first();
                $finalResult = $examinationDetails->examination_result_v2;
                foreach ($examinationCategory->categories as $key => $value) {
                    $score = ApplicantExaminationAnswer::join(env('DB_DATABASE') . '.examination_questions as examination_question', 'examination_question.id', 'applicant_examination_answers.question_id')
                        ->join(env('DB_DATABASE') . '.examination_question_choices as choices', 'choices.id', 'applicant_examination_answers.choices_id')
                        ->where('applicant_examination_answers.examination_id', $examinationDetails->id)
                        ->where('examination_question.category_id', $value->id)
                        ->where('applicant_examination_answers.is_removed', false)
                        ->where('choices.is_answer', true)
                        ->get();
                    $examinationResult[] = array(
                        'examinationCategory' => $value->category_name,
                        'totalItems' => count($value->question),
                        'score' => count($score)
                    );
                }
            }
        }
        $examination = compact('payment', 'examinationDetails', 'examinationSchedule', 'examinationScheduleHistory', 'examinationResult', 'finalResult');
        /* BRIEFING ORIENTATION */
        $orientation = [];
        if ($data->schedule_orientation) {
            $schedule = $data->schedule_orientation;
            $present = $data->virtual_orientation;
            $orientation = compact('schedule', 'present');
        }
        /* MEDICAL  */
        $medical_scheduled = MedicalAppointmentSchedule::where('is_close', false)->orderBy('date', 'desc')->get();
        $medical = compact('medical_scheduled');
        if ($data->medical_appointment) {
            $appointment = $data->medical_appointment;
            $medical_result = $data->medical_result;
            $medical = compact('appointment', 'medical_result');
        }
        /* ENROLLMENT DETAILS */
        $enrollment = $data->student_applicant ? $data->student_applicant->student_details :  [];

        return response(['data' => $data, 'alumnia' => $alumnia, 'documents' => $documents, 'examination' => $examination, 'orientation' => $orientation, 'medical' => $medical, 'enrollment' => $enrollment], 200);
    }
    function applicant_information_verification()
    {
        $account = auth()->user();
        $applicant = ApplicantDetials::where('applicant_id', $account->id)->first();
        if ($applicant) {
            $height = $applicant->height;
            // Course
            if ($account->course_id == 2 || $height <= 161.544) {
                return response(['message' => "You're Height is not Quilifed on Marine Transportation. Do you want to shift on Marine Engineering"], 200);
            }
        }
    }
    public function applicant_store_information(Request $_request)
    {
        $_fields = [
            'first_name' => 'required',
            'last_name' => 'required',
            /*  'middle_name' => 'required',
            'extension_name' => 'required | min:2', */
            'birth_date' => 'required',
            'birth_place' => 'required',
            'gender' => 'required',
            'weight' => 'required',
            'height' => 'required',
            'civil_status' => 'required',
            'religion' => 'required',
            'nationality' => 'required',
            'street' => 'required',
            'barangay' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'zip_code' => 'required',
            'personal_email' => 'required',
            'contact_number' => 'required | numeric| min:12',
            // FATHER INFORMATION
            'father_last_name' => 'required | min:2 | max:50',
            'father_first_name' => 'required | min:2 | max:50',
            'father_middle_name' => 'required | min:2 | max:50',
            'father_educational_attainment' => 'required | min:2 | max:100',
            'father_employment_status' => 'required | min:2 | max:50',
            'father_working_arrangement' => 'required | min:2 | max:50',
            'father_contact_number' => 'required | min:2 | max:12',
            // MOTHER INFORMATION
            'mother_last_name' => 'required | min:2 | max:50',
            'mother_first_name' => 'required | min:2 | max:50',
            'mother_middle_name' => 'required | min:2 | max:50',
            'mother_educational_attainment' => 'required | min:2 | max:100',
            'mother_employment_status' => 'required | min:2 | max:50',
            'mother_working_arrangement' => 'required | min:2 | max:50',
            'mother_contact_number' => 'required | min:2 | max:12',
            // GUARDIAN  INFORMATION
            'guardian_last_name' => 'required | min:2 | max:50',
            'guardian_first_name' => 'required | min:2 | max:50',
            'guardian_middle_name' => 'required | min:2 | max:50',
            'guardian_educational_attainment' => 'required | min:2 | max:50',
            'guardian_employment_status' => 'required | min:2 | max:50',
            'guardian_working_arrangement' => 'required | min:2 | max:50',
            'guardian_contact_number' => 'required| min:2 | max:12',

            'elementary_school_name' => 'required|max:100',
            'elementary_school_address' => 'required|max:255',
            'elementary_school_year' => 'required|max:100',
            'junior_high_school_name' => 'required|max:100',
            'junior_high_school_address' => 'required|max:255',
            'junior_high_school_year' => 'required|max:100',
        ];

        $_data = [];
        if (auth()->user()->course_id != 3) {
            $_fields += [
                'senior_high_school_name' => 'required|max:100',
                'senior_high_school_address' => 'required|max:255',
                'senior_high_school_year' => 'required|max:100',
                'strand' => 'required'
            ];
        }
        $inputs = $_request->validate($_fields);
        foreach ($inputs as $key => $value) {
            if ($key != 'personal_email' && $key != 'contact_number' && $key != 'strand') {
                if ($key == 'extension_name') {
                    $_data['extention_name'] = ucwords(mb_strtolower(trim($value)));
                } else if ($key == 'birth_date') {
                    $_data['birthday'] = ucwords(mb_strtolower(trim($value)));
                } else if ($key == 'gender') {
                    $_data['sex'] = ucwords(mb_strtolower(trim($value)));
                } else {
                    $_data[$key] = ucwords(mb_strtolower(trim($value)));
                }
            }
        }
        $user = auth()->user(); // Get the Current User Account
        $account = ApplicantAccount::find($user->id); // Get the Applicant Account using the user id
        $account->contact_number = $inputs['contact_number'];
        $account->strand = $_request->strand;
        $account->save();
        if ($account->applicant) {
            # If Account have Applicant Details it will be Update the information
            ApplicantDetials::where('applicant_id', $user->id)->update($_data);
            return response(['message' => "Applicant's Information Completed"], 200);
        } else {
            # Then is not they will be Store the Applicant Details
            $_data['applicant_id'] = $account->id;
            ApplicantDetials::create($_data);
            return response(['message' => "Applicant's Information Completed"], 200);
        }
    }
    function applicant_registration_form()
    {
        $_report = new ApplicantReport;
        $user = auth()->user();
        $_applicant = ApplicantAccount::find($user->id);
        $report =  $_report->applicant_form($_applicant);
        return response($report)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'inline; filename="report.pdf"');
    }
    function file_upload(Request $request)
    {
        $request->validate([
            'file' => 'required| mimes:jpg,bmp,png',
        ]);
        try {
            # If verify the Document Data
            $documentChecker = ApplicantDocuments::where([
                'applicant_id' => Auth::user()->id,
                'document_id' => $request->document, 'is_removed' => false
            ])->first();
            if ($documentChecker) {
                $documentChecker->is_removed = true;
                $documentChecker->save();
            }
            $fileLink[] = $this->saveApplicantFile($request->file, 'bma-applicants', 'documents');
            $_data = [
                'applicant_id' => Auth::user()->id,
                'document_id' => $request->document,
                'file_links' => json_encode($fileLink),
                'is_removed' => 0,
            ];
            $data = ApplicantDocuments::create($_data);
            $this->applicant_document_verification();
            return $data;
        } catch (\Throwable $error) {
            $this->debugTrackerApplicant($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function applicant_document_verification()
    {
        // Applicant Details
        $applicant = Auth::user();
        // Get the Required Documents
        $level = $applicant->course_id == 3 ? 11 : 4;
        // Get Required Documnet Per Course
        $requredDocuments = Documents::select('id')
            ->where('year_level', $level)
            ->where('is_removed', false)
            ->get();
        $applicantDocumentCount = 0; // Set the applicant document count
        // Document Checker
        foreach ($requredDocuments as $key => $document) {
            $applicantDocument = ApplicantDocuments::where('applicant_id', $applicant->id)
                ->where('document_id', $document->id)
                ->where('is_removed', false)
                ->first();
            if ($applicantDocument) {
                $applicantDocumentCount += 1;
            }
        }
        // Check the if the Applicant Document Count and Required Document is Equal
        if ($applicantDocumentCount == count($requredDocuments)) {
            // Then Create Applicant Document Verification
            $valdation = ApplicantDocumentVerification::where('applicant_id', $applicant->id)->where('is_removed', false)->first();
            if ($valdation) {
                $valdation->is_removed = true;
                $valdation->save();
                ApplicantDocumentVerification::create(['applicant_id' => $applicant->id]);
            } else {
                ApplicantDocumentVerification::create(['applicant_id' => $applicant->id]);
            }
            //ApplicantDocumentVerification::firstOrCreate(['applicant_id' => $applicant->id]);
        }
    }
    function payment_transaction(Request $request)
    {
        $inputFields = array(
            'transaction_date' => 'required',
            'amount_paid' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'payment_mode' => 'required',
            'file' => 'required| mimes:jpg,bmp,png',
        );
        if (strtolower($request->payment_mode) == 'e-wallets') {
            $inputFields += ['reference_number' => 'required'];
        }
        $request->validate($inputFields);
        try {
            $user = auth()->user();
            $paymentHistory = ApplicantPayment::where('applicant_id', $user->id)->where('is_removed', false)->first();
            if ($paymentHistory) {
                $paymentHistory->is_removed = true;
                $paymentHistory->save();
            }
            $fileLink = $this->saveApplicantFile($request->file, 'bma-applicants', 'proofOfPayment');
            $data = array(
                'applicant_id' => $user->id,
                'amount_paid'  => str_replace(',', '', $request->amount_paid),
                'reference_number' => strtolower($request->payment_mode) == 'e-wallets' ? $request->reference_number : '',
                'transaction_type' => 'entrance-examination-payment',
                'reciept_attach_path' => $fileLink
            );
            ApplicantPayment::create($data);
            return response(['message' => 'Successfully Submit of your Payment'], 200);
        } catch (\Throwable $error) {
            $this->debugTrackerApplicant($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function examination_scheduled(Request $request)
    {
        $request->validate([
            'schedule' => 'required',
            'schedule_time' => 'required|',
        ]);
        try {
            $user = auth()->user();
            $schedule = ApplicantExaminationSchedule::where('applicant_id', $user->id)->where('is_removed', false)->first();
            $dateString = $request->schedule;
            $date = DateTime::createFromFormat('D M d Y', $dateString);
            $formattedDate = $date->format('Y-m-d');
            $scheduleDate =  $formattedDate . ' ' . $request->schedule_time . ':00';
            //return response(['message' => $scheduleDate]);
            if ($schedule) {
                ApplicantExaminationSchedule::create([
                    'applicant_id' => $user->id,
                    'examination_id' => $user->applicant_examination->id,
                    'schedule_date' => $scheduleDate
                ]);
            }
            $schedule->is_removed = true;
            $schedule->save();
            return response(['message' => 'Your Examination Schedule has been approved.']);
        } catch (\Throwable $error) {
            $this->debugTrackerApplicant($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function examination_verification(Request $request)
    {
        $request->validate([
            'examination' => 'required'
        ]);
        try {
            $user = auth()->user();
            $examinationVerification = ApplicantEntranceExamination::where('applicant_id', $user->id)->where('examination_code', $request->examination)->where('is_removed', false)->first();
            if (!$examinationVerification) {
                return response(['errors' => ['examination' => ['Examination Code is Invalid']]], 422);
            }
            $examinationVerification->is_finish = 0;
            $examinationVerification->examination_start = now();
            $examinationVerification->save();
            return response(['data' => 'Examination Code Verified'], 200);
        } catch (\Throwable $th) {
            $this->debugTrackerApplicant($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function examination_questions(Request $request)
    {
        try {
            $user = auth()->user();
            $department = $user->course_id === 3 ? 'SENIOR HIGHSCHOOL' : 'COLLEGE';
            $examination = Examination::where('examination_name', 'ENTRANCE EXAMINATION')->where('department', $department)->first();
            $questionLists =  $examination->category_lists;
            $applicantExamination = ApplicantEntranceExamination::where('applicant_id', $user->id)->where('examination_code', base64_decode($request->code))->first();
            if ($applicantExamination) {
                return response(['examinationDetails' => $applicantExamination, 'questionLists' => $questionLists], 200);
            } else {
                return response(['message' => 'Invalid Examination Code'], 401);
            }
        } catch (\Throwable $th) {
            $this->debugTrackerApplicant($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function examination_answer(Request $request)
    {
        try {
            $verify = ApplicantExaminationAnswer::where('examination_id', $request->examination)->where('question_id', $request->question)->first();
            if ($verify) {
                $verify->choices_id = $request->choices;
                $verify->save();
            } else {
                ApplicantExaminationAnswer::create([
                    'examination_id' => $request->examination,
                    'question_id' => $request->question,
                    'choices_id' => $request->choices,
                ]);
            }
        } catch (\Throwable $th) {
            $this->debugTrackerApplicant($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function examination_finish(Request $request)
    {
        try {
            // Find Examination the Request examination id
            $examination = ApplicantEntranceExamination::find($request->examination);
            $examination->is_finish = true;
            $examination->save();
            // Examination Result
            $result = $examination->examination_result();
            $examinationDetails = array(
                'applicant_id' => $examination->applicant_id,
                'examination_id' => $examination->id,
                'examination_date' => $examination->examination_start,
                'score' => $result[0],
                'result' => $result[2],
            );
            if (!$examination->examination_result_v2) {
                ApplicantEntranceExaminationResult::create($examinationDetails);
            }
            // If the Student Finish the Examination On Essay
            $essay = ApplicantExaminationEssay::where('examination_id', $request->examination)->first();
            if ($essay) {
                $essay->essay_answer =  base64_encode($request->essay);
                $essay->save();
            } else {
                ApplicantExaminationEssay::create([
                    'examination_id' => $request->examination,
                    'essay_answer' => base64_encode($request->essay),
                    'is_removed' => false
                ]);
            }

            return response(['data' => 'Examination Complete'], 200);
        } catch (\Throwable $th) {
            $this->debugTrackerApplicant($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function medical_appointment($data)
    {
        try {
            $scheduleData = array(
                'applicant_id' => auth()->user()->id,
                'appointment_date' => $data,
                'approved_by' => 7
            );
            ApplicantMedicalAppointment::create($scheduleData);
            return response(['data' => 'Medical Appointment Submitted'], 200);
        } catch (\Throwable $th) {
            $this->debugTrackerApplicant($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    function medical_appointment_slot($data)
    {
        try {
            $schedule = MedicalAppointmentSchedule::find($data);
            return $schedule->number_of_avialable_applicant() == $schedule->capacity ? true : false;
        } catch (\Throwable $th) {
            $this->debugTrackerApplicant($th);
            return response([
                'message' => $th->getMessage()
            ], 500);
        }
    }
    public function applicant_logout(Request $_request)
    {
        Auth::guard('applicant')->user()->tokens()->delete();
        //auth()->user()->tokens()->delete();

        return [
            'message' => 'Logget out',
        ];
    }
    function applicant_overview()
    {
        try {
            $data1 = ['registered_applicants', 'approved', 'disapproved', 'pending', 'senior_high_school_alumni'];
            $data2 = ['waiting_examination_payment', 'examination_payment', 'entrance_examination', 'passed', 'failed'];
            $data3 = ['for_medical_schedule', 'waiting_for_medical_results', 'fit', 'unfit', 'pending_result'];
            $courses = CourseOfferV2::with(['registered_applicants'])->get();
            return compact('courses');
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
}
