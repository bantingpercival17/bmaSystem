<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use Illuminate\Http\Request;
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
}
