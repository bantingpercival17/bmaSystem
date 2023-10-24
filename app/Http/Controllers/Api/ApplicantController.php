<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\ApplicantDetials;
use App\Models\ApplicantDocuments;
use App\Models\ApplicantEntranceExamination;
use App\Models\ApplicantExaminationAnswer;
use App\Models\ApplicantExaminationEssay;
use App\Models\ApplicantPayment;
use App\Models\Documents;
use App\Models\Examination;
use App\Report\ApplicantReport;
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
            ->where('documents.is_removed', false)
            ->get();
        $documents = $data->applicant_documents;
        $approvedDocuments = $data->applicant_documents_status();
        $documents = compact('documents', 'listOfDocuments', 'approvedDocuments');
        $payment = $data->payment;
        $examinationDetails = $payment ? $data->applicant_examination : [];
        $examination = compact('payment', 'examinationDetails');
        return response(['data' => $data, 'documents' => $documents, 'examination' => $examination], 200);
    }
    public function applicant_store_information(Request $_request)
    {
        $_fields = [
            'first_name' => 'required',
            'last_name' => 'required',
            'middle_name' => 'required',
            'extension_name' => 'required | min:2',
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
            ];
        }
        $inputs = $_request->validate($_fields);
        foreach ($inputs as $key => $value) {
            if ($key != 'personal_email' && $key != 'contact_number') {
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
        $account->save();
        if ($account->applicant) {
            # If Account have Applicant Details it will be Update the information
            ApplicantDetials::where('applicant_id', $user->id)->update($_data);
            return response(['message' => 'Update the Applicant Information'], 200);
        } else {
            # Then is not they will be Store the Applicant Details
            $_data['applicant_id'] = $account->id;
            ApplicantDetials::create($_data);
            return response(['message' => 'Successfully Save the Applicant Information'], 200);
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
            return $data;
        } catch (\Throwable $error) {
            $this->debugTrackerApplicant($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function payment_transaction(Request $request)
    {
        $request->validate([
            'transaction_date' => 'required',
            'amount_paid' => 'required|numeric|regex:/^\d+(\.\d{1,2})?$/',
            'reference_number' => 'required',
            'file' => 'required| mimes:jpg,bmp,png',
        ]);
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
                'reference_number' => $request->reference_number,
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
    public function applicant_logout(Request $_request)
    {
        Auth::guard('applicant')->user()->tokens()->delete();
        //auth()->user()->tokens()->delete();

        return [
            'message' => 'Logget out',
        ];
    }
}
