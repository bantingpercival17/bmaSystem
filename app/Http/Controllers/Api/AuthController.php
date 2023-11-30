<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\ApplicantEmail;
use App\Mail\StudentEnrollmentMail;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\ApplicantDetials;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Mews\Captcha\Facades\Captcha;

class AuthController extends Controller
{
    public function applicant_login(Request $_request)
    {
        $_fields = $_request->validate([
            'email' => 'required|email',
            'password' => 'required|string',

        ]);
        try {
            if (!Auth::guard('applicant')->attempt($_fields)) {
                return response([
                    'message' => 'Invalid Credentials.'
                ], 401);
            }
            $_data = Auth::guard('applicant')->user();
            $account = ApplicantAccount::with('applicant')->find($_data->id);
            $profile_picture = $account->image ? json_decode($account->image->file_links)[0] : 'http://bma.edu.ph/img/student-picture/midship-man.jpg';
            $student = compact('account', 'profile_picture');
            $token = Auth::guard('applicant')->user()->createToken('applicantToken')->plainTextToken;
            return response(
                [
                    'student' => $student,
                    'token' => $token
                ],
                200
            );
        } catch (\Throwable $error) {
            $this->debugTrackerUser($error);
            return response([
                'message' => $error->getMessage()
            ], 402);
        }
    }

    public function applicant_registration(Request $_request)
    {
        // Validate the input fileds
        $_fields = $_request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            /*   'email' => 'required|string|unique:mysql2.applicant_accounts,email', */
            'contactNumber' => 'required',
            'course' => 'required',
            'birthday' => 'required|string',
            'captcha' => 'required',
            'agreement' => 'required|boolean'
        ]);
        try {
            // Current Academic
            $_academic =  AcademicYear::where('semester', 'First Semester')->orderBy('id', 'desc')->first();
            // Applicant Validation
            $_applicant = ApplicantDetials::where('first_name', $_fields['firstName'])
                ->where('last_name', $_fields['lastName'])
                ->where('birthday', $_fields['birthday'])
                ->first();
            $_account = ApplicantAccount::where('name', trim($_fields['firstName']) . ' ' . trim($_fields['lastName']))->where('academic_id', $_academic->id)->first();
            if ($_applicant || $_account) {
                return response(['errors' => array('message' => 'This Applicant is already existing')], 422);
            }
            // Get the number of Applicant Per School Year
            $_transaction_number = ApplicantAccount::where('academic_id', $_academic->id)->count();
            $_details = [
                'name' => trim($_request->firstName) . ' ' . trim($_request->lastName),
                'email' => trim($_request->email),
                'course_id' => $_request->course,
                'contact_number' => $_request->contactNumber,
                'password' => Hash::make('AN-' . date('ymd') . ($_transaction_number + 1)),
                'applicant_number' => 'AN-' . date('ymd') . ($_transaction_number + 1),
                'academic_id' => $_academic->id,
                'is_removed' => 0,
            ];
            try {
                return response(['error' => array('message' => 'Please await the official announcement for the admission process for the academic year 2024-2025')], 422);
                /*  $user = ApplicantAccount::create($_details);
                $applicant = ApplicantAccount::find($user->id);
                $mail = new ApplicantEmail();
                Mail::to($_request->email)->bcc('developer@bma.edu.ph')->send($mail->pre_registration_notificaiton($applicant));
                $message = "Email Sent";
                return response(['message' => 'Thank you for submitting your application! Your login credentials have been sent to email address: ' . $applicant->email], 200); */
                #return back()->with('success-message', 'Thank you for submitting your application! Your login credentials have been sent to' . $_request->email);
            } catch (\Throwable  $error) {
                $_request->header('User-Agent');
                return response(['error' => $error->getMessage()], 505);
            }
        } catch (\Throwable $error) {
            $_request->header('User-Agent');
            return response(['error' => $error->getMessage()], 505);

            // Create a function to Controler file to save and store the details of bugs
        }
    }
    public function student_login(Request $_request)
    {
        $_fields = $_request->validate([
            'email' => 'required|email',
            'password' => 'required|string',

        ]);
        try {
            if (!Auth::guard('student')->attempt($_fields)) {
                return response([
                    'message' => 'Invalid Credentials.'
                ], 401);
            }
            $_data = Auth::guard('student')->user();
            $_student = StudentAccount::find($_data->id);
            $account = StudentAccount::where('id', $_data->id)->with('student')->first();
            $student = StudentDetails::find($_data->student_id);
            $profile_picture =  $student->profile_picture();
            $student = compact('account', 'profile_picture');
            return response(
                [
                    'student' => $student,
                    'token' => $_student->createToken('studentToken')->plainTextToken
                ],
                200
            );
        } catch (\Throwable $error) {
            //$this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 402);
        }
    }
    function student_forget_password(Request $request)
    {
        $request->validate(['email' => 'required']);
        try {
            $account = StudentAccount::where('email', $request->email)->where('is_removed', false)->first();
            if (!$account) {
                return response(['message' => 'Email does not exist', 'errors' => ['email' => ['Student Email does not exist']]], 422);
            }
            $mail = new StudentEnrollmentMail();
            $length = 8;
            $_password = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length / strlen($x)))), 1, $length);
            $account->password = Hash::make($_password);
            $account->save();
            Mail::to($account->email)->bcc('developer@bma.edu.ph')->send($mail->student_forget_password($account, $_password));
            return response(['data' => 'success'], 200);
        } catch (\Throwable $error) {
            //$this->debugTrackerStudent($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
}
