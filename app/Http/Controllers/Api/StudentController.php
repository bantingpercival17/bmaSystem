<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DeploymentAssesment;
use App\Models\ShipBoardInformation;
use App\Models\ShipboardJournal;
use App\Models\StudentAccount;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function student_details()
    {
        $account = auth()->user();
        $student = StudentAccount::where('id', $account->id)->with('student')->first();
        return response(['account' => $student], 200);
    }


    public function student_onboarding()
    {
        try {
            $account = auth()->user();
            $onboard_assessment = ShipBoardInformation::where('student_id', $account->student_id)->get();
            $journal = $_journal = ShipboardJournal::select('month', DB::raw('count(*) as total'))->where('student_id', $account->student_id)->where('is_removed', false)->groupBy('month')->get();

            return response(['shipboard_information' => $onboard_assessment], 200);
        } catch (Exception $error) {
            return response(['error' => $error->getMessage()], 505);
        }
    }
}
