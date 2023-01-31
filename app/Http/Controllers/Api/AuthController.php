<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\ApplicantDetials;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use Exception;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function applicant_login(Request $_request)
    {
        $_fields = $_request->validate([
            'email' => 'required|string',
            'password' => 'required',

        ]);
        try {

            // Check Email
            $account = ApplicantAccount::where('email', $_fields['email'])->first();
            // Check Password
            if (!$account || !Hash::check($_fields['password'], $account->password)) {
                return response(['message' => 'Invalid Creadials'], 401);
            }
            /* if (!Auth::guard('applicant')->attempt($_fields)) {
                return response([
                    'message' => 'Invalide Credentials.'
                ], 401);
            }
            $_data = Auth::guard('applicant')->user(); // Get the Applicant Account Details
            $account = ApplicantAccount::find($_data->id); */
            $_token = $account->createToken('secretToken')->plainTextToken; // Get the secure Token
            return response(['data' => $account, 'token' => $_token], 200); // Then return Response to the Front End
        } catch (Expression $error) {
            return response([
                'message' => $error
            ], 402);
        }
    }

    public function applicant_registration(Request $_request)
    {
        // Validate the input fileds
        $_fields = $_request->validate([
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|string|unique:mysql2.applicant_accounts,email',
            'contactNumber' => 'required',
            'password' => 'required|min:6|max:20|confirmed',
            'course' => 'required',
            'birthday' => 'required|string'
        ]);
        try {
            // Applicant Validation
            $_applicant = ApplicantDetials::where('first_name', $_fields['firstName'])
                ->where('last_name', $_fields['lastName'])
                ->where('birthday', $_fields['birthday'])
                ->first();

            if ($_applicant) {
                return response(['message' => 'This Applicant is already existing'], 422);
            }
            // Get the Academic School Year
            $_academic = AcademicYear::where('is_active', 1)->first();
            // Get the number of Applicant Per School Year 
            $_transaction_number = ApplicantAccount::where('academic_id', $_academic->id)->count();
            $_details = [
                'name' => $_request->firstName . ' ' . $_request->lastName,
                'email' => $_request->email,
                'course_id' => $_request->course,
                'contact_number' => $_request->contactNumber,
                'password' => Hash::make($_request->password),
                'applicant_number' => 'TR-' . date('ymd') . ($_transaction_number + 1),
                'academic_id' => $_academic->id,
                'is_removed' => 0,
            ];
            ApplicantAccount::create($_details);
            return response(['data' => 'Registration Successfully'], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
            $_request->header('User-Agent');
            // Create a function to Controler file to save and store the details of bugs
        }
    }
    public function register(Request $_request)
    {
        $_fields = $_request->validate([
            'name' => 'required',
            'email' => 'required|string|unique:mysql2.applicant_accounts,email',
            'password' => 'required|string|confirmed',
            'course' => 'required'
        ]);
        $_academic = AcademicYear::where('is_active', 1)->first();
        //return $_academic->id;
        $_applicant = ApplicantAccount::create([
            'name' => $_fields['name'],
            'email' => $_fields['email'],
            'password' => bcrypt($_fields['password']),
            'applicant_nunber' => '',
            'course_id' => $_fields['course'],
            'academic_id' => $_academic->id
        ]);
        $_token = $_applicant->createToken('myapptoken')->plainTextToken;
        $_reponse = [
            'user' => $_applicant,
            'token' => $_token
        ];
        return response($_reponse, 201);
    }

    public function student_login(Request $_request)
    {
        try {
            $_fields = $_request->validate([
                'email' => 'required|email',
                'password' => 'required|string',

            ]);
            if (!Auth::guard('student')->attempt($_fields)) {
                return response([
                    'message' => 'Invalide Credentials.'
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
        } catch (Expression $error) {
            return response([
                'message' => $error
            ], 402);
        }
    }
}
