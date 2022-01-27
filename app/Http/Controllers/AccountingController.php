<?php

namespace App\Http\Controllers;

use App\Models\CourseOffer;
use App\Models\CourseSemestralFees;
use App\Models\Curriculum;
use App\Models\ParticularFees;
use App\Models\Particulars;
use App\Models\SemestralFee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('accounting');
    }
    public function index()
    {
        return view('pages.accounting.dashboard.view');
    }
    public function particular_view(Request $_request)
    {
        $_particulars = Particulars::where('is_removed', false)->get();
        return view('pages.accounting.particular.view', compact('_particulars'));
    }
    public function particular_store(Request $_request)
    {
        $_request->validate([
            '_name' => 'required',
            '_type' => 'required',
            '_tag' => 'required',
            '_department' => 'required',
        ]);
        $_details = array(
            'particular_name' => trim(ucwords(mb_strtolower($_request->_name))),
            'particular_type' => $_request->_type,
            'particular_tag' => $_request->_tag,
            'department' => $_request->_department,
            'is_removed' => 0
        );
        Particulars::create($_details);
        return back()->with('success', 'Successfully Created Particulars');
    }
    public function particular_fee_view(Request $_request)
    {
        $_particulars = Particulars::where('department', $_request->_department)->where('is_removed', false)->get();
        return view('pages.accounting.particular.create_semestral_fee', compact('_particulars'));
    }

    public function particular_fee_store(Request $_request)
    {
        foreach ($_request->data as $key => $value) {
            $_data = array(
                'particular_id' => $value['id'],
                'particular_amount' => $value['fee'],
                'academic_id' => $_request->_academic,
                'is_removed' => 0
            );
            ParticularFees::create($_data);
        }
        return back()->with('success', 'Successfully Created a Semestral Tuition Fee');
    }


    public function fee_view(Request $_request)
    {
        $_courses = CourseOffer::where('is_removed', false)->get();
        return view('pages.accounting.fee.view', compact('_courses'));
    }
    public function course_fee_view(Request $_request)
    {
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_course_fees = CourseSemestralFees::where('course_id', $_course->id)->where('academic_id', Auth::user()->staff->current_academic()->id)->get();

        $_tag = CourseSemestralFees::select('p.particular_tag')
            ->selectRaw("sum(pf.particular_amount) as fees")
            ->join('semestral_fees as sf', 'sf.course_semestral_fee_id', 'course_semestral_fees.id')
            ->join('particular_fees as pf', 'pf.id', 'sf.particular_fee_id')
            ->join('particulars as p', 'p.id', 'pf.particular_id')
            ->groupBy('p.particular_tag')
            ->get();
        $_tuition_fee = CourseSemestralFees::select('particulars.particular_tag')->join('semestral_fees', 'semestral_fees.course_semestral_fee_id', 'course_semestral_fees.id')
            ->join('particular_fees as pf', 'pf.id', 'semestral_fees.particular_fee_id')
            ->join('particulars', 'pf.particular_id', 'particulars.id')
            ->groupBy('particulars.particular_tag')
            ->sum('pf.particular_amount');
        //return compact('_tag');
        return view('pages.accounting.fee.course_fee_view', compact('_course', '_course_fees'));
       
    }
    public function course_fee_create_view(Request $_request)
    {
        $_department = base64_decode($_request->_course) == 3 ? 'senior_high' : 'college';
        $_particulars = Particulars::where('department', $_department)->where('is_removed', false)->get();
        $_curriculum = Curriculum::all();
        //$_courses = CourseOffer::where('is_removed', false)->get();
        return view('pages.accounting.fee.create_semestral_fee', compact('_particulars', '_curriculum'));
    }
    public function course_fee_store(Request $_request)
    {
        $_request->validate([
            '_year_level' => 'required',
            '_curriculum' => 'required'
        ]);
        $_details = array(
            'course_id' => base64_decode($_request->_course),
            'curriculum_id' => $_request->_curriculum,
            'year_level' => $_request->_year_level,
            'academic_id' => $_request->_academic,

        );
        $_course_semestral = CourseSemestralFees::where($_details)->first();
        $_course_semestral = $_course_semestral ?: CourseSemestralFees::create($_details);
        //echo var_dump($_details) . "<br>";
        foreach ($_request->data as $key => $value) {
            if ($value['fee'] != null) {
                $_data = ParticularFees::where([
                    'particular_id' => $value['particular'],
                    'particular_amount' => $value['fee'],
                    'academic_id' => $_request->_academic,
                ])->first();
                if ($_data) {
                    $_particulars = $_data->id;
                } else {
                    $_particular = ParticularFees::create([
                        'particular_id' => $value['particular'],
                        'particular_amount' => $value['fee'],
                        'academic_id' => $_request->_academic,
                        'is_removed' => 0
                    ]);
                    $_particulars = $_particular->id;
                }
            } else {
                $_particulars = $value['id'];
            }
            $_data = array(
                'particular_fee_id' => $_particulars,
                'course_semestral_fee_id' => $_course_semestral->id,
                'is_removed' => false
            );
            SemestralFee::where([
                'particular_fee_id' => $_particulars,
                'course_semestral_fee_id' => $_course_semestral->id,
            ])->first() ?:
                SemestralFee::create($_data);
        }
        return back()->with('success', 'Successfully Create a Semestral Tuition Fee');
    }
}
