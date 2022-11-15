<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\StudentAccount;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $_request)
    {
        $_fields = $_request->validate([
            'password' => 'required',
            'email' => 'required|string',
        ]);
        $_applicant = ApplicantAccount::where('email', $_fields['email'])->first();
        if (!$_applicant || Hash::check($_fields['password'], $_applicant->password)) {
            return response([
                'message' => 'Invalid Creds',
            ], 401);
        }
        $_token = $_applicant->createToken('myapptoken')->plainTextToken;
        $_reponse = [
            'user' => $_applicant,
            'token' => $_token
        ];
        return response($_reponse, 201);
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
            $account = auth()->user();
            $student = StudentAccount::where('id', $_data->id)->with('student')->first();
            return response(
                [
                    'account' => $student,
                    'token' => $_student->createToken('secretToken')->plainTextToken
                ],
                200
            );
            //return Auth::user()->student;
            /* if (!Auth::attempt($_fields)) {
                return response([
                    'message' => 'Invalide Credentials.'
                ], 401);
            }
            $_user = User::find(Auth::user()->id);
            return response(
                [
                    'user' => Auth::user(),
                    'token' => $_user->createToken('secretToken')->plainTextToken
                ],
                200
            ); */
        } catch (Expression $error) {
            return response([
                'message' => $error
            ], 402);
        }
    }
}
