<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ApplicantAccount;
use App\Models\ApplicantDetials;
use App\Models\Documents;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ApplicantController extends Controller
{
    public function applicant_information()
    {
        $data = auth()->user();
        $data = ApplicantAccount::with('applicant')->find($data->id);
        $_level = Auth::user()->course_id == 3 ? 11 : 4;
        $listOfDocuments =  Documents::where('year_level', $_level)
            ->where('department_id', 2)
            ->where('is_removed', false)
            ->get();
        $documents = $data->applicant_documents;
        $documents = compact('documents', 'listOfDocuments');
        return response(['data' => $data, 'documents' => $documents], 200);
    }
    public function create_applicant_details(Request $_request)
    {
        $_inputs = $_request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'middle_name' => 'required|string',
            'extension_name' => 'required|string',
            'birthday' => 'required|date',
            'birth_place' => 'required|string',
            'street' => 'required',
            'barangay' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'zip_code' => 'required',
            /* 'father_name' => 'required',
            'father_contact_number' => 'required',
            'mother_name' => 'required',
            'mother_contact_number' => 'required',
            'parent_address' => 'required', */
        ]);

        $_data = [];
        foreach ($_inputs as $key => $value) {
            //$_data[$value] = trim(ucwords(strtolower($_request->input('_first_name')))) ;
            $_data[$key] = ucwords(mb_strtolower(trim($value)));
        }
        $_data['student_id'] = 1;
        return response($_data);
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
