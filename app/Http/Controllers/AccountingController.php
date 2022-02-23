<?php

namespace App\Http\Controllers;

use App\Models\CourseOffer;
use App\Models\CourseSemestralFees;
use App\Models\Curriculum;
use App\Models\EnrollmentAssessment;
use App\Models\ParticularFees;
use App\Models\Particulars;
use App\Models\PaymentAssessment;
use App\Models\PaymentTransaction;
use App\Models\PaymentTrasanctionOnline;
use App\Models\Section;
use App\Models\SemestralFee;
use App\Models\StudentDetails;
use App\Models\StudentNonAcademicClearance;
use App\Models\Voucher;
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
        $_courses = CourseOffer::where('is_removed', false)->orderBy('id', 'desc')->get();
        $_total_population = EnrollmentAssessment::join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            /* ->join('payment_transactions as pt', 'pt.assessment_id', 'pa.id')
            ->where('pt.remarks', 'Upon Enrollment') */
            ->where('enrollment_assessments.is_removed', false)
            ->where('academic_id', Auth::user()->staff->current_academic()->id)
            ->with('payment_transactions')
            ->get();
        return view('pages.accounting.dashboard.view', compact('_courses', '_total_population'));
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
        $_course_fees = CourseSemestralFees::where('course_id', $_course->id)->where('academic_id', Auth::user()->staff->current_academic()->id)->where('is_removed', false)->get();

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
    public function course_fee_view_list(Request $_request)
    {
        $_course_fee = CourseSemestralFees::find(base64_decode($_request->_course_fee));
        return view('pages.accounting.fee.create_semestral_fee_list', compact('_course_fee'));
        return $_course_fee->semestral_fee_list;
    }
    public function course_change_fee(Request $_request)
    {
        //return $_request->_semestral_fee;
        $_course_fee = SemestralFee::find(base64_decode($_request->_semestral_fee));
        $_course_fee->particular_fee_id = $_request->_amount;
        $_course_fee->save();
        return back()->with('success', 'Successfully Change Amount');
    }
    public function course_fee_create_view(Request $_request)
    {
        $_department = base64_decode($_request->_course) == 3 ? 'senior_high' : 'college';
        $_particulars = Particulars::where('department', $_department)->where('is_removed', false)/* ->where('particular_type', 'tuition_type') */->get();
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
            'is_removed' => false,
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
    public function course_fee_remove(Request $_request)
    {
        $_course_fee = CourseSemestralFees::find(base64_decode($_request->_course_fee));
        $_course_fee->is_removed = true;
        $_course_fee->save();
        return back()->with('success', 'Successfully Removed Course Fees');
    }
    public function assessment_view(Request $_request)
    {
        $_student_detials = new StudentDetails();
        $_student = $_request->_midshipman ? StudentDetails::find(base64_decode($_request->_midshipman)) : [];
        $_students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
            ->join('enrollment_assessments', 'student_details.id', 'enrollment_assessments.student_id')
            ->leftJoin('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->where('enrollment_assessments.academic_id', auth()->user()->staff->current_academic()->id)
            ->whereNull('pa.enrollment_id');
        $_students = $_request->_course ? $_students->where('enrollment_assessments.course_id', base64_decode($_request->_course))->get() : $_students->get();
        $_students = $_request->_students ?   $_student_detials->student_search($_request->_students) : $_students;
        if ($_request->_midshipman) {
            if ($_ea = $_student->enrollment_assessment) {
                $_course_semestral_fee =  $_ea->course_semestral_fees($_ea); // Course Semestral Fee Table
                $_semestral_fees = $_course_semestral_fee ? $_course_semestral_fee->semestral_fees() : [];
                //return compact('_semestral_fees');
            } else {
                $_semestral_fees = [];
            }
        } else {
            $_semestral_fees = [];
            $_course_semestral_fee = [];
        }
        return view('pages.accounting.assessment.view', compact('_student', '_students', '_semestral_fees', '_course_semestral_fee'));
    }
    public function assessment_store(Request $_request)
    {
        $_details = array(
            'enrollment_id' => $_request->enrollment,
            'course_semestral_fee_id' => $_request->semestral_fees,
            'payment_mode' => $_request->mode,
            'staff_id' => auth()->user()->staff->id,
            'is_removed' => 0,
            'total_payment' => 0,
            'voucher_amount' => 0
        );
        $_payment_assessment = PaymentAssessment::where('enrollment_id', $_request->enrollment)->first();
        if (!$_payment_assessment) {
            PaymentAssessment::create($_details);
            return redirect(route('accounting.payment-transactions') . "?_midshipman=" . $_request->_student)->with('success', 'Payment Assessment Complete.');
        } else {

            return redirect(route('accounting.payment-transactions') . "?_midshipman=" . $_request->_student)->with('success', 'Payment Assessment Updated');
        }

        //return dd($_details);
    }

    public function semestral_clearance_view(Request $_request)
    {
        $_courses = CourseOffer::all();
        $_sections = $_request->_course ? Section::where('course_id', base64_decode($_request->_course))->where('is_removed', false)->where('academic_id', Auth::user()->staff->current_academic()->id)->orderBy('section_name', 'desc')->get() : [];
        return view('pages.accounting.semestral-clearance.view', compact('_courses', '_sections'));
    }
    public function semestral_student_list_view(Request $_request)
    {
        $_section = Section::find(base64_decode($_request->_section));
        return view('pages.accounting.semestral-clearance.student_section', compact('_section'));
    }
    public function semestral_clearance_store(Request $_request)
    {
        foreach ($_request->data as $key => $value) {
            $_student_id = base64_decode($value['sId']);
            $_clearance_data = $_request->_clearance_data;
            // Check if the student is Store
            $_check = count($value) > 2 ? 1 : 0;
            $_clearance = array(
                'student_id' => $_student_id,
                'non_academic_type' => $_clearance_data,
                'academic_id' => $_request->_academic,
                'comments' => $value['comment'], // nullable
                'staff_id' => Auth::user()->staff->id,
                'is_approved' => $_check, // nullable
                'is_removed' => 0
            );
            $_check_clearance = StudentNonAcademicClearance::where('student_id', $_student_id)->where('non_academic_type', $_clearance_data)->where('is_removed', false)->first();
            if ($_check_clearance) {
                // If the Data is existing and the approved status id TRUE and the Input Tag is TRUE : They will remain

                // If the Data is existing and the apprvod status is FALSE and the Input is FALSE : Nothing to Do, They will remain
                // If comment is fillable
                if ($_check_clearance->is_approved == 0 && $_check == 0) {
                    if ($value['comment']) {
                        $_check_clearance->comments = $value['comment'];
                        $_check_clearance->save();
                    }
                }
                // If the Data is existing and the approved status is TRUE and the Input is FALSE : The Data will removed and create a new one
                if ($_check_clearance->is_approved == 1 && $_check == 0) {
                    $_check_clearance->is_removed = true;
                    $_check_clearance->save();
                    StudentNonAcademicClearance::create($_clearance);
                }
                if ($_check_clearance->is_approved == 0 && $_check == 1) {
                    $_check_clearance->is_removed = true;
                    $_check_clearance->save();
                    StudentNonAcademicClearance::create($_clearance);
                }
            } else {
                StudentNonAcademicClearance::create($_clearance);
            }
            //echo "Saved: " . $_student_id . "<br>";
            $_student = StudentDetails::find($_student_id);
            $_student->offical_clearance_cleared();
        }
        return back()->with('success', 'Successfully Submitted Clearance');
    }

    public function payment_view(Request $_request)
    {
        $_student_detials = new StudentDetails();
        $_student = $_request->_midshipman ? StudentDetails::find(base64_decode($_request->_midshipman)) : [];
        $_vouchers = Voucher::where('is_removed', false)->get();
        $_students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
            ->join('enrollment_assessments as ea', 'ea.student_id', 'student_details.id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'ea.id')
            ->join('payment_trasanction_onlines as pto', 'pto.assessment_id', 'pa.id')
            ->where('ea.academic_id', Auth::user()->staff->current_academic()->id)
            ->whereNull('pto.is_approved')
            ->get();
        $_students = $_request->_students ? $_student_detials->student_search($_request->_students) : $_students;
        $_online_payment = $_request->payment_approved ? PaymentTrasanctionOnline::find(base64_decode($_request->payment_approved)) : null;
        return view('pages.accounting.payment.view', compact('_students', '_student', '_vouchers', '_online_payment'));
    }
    public function payment_store(Request $_request)
    {
        $_amount = str_replace(",", "", $_request->amount);
        $_tuition_fee_remarks = ['Tuition Fee', 'Upon Enrollment', '1ST MONTHLY', '2ND MONTHLY', '3RD MONTHLY', '4TH MONTHLY'];
        $_payment_transaction =  in_array($_request->remarks, $_tuition_fee_remarks) ? 'TUITION FEE' : 'ADDITIONAL FEE';
        if (!$_request->voucher) {
            $_request->validate([
                'or_number' => 'required',
                'amount' => 'required',
            ]);
            $_payment_details = array(
                'assessment_id' => $_request->_assessment,
                'or_number' => $_request->or_number,
                'payment_transaction' => $_payment_transaction,
                'payment_amount' => $_amount,
                'payment_method' => $_request->payment_method,
                'remarks' => $_request->remarks,
                'transaction_date' => $_request->tran_date ? $_request->tran_date : date('Y-m-d'),
                'staff_id' => Auth::user()->staff->id,
                'is_removed' => false
            );
        } else {
            $_vouchers = Voucher::find($_request->voucher);
            $_payment_assessment = PaymentAssessment::find($_request->_assessment);
            $_student_no = str_replace('-', '', $_payment_assessment->enrollment_assessment->student->account->student_number);
            $_payment_details = array(
                'assessment_id' => $_request->_assessment,
                'or_number' => $_vouchers->voucher_code . "." . $_student_no,
                'payment_transaction' => $_payment_transaction,
                'payment_amount' => $_vouchers->voucher_amount,
                'payment_method' => $_request->payment_method,
                'remarks' => $_request->remarks,
                'transaction_date' => $_request->tran_date ? $_request->tran_date : date('Y-m-d'),
                'staff_id' => Auth::user()->staff->id,
                'is_removed' => false
            );
        }
        $_payment = PaymentTransaction::create($_payment_details);
        if ($_request->_online_payment) {

            $_online_payment = PaymentTrasanctionOnline::find($_request->_online_payment);
            $_online_payment->payment_id = $_payment->id;
            $_online_payment->is_approved = 1;
            $_online_payment->or_number = $_request->or_number;
            $_online_payment->save();
        }

        return back()->with('success', 'Payment Transaction Complete!');
    }
    public function payment_verification(Request $_request)
    {
        $_online_payment = PaymentTrasanctionOnline::find($_request->_online_payment);
        $_online_payment->is_approved = 0;
        $_online_payment->comment_remarks =  $_request->remarks;
        $_online_payment->save();
        return back()->with('success', 'Transaction Complete');
    }
}
