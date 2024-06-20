<?php

namespace App\Http\Controllers;

use App\Exports\BalanceStudent;
use App\Exports\CollectionReport;
use App\Exports\DepartmentBalanceSheet;
use App\Exports\EnrolledStudentList;
use App\Exports\MonthlyCollectionReport;
use App\Exports\SalaryDetailsTemplate;
use App\Exports\WorkBook\MonthlyPaymentMonitoring;
use App\Imports\ImportSalaryDetails;
use App\Imports\StudentTransactionHistoryImport;
use App\Mail\ApplicantEmail;
use App\Models\AcademicYear;
use App\Models\AdditionalFees;
use App\Models\ApplicantAccount;
use App\Models\ApplicantPayment;
use App\Models\CourseOffer;
use App\Models\CourseSemestralFees;
use App\Models\Curriculum;
use App\Models\EnrollmentAssessment;
use App\Models\ParticularFees;
use App\Models\Particulars;
use App\Models\PaymentAdditionalTransaction;
use App\Models\PaymentAssessment;
use App\Models\PaymentTransaction;
use App\Models\PaymentTrasanctionOnline;
use App\Models\Section;
use App\Models\SemestralFee;
use App\Models\Staff;
use App\Models\StaffPayroll;
use App\Models\StaffPayrollDetails;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use App\Models\StudentNonAcademicClearance;
use App\Models\StudentSection;
use App\Models\VoidTransaction;
use App\Models\Voucher;
use App\Report\Accounting\PaymentReceipt as AccountingPaymentReceipt;
use App\Report\Accounting\PaymentReport;
use App\Report\Accounting\PaymentReports;
use App\Report\AttendanceSheetReport;
use App\Report\PayrollReport;
use App\Report\StudentListReport;
use App\Report\Students\StudentReport;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Query\Expression;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class AccountingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('accounting');
        set_time_limit(8000000);
    }
    public function index()
    {
        $_courses = CourseOffer::where('is_removed', false)->orderBy('id', 'desc')->get();
        $_total_population = Auth::user()->staff->enrollment_count();
        // $_total_applicants = ApplicantAccount::join('applicant_detials', 'applicant_detials.applicant_id', 'applicant_accounts.id')->where('academic_id', Auth::user()->staff->current_academic()->id)->where('applicant_accounts.is_removed', false)->get();
        return view('pages.accounting.dashboard.view', compact('_courses', '_total_population'));
    }
    public function payment_pending_view(Request $_request)
    {
        $_course = CourseOffer::find(base64_decode($_request->_course));
        return view('pages.accounting.dashboard.payment-assessment', compact('_course'));
    }
    public function enrolled_list(Request $_request)
    {
        $_course = CourseOffer::find(base64_decode($_request->_course));
        $_students = $_request->_year_level ?  $_course->enrollment_list_by_year_level($_request->_year_level)->get() : $_course->enrollment_list;
        $_file_name = $_course->course_name . "-" . Auth::user()->staff->current_academic()->school_year . '-' . strtoupper(str_replace(' ', '-', Auth::user()->staff->current_academic()->semester)) . '.csv';
        //return Excel::download(new EnrolledStudentList($_course), $_file_name); // Download the File
        $_respond =  Excel::download(new EnrolledStudentList($_course), $_file_name . '.xlsx', \Maatwebsite\Excel\Excel::XLSX); // Download the File
        ob_end_clean();
        return $_respond;
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
        $fees = AdditionalFees::where('is_removed', false)->get();
        $particulars = Particulars::where('particular_tag', 'addition_tags')->where('is_removed', false)->get();
        return view('pages.accounting.fee.view', compact('_courses', 'fees', 'particulars'));
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
        //return $_course_fee->semestral_fee_list;
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
        $_particulars = Particulars::where('department', $_department)->where('is_removed', false)->where('particular_tag', '!=', 'addition_tags')->get();
        $_curriculum = Curriculum::all();
        $_course = CourseOffer::find(base64_decode($_request->_course));
        //$_courses = CourseOffer::where('is_removed', false)->get();
        return view('pages.accounting.fee.create_semestral_fee', compact('_particulars', '_curriculum', '_course'));
    }
    public function course_fee_store(Request $_request)
    {

        $_request->validate([
            '_year_level' => 'required',
            '_curriculum' => 'required'
        ]);
        try {
            $_details = array(
                'course_id' => base64_decode($_request->_course),
                'curriculum_id' => $_request->_curriculum,
                'year_level' => $_request->_year_level,
                'academic_id' => $_request->_academic,
                'is_removed' => false,
            ); // Set up the data content for storing Course Semestrarl Fees
            //$_course_semestral = CourseSemestralFees::where($_details)->first(); // Verify if the Content is already Store/Save
            // if the content get the detials or the id, if not the content store into database
            $_course_semestral = /* $_course_semestral ?: */ CourseSemestralFees::create($_details);

            foreach ($_request->data as $key => $value) {
                // Check if the Fees is have a valueƒ
                if ($value['fee'] != null) {
                    // Verify the Particular Fee is already Save
                    $_particular_fees = ParticularFees::where('particular_id', $value['particular'])->where('particular_amount', $value['fee'])->where('academic_id', $_request->_academic)->first();
                    // If the Particular Fees get the Details, if not the Content will Save to database
                    // Get the Particular Details
                    $_particulars = $_particular_fees ?: ParticularFees::create([
                        'particular_id' => $value['particular'],
                        'particular_amount' => $value['fee'],
                        'academic_id' => $_request->_academic,
                        'is_removed' => false
                    ]);
                    // Semestral Fees Setup
                    $_content = array(
                        'particular_fee_id' => $_particulars->id,
                        'course_semestral_fee_id' => $_course_semestral->id,
                        'is_removed' => false
                    );
                    // Save the Semestrarl Fees
                    SemestralFee::where($_content)->first() ?:
                        SemestralFee::create($_content);
                } else {
                    if ($value['id'] != null) {
                        $_content = array(
                            'particular_fee_id' => $value['id'],
                            'course_semestral_fee_id' => $_course_semestral->id,
                            'is_removed' => false
                        );
                        SemestralFee::where($_content)->first() ?:
                            SemestralFee::create($_content);
                    }
                }
            }
            // Redirect to the Semestral Fee View
            return redirect(route('accounting.course-fee-view-list') . '?_course_fee=' . base64_encode($_course_semestral->id))->with('success', 'Successfully Create a Semestral Tuition Fee');
        } catch (Exception $err) {
            return $err->getMessage();
            return back()->with('error', $err->getMessage());
        }
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
        try {
            $_student_detials = new StudentDetails();
            $_student = $_request->midshipman ? StudentDetails::find(base64_decode($_request->midshipman)) : [];
            $_students = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
                ->join('enrollment_assessments', 'student_details.id', 'enrollment_assessments.student_id')
                ->leftJoin('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
                ->where('enrollment_assessments.academic_id', auth()->user()->staff->current_academic()->id)
                ->whereNull('pa.enrollment_id');
            $_students = $_request->_course ? $_students->where('enrollment_assessments.course_id', base64_decode($_request->_course))->get() : $_students->get();
            $_students = $_request->_students ?   $_student_detials->student_search($_request->_students) : $_students;
            if ($_request->midshipman) {
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
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function assessment_store(Request $_request)
    {
        try {
            $enrollment_assessment = EnrollmentAssessment::find($_request->enrollment);
            $tuition_fees = $enrollment_assessment->course_level_tuition_fee();
            if ($_request->mode == 1) {
                // Installment
                $total_tuitionfee =  $tuition_fees->total_tuition_fees_with_interest($enrollment_assessment);
                $upon_enrollment = $tuition_fees->upon_enrollment_v2($enrollment_assessment);
                $monthly_payment = $tuition_fees->monthly_fees_v2($enrollment_assessment);
            } else {
                //FullPayment
                $total_tuitionfee = $tuition_fees->total_tuition_fees($enrollment_assessment);;
                $upon_enrollment = $tuition_fees->total_tuition_fees($enrollment_assessment);;
                $monthly_payment = 0;
            }
            $_details = array(
                'enrollment_id' => $_request->enrollment,
                'course_semestral_fee_id' => $_request->semestral_fees,
                'payment_mode' => $_request->mode,
                'staff_id' => auth()->user()->staff->id,
                'total_payment' => $total_tuitionfee,
                'upon_enrollment' => $upon_enrollment,
                'monthly_payment' => $monthly_payment,
                'voucher_amount' => 0,
                'is_removed' => 0
            );
            $_payment_assessment = PaymentAssessment::where('enrollment_id', $_request->enrollment)->first();
            if (!$_payment_assessment) {
                PaymentAssessment::create($_details);
                return redirect(route('accounting.payment-transactions') . "?midshipman=" . $_request->_student)->with('success', 'Payment Assessment Complete.');
            } else {
                $_payment_assessment->course_semestral_fee_id =  $_request->semestral_fees;
                $_payment_assessment->payment_mode =  $_request->mode;
                $_payment_assessment->total_payment =  $total_tuitionfee;
                $_payment_assessment->upon_enrollment =  $upon_enrollment;
                $_payment_assessment->monthly_payment =  $monthly_payment;
                $_payment_assessment->save();
                return redirect(route('accounting.payment-transactions') . "?midshipman=" . $_request->_student)->with('success', 'Payment Assessment Updated');
            }
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
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
        try {
            $_student_detials = new StudentDetails();
            $_student = $_request->midshipman ? StudentDetails::find(base64_decode($_request->midshipman)) : [];
            $_vouchers = Voucher::where('is_removed', false)->get();
            $_students = StudentDetails::select('student_details.*')
                ->join('enrollment_assessments', 'enrollment_assessments.student_id', 'student_details.id')
                ->where('enrollment_assessments.is_removed', false)
                ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
                ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
                ->where('payment_assessments.is_removed', false)
                ->join('payment_trasanction_onlines', 'payment_trasanction_onlines.assessment_id', 'payment_assessments.id')
                ->whereNull('payment_trasanction_onlines.is_approved')
                ->where('payment_trasanction_onlines.is_removed', false)
                ->groupBy('student_details.id')->paginate(5);
            $_additional_fees = StudentDetails::select('student_details.*')
                ->join('enrollment_assessments', 'enrollment_assessments.student_id', 'student_details.id')
                ->join('payment_additional_transactions', 'payment_additional_transactions.enrollment_id', 'enrollment_assessments.id')
                ->where('payment_additional_transactions.is_removed', false)
                ->whereNull('payment_additional_transactions.is_approved')
                ->groupBy('enrollment_assessments.student_id')->paginate(0);
            $_students = $_request->_students ? $_student_detials->student_search($_request->_students) : $_students;
            $_students = $_request->_payment_category == 'additional-payment' ? $_additional_fees : $_students;
            $_online_payment = $_request->payment_approved ? PaymentTrasanctionOnline::find(base64_decode($_request->payment_approved)) : null;

            //return $_student->enrollment_status();
            return view('pages.accounting.payment.view', compact('_students', '_student', '_vouchers', '_online_payment'));
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function payment_store(Request $_request)
    {
        try {


            $_amount = str_replace(",", "", $_request->amount);
            $_tuition_fee_remarks = ['Tuition Fee', 'Upon Enrollment', '1ST MONTHLY', '2ND MONTHLY', '3RD MONTHLY', '4TH MONTHLY'];
            $_payment_transaction =  in_array($_request->remarks, $_tuition_fee_remarks) ? 'TUITION FEE' : 'ADDITIONAL FEE';
            if (!$_request->voucher) {
                // If this Transaction is without a Voucher we proceed to the Storing of Transaction Details
                $_request->validate([
                    'or_number' => 'required',
                    'amount' => 'required',
                ]); // Validate the Or-Number and Payment Amount
                $_payment_details = array(
                    'assessment_id' => $_request->_assessment,
                    'or_number' => $_request->or_number,
                    'payment_transaction' => $_payment_transaction,
                    'payment_amount' => $_amount,
                    'payment_method' => $_request->payment_method,
                    'remarks' => $_request->remarks,
                    'transaction_date' => $_request->tran_date ?: date('Y-m-d'),
                    'staff_id' => Auth::user()->staff->id,
                    'is_removed' => false
                );
            } else {
                // If the Transaction is with Voucher, Find the Voucher id
                $_vouchers = Voucher::find($_request->voucher);
                $_payment_assessment = PaymentAssessment::find($_request->_assessment); // Get the Payment Assessment
                $_payment_details = PaymentAssessment::find($_request->_assessment);
                $_voucher_amount = $_vouchers->voucher_code == "TCC" ? $_payment_details->course_semestral_fee->total_payments($_payment_details) : $_vouchers->voucher_amount;
                $_student_no = str_replace('-', '', $_payment_assessment->enrollment_assessment->student->account->student_number);
                $_payment_details = array(
                    'assessment_id' => $_request->_assessment,
                    'or_number' => $_vouchers->voucher_code . "." . $_student_no,
                    'payment_transaction' => $_payment_transaction,
                    'payment_amount' => $_voucher_amount,
                    'payment_method' => $_request->payment_method,
                    'remarks' => $_request->remarks,
                    'transaction_date' => $_request->tran_date ? $_request->tran_date : date('Y-m-d'),
                    'staff_id' => Auth::user()->staff->id,
                    'is_removed' => false
                );
            }
            $_payment = PaymentTransaction::create($_payment_details);

            // If the Student have Online Payment Transaction
            if ($_request->_online_payment) {
                $_online_payment = PaymentTrasanctionOnline::find($_request->_online_payment);
                $_online_payment->payment_id = $_payment->id;
                $_online_payment->is_approved = 1;
                $_online_payment->or_number = $_request->or_number;
                $_online_payment->save();
            }
            // Sectioning & Student number for new student
            if ($_request->remarks == 'Upon Enrollment' && $_payment) {
                $_payment_assessment = PaymentAssessment::find($_request->_assessment);
                $_section = $_payment_assessment->enrollment_assessment->find_section(); // Find the Sections
                if ($_section) {
                    $_validate_student_section = StudentSection::where('section_id', $_section->id)->where('student_id', $_payment_assessment->enrollment_assessment->student_id)->where('is_removed', false)->first(); // Verify if the Student will save on Section
                    if (!$_validate_student_section) {
                        StudentSection::create([
                            'student_id' => $_payment_assessment->enrollment_assessment->student_id,
                            'section_id' => $_section->id,
                            'created_by' => 'Auto Section',
                            'is_removed' => 0,
                        ]);
                    }
                }
                if (!$_payment_assessment->enrollment_assessment->student->account) {
                    // Get the Year Level
                    $_year_level = $_payment_assessment->enrollment_assessment->year_level;
                    // Get the Total Number of Enrollee per Year Level
                    $_enrollment_count = EnrollmentAssessment::where('enrollment_assessments.is_removed', false)
                        ->where('enrollment_assessments.year_level', $_year_level)
                        ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
                        ->groupBy('enrollment_assessments.student_id')
                        ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
                        ->join('payment_transactions', 'payment_transactions.assessment_id', 'payment_assessments.id')
                        ->groupBy('enrollment_assessments.student_id')->get();
                    $_enrollment_count = count($_enrollment_count);
                    // Set the student number
                    $student_count = $_enrollment_count > 10 ? ($_enrollment_count >= 100 ? $_enrollment_count : '0' . $_enrollment_count) : '00' . $_enrollment_count;
                    $pattern = $_year_level == 11 ? '07-' . date('y') : date('y'); // Set the Year and Batch
                    $student_number = $pattern . $student_count; // Final Student Number
                    $email = $student_number . '.' . str_replace(' ', '', strtolower($_payment_assessment->enrollment_assessment->student->last_name)) . '@bma.edu.ph'; // Set Email
                    // Set the value for Student Account
                    $_account_details = array(
                        'student_id' => $_payment_assessment->enrollment_assessment->student_id,
                        'email' => $email,
                        'personal_email' => $email,
                        'student_number' => $student_number,
                        'password' => Hash::make($student_number),
                        'is_actived' => true,
                        'is_removed' => false,
                    );
                    StudentAccount::create($_account_details);
                    if ($_year_level == 11 && $_year_level == 4) {
                        StudentAccount::create($_account_details);
                    }
                }
            }

            /*  if ($_request->remarks == 'Upon Enrollment') {
                // Get the Assessment Detials
                $_payment_assessment = PaymentAssessment::find($_request->_assessment);
                // HOW TO IDENTIFY THE COUNT NUMBER OF THE STUDENT
                //return $_payment_assessment->enrollment_assessment->academic;
                if ($_payment_assessment->enrollment_assessment->academic->semester == 'First Semester') {
                    if ($_payment_assessment->enrollment_assessment->year_level == '4') {
                        $_enrollment_count = EnrollmentAssessment::where('enrollment_assessments.is_removed', false)
                            ->where('enrollment_assessments.year_level', '4')
                            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
                            ->groupBy('enrollment_assessments.student_id')
                            ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
                            ->join('payment_transactions', 'payment_transactions.assessment_id', 'payment_assessments.id')
                            ->groupBy('enrollment_assessments.student_id')->get();
                        $_enrollment_count = count($_enrollment_count);
                        $_student_number = $_enrollment_count > 10 ? ($_enrollment_count >= 100 ? $_enrollment_count : '0' . $_enrollment_count) : '00' . $_enrollment_count;
                        $_email =  date("y") . $_student_number . '.' . str_replace(' ', '', strtolower($_payment_assessment->enrollment_assessment->student->last_name)) . '@bma.edu.ph';
                        $_student_number = date("y") . $_student_number;
                        $_account_details = array(
                            'student_id' => $_payment_assessment->enrollment_assessment->student_id,
                            'email' => $_email,
                            'personal_email' => $_email,
                            'student_number' => $_student_number,
                            'password' => Hash::make($_student_number),
                            'is_actived' => true,
                            'is_removed' => false,
                        );
                        if ($_payment_assessment->enrollment_assessment->student->account) {
                            $_payment_assessment->enrollment_assessment->student->account->is_actived = false;
                            $_payment_assessment->enrollment_assessment->student->account->save();
                            StudentAccount::create($_account_details);
                        } else {
                            StudentAccount::create($_account_details);
                        }
                    }
                    if ($_payment_assessment->enrollment_assessment->year_level == 11) {
                        $_enrollment_count = EnrollmentAssessment::where('enrollment_assessments.is_removed', false)
                            ->where('enrollment_assessments.year_level', 11)
                            ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
                            ->groupBy('enrollment_assessments.student_id')
                            ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
                            ->join('payment_transactions', 'payment_transactions.assessment_id', 'payment_assessments.id')
                            ->groupBy('enrollment_assessments.student_id')->get();
                        $_enrollment_count = count($_enrollment_count);
                        $_student_number = $_enrollment_count > 10 ? ($_enrollment_count >= 100 ? $_enrollment_count : '0' . $_enrollment_count) : '00' . $_enrollment_count;
                        $_email =  date("y") . $_student_number . '.' . str_replace(' ', '', strtolower($_payment_assessment->enrollment_assessment->student->last_name)) . '@bma.edu.ph';
                        $_student_number = date("y") . $_student_number;
                        $_account_details = array(
                            'student_id' => $_payment_assessment->enrollment_assessment->student_id,
                            'email' => $_email,
                            'personal_email' => $_email,
                            'student_number' => $_student_number,
                            'password' => Hash::make($_student_number),
                            'is_actived' => true,
                            'is_removed' => false,
                        );
                        if ($_payment_assessment->enrollment_assessment->student->account) {
                            $_payment_assessment->enrollment_assessment->student->account->is_actived = false;
                            $_payment_assessment->enrollment_assessment->student->account->save();
                            StudentAccount::create($_account_details);
                        } else {
                            StudentAccount::create($_account_details);
                        }
                    }
                }
                // Year Level
                $_year_level = $_payment_assessment->enrollment_assessment->course_id == 3 ? 'GRADE ' . $_payment_assessment->enrollment_assessment->year_level :
                    $_payment_assessment->enrollment_assessment->year_level . '/C';
                // Find Section
                $_section =   Section::where('academic_id', Auth::user()->staff->current_academic()->id)
                    ->where('course_id', $_payment_assessment->enrollment_assessment->course_id)
                    ->where('year_level', $_year_level)
                    ->where('section_name', 'not like', '%BRIDGING%')
                    ->where('is_removed', false)
                    ->where(function ($_sub_query) {
                        $_sub_query->select(DB::raw('count(*)'))->from('student_sections')
                            ->whereColumn('student_sections.section_id', 'sections.id')
                            ->where('student_sections.is_removed', false);
                    }, '<', 40)
                    ->first();
                if ($_section) {
                    $_content = array(
                        'student_id' => $_payment_assessment->enrollment_assessment->student_id,
                        'section_id' => $_section->id,
                        'created_by' => 'Auto Section',
                        'is_removed' => 0,
                    );
                    StudentSection::create($_content); // Store Student Section
                }
            } */
            return back()->with('success', 'Payment Transaction Complete!');
        } catch (Exception $error) {
            return $error->getMessage();
            // return back()->with('error', $error->getMessage());
        }
    }
    public function payment_store_v1(Request $_request)
    {
        try {
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
                $_payment_details = PaymentAssessment::find($_request->_assessment);
                $_voucher_amount = $_vouchers->voucher_code == "TCC" ? $_payment_details->course_semestral_fee->total_payments($_payment_details) : $_vouchers->voucher_amount;
                $_student_no = str_replace('-', '', $_payment_assessment->enrollment_assessment->student->account->student_number);
                $_payment_details = array(
                    'assessment_id' => $_request->_assessment,
                    'or_number' => $_vouchers->voucher_code . "." . $_student_no,
                    'payment_transaction' => $_payment_transaction,
                    'payment_amount' => $_voucher_amount,
                    'payment_method' => $_request->payment_method,
                    'remarks' => $_request->remarks,
                    'transaction_date' => $_request->tran_date ? $_request->tran_date : date('Y-m-d'),
                    'staff_id' => Auth::user()->staff->id,
                    'is_removed' => false
                );
            }
            $_payment = PaymentTransaction::create($_payment_details);

            // If the Student have Online Payment Transaction
            if ($_request->_online_payment) {
                $_online_payment = PaymentTrasanctionOnline::find($_request->_online_payment);
                $_online_payment->payment_id = $_payment->id;
                $_online_payment->is_approved = 1;
                $_online_payment->or_number = $_request->or_number;
                $_online_payment->save();
            }
            // Sectioning & Student number for new student
            if ($_request->remarks == 'Upon Enrollment') {
                // Get the Assessment Detials
                $_payment_assessment = PaymentAssessment::find($_request->_assessment);
                // Year Level
                $_year_level = $_payment_assessment->enrollment_assessment->course_id == 3 ? 'GRADE ' . $_payment_assessment->enrollment_assessment->year_level :
                    $_payment_assessment->enrollment_assessment->year_level . '/C';
                // Find Section
                $_section =   Section::where('academic_id', Auth::user()->staff->current_academic()->id)
                    ->where('course_id', $_payment_assessment->enrollment_assessment->course_id)
                    ->where('year_level', $_year_level)
                    ->where('section_name', 'not like', '%BRIDING%')
                    ->where('is_removed', false)
                    ->where(function ($_sub_query) {
                        $_sub_query->select(DB::raw('count(*)'))->from('student_sections')
                            ->whereColumn('student_sections.section_id', 'sections.id')
                            ->where('student_sections.is_removed', false);
                    }, '<', 40)
                    ->first();
                if ($_section) {
                    $_content = array(
                        'student_id' => $_payment_assessment->enrollment_assessment->student_id,
                        'section_id' => $_section->id,
                        'created_by' => 'Auto Section',
                        'is_removed' => 0,
                    );
                    StudentSection::create($_content); // Store Student Section
                }
                // If the Student don't have BMA Account and Student number
                return $_payment_assessment->enrollment_assessment->student;
                if (!$_payment_assessment->enrollment_assessment->student->account) {
                    // Get the Year Level
                    $_year_level = $_payment_assessment->enrollment_assessment->year_level;
                    // Get the Total Number of Enrollee per Year Level
                    $_enrollment_count = EnrollmentAssessment::where('enrollment_assessments.is_removed', false)
                        ->where('enrollment_assessments.year_level', $_year_level)
                        ->where('enrollment_assessments.academic_id', Auth::user()->staff->current_academic()->id)
                        ->groupBy('enrollment_assessments.student_id')
                        ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
                        ->join('payment_transactions', 'payment_transactions.assessment_id', 'payment_assessments.id')
                        ->groupBy('enrollment_assessments.student_id')->get();
                    $_enrollment_count = count($_enrollment_count);
                    // Set the student number
                    $student_count = $_enrollment_count > 10 ? ($_enrollment_count >= 100 ? $_enrollment_count : '0' . $_enrollment_count) : '00' . $_enrollment_count;
                    $pattern = $_year_level == 11 ? '07-' . date('y') : date('y'); // Set the Year and Batch
                    $student_number = $pattern . $student_count; // Final Student Number
                    $email = $student_number . '.' . str_replace(' ', '', strtolower($_payment_assessment->enrollment_assessment->student->last_name)) . '@bma.edu.ph'; // Set Email
                    // Set the value for Student Account
                    $_account_details = array(
                        'student_id' => $_payment_assessment->enrollment_assessment->student_id,
                        'email' => $email,
                        'personal_email' => $email,
                        'student_number' => $student_number,
                        'password' => Hash::make($student_number),
                        'is_actived' => true,
                        'is_removed' => false,
                    );
                    StudentAccount::create($_account_details);
                    if ($_year_level == 11 && $_year_level == 4) {
                        StudentAccount::create($_account_details);
                    }
                }
            }
            // return back()->with('success', 'Payment Transaction Complete!');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function payment_verification(Request $_request)
    {
        $_online_payment = PaymentTrasanctionOnline::find($_request->_online_payment);
        $_online_payment->is_approved = 0;
        $_online_payment->comment_remarks =  $_request->remarks;
        $_online_payment->save();
        return back()->with('success', 'Transaction Complete');
    }
    public function online_payment_transaction_removed(Request $_request)
    {
        try {
            $_transaction = PaymentTrasanctionOnline::find(base64_decode($_request->transaction));
            $_transaction->is_removed = 1;
            $_transaction->save();
            return back()->with('success', 'Successfully Removed');
        } catch (Exception $error) {
            return back()->with('error', $error->getMessage());
        }
    }
    public function applicant_transaction_view(Request $_request)
    {
        try {
            $_applicants = new ApplicantAccount;
            $_payment_transaction = $_request->_applicants ? $_applicants->search_applicants() :  $_applicants->applicant_payments();
            $_student = $_request->_applicant ? ApplicantAccount::find(base64_decode($_request->_applicant)) : [];
            $_applicant_payment = $_request->payment_approved ?  ApplicantPayment::find(base64_decode($_request->payment_approved)) : [];


            return view('pages.accounting.applicant.view', compact('_payment_transaction', '_student', '_applicant_payment'));
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function applicant_transaction_verification(Request $_request)
    {
        $_payment = ApplicantPayment::find(base64_decode($_request->transaction));
        $_applicant = new ApplicantEmail();
        if ($_request->status == 'approved') {
            $_payment->is_approved = 1;
            $_payment->or_number = $_request->or_number;
            $_payment->save();
            //return $_payment->account->email;
            //Mail::to($_payment->account->email)->send($_applicant->payment_approved(ApplicantAccount::find($_payment->applicant_id)));
            Mail::to('banting.percival17@gmail.com')->send($_applicant->payment_approved(ApplicantAccount::find($_payment->applicant_id)));
            // $_applicant->payment_approved(ApplicantAccount::find($_payment->applicant_id));
        }
        if ($_request->status == 'disapproved') {
            $_payment->is_approved = 0;
            $_payment->comment_remarks = $_request->remarks;
            $_payment->save();
        }
        return back()->with('success', 'Successfully Transact!');
    }
    public function applicant_transaction_store(Request $_request)
    {
        $_request->validate([
            'or_number' => 'required',
            'amount' => 'required'
        ]);
        ApplicantPayment::create([
            'applicant_id' => $_request->applicant,
            'amount_paid' => $_request->amount,
            'reference_number' => $_request->or_number,
            'reciept_attach_path' => $_request->payment_method,
            'transaction_type' => $_request->remarks,
            'or_number' => $_request->or_number,
            'is_approved' => 1
        ]);
        // Send Email Notification
        $_applicant = new ApplicantEmail();
        $applicant = ApplicantAccount::find($_request->applicant);
        Mail::to('banting.percival17@gmail.com')->send($_applicant->payment_approved($applicant));
        //Mail::to($applicant->email)->send($_applicant->payment_approved($applicant));
        return back()->with('success', 'Successfully Transact!');
    }
    public function staff_payroll_view(Request $_request)
    {
        $_payroll = StaffPayroll::where('is_removed', false)->get();
        return view('pages.accounting.payroll.view', compact('_payroll'));
    }
    public function staff_salary_details(Request $_request)
    {
        $_employees = Staff::select('staff.*')
            ->orderBy('staff.last_name', 'asc')->where('is_removed', false)->get();
        return view('pages.accounting.payroll.employee_list', compact('_employees'));
    }
    public function staff_salary_details_template(Request $_request)
    {
        $_respond =  Excel::download(new SalaryDetailsTemplate, 'Employee-Salary-Detials' . '.xlsx', \Maatwebsite\Excel\Excel::XLSX); // Download the File
        ob_end_clean();
        return $_respond;
    }
    public function upload_salary_details(Request $_request)
    {
        Excel::import(new ImportSalaryDetails, $_request->file('_file'));
        return back()->with('success', 'Successfully Upload Employees Salary Details');
    }
    public function payroll_store(Request $_request)
    {
        $_request->validate([
            'cutoff_range' => 'required',
            'month' => 'required'
        ]);
        $_details =  array('period' => $_request->cutoff_range, 'cut_off' => $_request->month . "-01");
        $_payroll = StaffPayroll::create($_details);
        $_employees = Staff::select('staff.*')
            ->orderBy('staff.last_name', 'asc')->where('is_removed', false)->get();
        foreach ($_employees as $key => $data) {
            $_details = array('payroll_id' => $_payroll->id, 'salary_id' => $data->id);
            $_payroll_details = StaffPayrollDetails::where($_details)->first();
            if (!$_payroll_details) {
                StaffPayrollDetails::create($_details);
            }
        }
        return back()->with('success', 'Successfully Create Payroll');
    }
    public function payroll_view(Request $_request)
    {
        if ($_request->_payroll) {
            $_payroll = StaffPayroll::find(base64_decode($_request->_payroll));
            $_employees = Staff::select('staff.*')
                ->orderBy('staff.last_name', 'asc')->where('is_removed', false)->get();
            return view('pages.accounting.payroll.payroll_view', compact('_payroll', '_employees'));
        } else {
            return redirect(route('accounting.payroll-view'));
        }
    }
    public function payroll_generated_report(Request $_request)
    {
        $_report = new PayrollReport;
        $_report->payroll_generated_report_without();
    }
    public function generate_report_view()
    {
        $_academic = AcademicYear::where('is_removed', false)->orderBy('id', 'desc')->get();
        return view('pages.accounting.generate-report.view', compact('_academic'));
    }
    public function colletion_report(Request $_request)
    {
        try {
            $_request->validate([
                'collection_type' => 'required',
                'collection_date' => 'required'
            ]);
            $_type = $_request->collection_type; // Category
            $_date = $_request->collection_date; // Date
            $_date = $_type == "monthly" ?  date_format(date_create($_date), "Y-m") : date_format(date_create($_date), "Y-m-d");
            $_file_name = strtoupper($_type) . '-COLLECTION-' . $_date . '.xlsx'; // Name of the File
            $_class = $_type === 'daily' ? new CollectionReport($_date) : new MonthlyCollectionReport($_date); // Get the Export Class
            //$this->activities->setActivity(['Generate Collection with file name' . $_file_name, 'ACCOUNTING']);
            $_file = Excel::download($_class, $_file_name); // Download the File
            ob_end_clean();
            return $_file;
            /*   $_respond =  Excel::download(new SalaryDetailsTemplate, 'Employee-Salary-Detials' . '.xlsx', \Maatwebsite\Excel\Excel::XLSX); // Download the File
            ob_end_clean();
            return $_respond; */
        } catch (Expression $er) {
            return back()->with('error', $er);
        }
    }
    public function balance_report(Request $_request)
    {
        try {
            $_course = CourseOffer::find($_request->balance_course); // Course
            $_data = $_request->balance_level; // Date
            $_academic = $_request->collection_academic; // Academic
            $_date = Carbon::today();
            $_file_name = strtoupper($_course->course_code) . '-BALANCE-' . strtoupper($_data) . '-' . $_date . '.xlsx'; // Name of the File
            //return $_course->enrolled_list(1)->get();
            $_class = $_data == "all" ? new DepartmentBalanceSheet($_course) : new BalanceStudent($_course, $_data); // Get the Export Class
            $_file = Excel::download($_class, $_file_name); // Download the File
            ob_end_clean();
            return $_file;
            //$this->activities->setActivity(['Generate Collection with file name' . $_file_name, 'ACCOUNTING']);
            // /return Excel::download($_class, $_file_name); // Download the File
        } catch (Expression $er) {
            return back()->with('error', $er);
        }
    }


    // Bridging Program Payments

    public function payment_disapproved(Request $_request)
    {
        try {
            $_online_payment = PaymentAdditionalTransaction::find($_request->_online_payment);
            $_online_payment->is_approved = 0;
            $_online_payment->comment_remarks =  $_request->remarks;
            $_online_payment->save();
            return back()->with('success', 'Transaction Complete');
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function payment_approved(Request $_request)
    {
        try {
            $_online_payment = PaymentAdditionalTransaction::find($_request->_online_payment);
            $_online_payment->is_approved = 1;
            $_online_payment->or_number =  $_request->or_number;
            $_online_payment->save();
            return back()->with('success', 'Transaction Complete');
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function payment_print_receipt(Request $_request)
    {
        try {
            $_payment = PaymentTransaction::find(base64_decode($_request->reciept));
            $_reciept_report = new AccountingPaymentReceipt();
            return $_reciept_report->print($_payment);
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function payment_print_or_receipt(Request $request)
    {
        try {
            $transactions = PaymentTransaction::where('or_number', base64_decode($request->orNumber))->get();
            // GET THE STUDENT INFORMATION
            $student = $transactions[0]->payment_assessment->enrollment_assessment->student;
            $totalAmount = 0;
            $particularsDetails = [];
            $particular = ['Uniform', 'Books', 'Forms'];
            $temp = [];
            foreach ($transactions as $key => $value) {
                $totalAmount += $value->payment_amount;
                $temp[] = $value->remarks;
                if ($value->payment_transaction === 'TUITION FEE') {
                    $particularsDetails = array(
                        'name' => 'TUITION FEE',
                        'amount' => $value->payment_amount
                    );
                } else {
                }
            }
            $remarks = $this->arrayToSentence($temp);
            // Student Name and Student Number
            $fullname = strtoupper($student->last_name . ', ' . $student->first_name);
            $student_number = $student->account->student_number;
            $staff = $this->getInitials($transactions[0]->staff->first_name) . ' ' . $transactions[0]->staff->last_name;
            // OR NUMBER AND TRANSACTION DATE
            $orNumber = base64_decode($request->orNumber);
            $transactionDate = $transactions[0]->transaction_date;
            $transactions = PaymentTransaction::where('or_number', base64_decode($request->orNumber))->get();
            $receiptDetails =  compact('fullname', 'student_number', 'transactionDate', 'orNumber', 'totalAmount', 'remarks', 'transactions', 'staff');
            //return $receiptDetails;
            $_reciept_report = new AccountingPaymentReceipt();
            return $_reciept_report->print_or_recipt($receiptDetails, $request->printer);
        } catch (Exception $err) {
            return $err->getMessage();
            // return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    function arrayToSentence($array)
    {
        $count = count($array);

        // If there's only one item in the array, return it directly
        if ($count === 1) {
            return $array[0];
        }

        // If there are two or more items, concatenate all but the last with commas,
        // then add "and" before the last item
        $lastItem = array_pop($array);
        $sentence = implode(', ', $array) . " & " . $lastItem;

        return $sentence;
    }
    function getInitials($name)
    {
        // Split the name into an array of words
        $words = explode(' ', $name);

        // Initialize an empty string to store initials
        $initials = '';

        // Loop through each word
        foreach ($words as $word) {
            // Add the first letter of each word to the initials string
            $initials .= strtoupper(substr($word, 0, 1));
        }

        // Return the initials
        return $initials;
    }
    public function student_card(Request $_request)
    {
        try {
            $_student = StudentDetails::find(base64_decode($_request->student));
            $_report = new StudentReport();
            return $_report->student_card_report($_student);
        } catch (Exception $err) {
            return $err;
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function student_transaction_import(Request $_request)
    {
        try {
            if ($_request->file('upload-file')) {
                //$_file_extention =  $_request->file('upload-file')->getClientOriginalExtension();
                //$_file_name = "/registrar/section-import/" . strtoupper(str_replace(' ', '-', str_replace('/', '', $_section->section_name))) . date('dmyhis') . '.' . $_file_extention;
                //   Storage::disk('public')->put($_file_name, fopen($_request->file('upload-file'), 'r+'));
                Excel::import(new StudentTransactionHistoryImport(), $_request->file('upload-file'));
                // return back()->with('success', 'Successfully Uploaded');
            }
        } catch (Exception $err) {
            return $err;
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function payment_transaction_void(Request $_request)
    {
        try {
            $_void_details = array(
                'payment_id' => base64_decode($_request->payment),
                'void_reason' => base64_encode($_request->reason),
            );
            VoidTransaction::create($_void_details);
            return back()->with('success', 'Void Transaction Pending');
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function void_view()
    {
        $_void_list = VoidTransaction::where('is_removed', false)->orderBy('id', 'desc')->get();
        return view('pages.accounting.void.view', compact('_void_list'));
    }
    public function void_transaction(Request $_request)
    {
        try {
            $_transaction = VoidTransaction::find(base64_decode($_request->void));
            $_staff = Auth::user()->staff;
            if ("ACCOUNTING" == $_staff->department && "DEPARTMENT HEAD" == $_staff->job_description) {
                //return base64_decode($_request->status);
                if (base64_decode($_request->status) == 1) {
                    $_transaction->is_approved = true;
                    $_transaction->staff_id = $_staff->id;
                    $_transaction->save();
                    $_transaction->payment->is_removed = true;
                    $_transaction->payment->save();
                } else {
                    # code...
                }
                return back()->with('success', 'Void Approved');
            } else {
                return back()->with('success', 'Invalid User Account');
            }
            return $_staff;
        } catch (Exception $err) {
            return back()->with('error', $err->getMessage());
            // TODO:: Audit Error
        }
    }
    public function report_student_monthly_payment(Request $_request)
    {
        try {
            $_course = CourseOffer::find($_request->balance_course); // Course
            $_academic = base64_decode($_request->_academic); // Academic
            $_academic = AcademicYear::find($_academic);
            $_date = now();
            $_section = Section::where('course_id', $_course->id)->where('academic_id', $_academic->id)->where('is_removed', false)->get();
            /*  $_report = new PaymentReports;
            return $_report->monthly_payment_report($_section); */
            $_file_name = strtoupper($_course->course_code) . '-MONTHLY-PAYMENT-MONITORING-' . strtoupper($_academic->school_year . "-" . $_academic->semester) . '-' . $_date . '.xlsx'; // Name of the File
            $_excel = new MonthlyPaymentMonitoring($_section); // Excel Function
            $_file = Excel::download($_excel, $_file_name); // Download the File
            ob_end_clean();
            return $_file;
        } catch (Expression $er) {
            return back()->with('error', $er);
        }
    }
    public function employee_attendace_report(Request $_request)
    {
        $_request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        try {
            $_report = new AttendanceSheetReport();
            $_report_pdf = $_request->r_view == 'daily' ? $_report->daily_report() : $_report->daily_time_record_report($_request->start_date, $_request->end_date);
            return $_report_pdf;
        } catch (\Throwable $th) {
            //throw $th;
            return $th->getMessage();
        }
    }
    public function employee_attendace_report_v2(Request $request)
    {
        $request->validate([
            'start_date' => 'required',
            'end_date' => 'required'
        ]);
        try {
            $_report = new AttendanceSheetReport();
            $_report_pdf = $request->format == 'daily' ? $_report->daily_time_record_report_v2($request->department, $request->start_date, $request->end_date) :  $_report->monthly_time_record_report_v2($request->department, $request->start_date, $request->end_date);
            return $_report_pdf;
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function examination_permit(Request $_request)
    {
        try {
            $course = CourseOffer::find($_request->course);
            $academic = AcademicYear::find(base64_decode($_request->academic));
            $sections = $course->sections_academic($academic)->get();
            $view = "widgets.report.accounting.examination-permit";
            $academic =  strtoupper($academic->semester) . ' SY ' . $academic->school_year;
            $term = $_request->term;
            $file_name = 'TEST-PERMIT-' . strtoupper($term) . 'EXAMINATION-' . strtoupper($course->course_name);
            $pdf = PDF::loadView($view, compact('sections', 'term', 'course', 'academic'));
            return $pdf->setPaper([0, 0, 612.00, 792.00], 'portrait')->download($file_name . '.pdf');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function student_account_card_per_section(Request $_request)
    {
        try {
            $course = CourseOffer::find($_request->course);
            $academic = AcademicYear::find(base64_decode($_request->academic));
            $level = str_replace('/', '', $_request->level);
            $filename = $course->course_code . '-' . $level . '-' . $academic->school_year . '-' . $academic->semester;

            $studentList = StudentDetails::select('student_details.id', 'student_details.last_name', 'student_details.first_name', 'student_details.middle_name', 'student_details.extention_name')
                ->join('student_sections', 'student_sections.student_id', 'student_details.id')
                ->where('student_sections.is_removed', false)
                ->join('sections', 'student_sections.section_id', 'sections.id')
                ->where('sections.is_removed', false)
                ->where('sections.course_id', $course->id)
                ->where('sections.academic_id', $academic->id)
                ->where('sections.year_level', $_request->level)
                ->orderBy('sections.section_name', 'asc')
                ->orderBy('student_details.last_name', 'asc')->orderBy('student_details.first_name', 'asc')
                ->get();
            //return $studentList;
            $pdfReport = new PaymentReports();
            return $pdfReport->student_account_card_by_section($studentList, $filename, $academic);
        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }
    public function employee_view(Request $_request)
    {
        try {
            $_employees = Staff::where('is_removed', false)->orderBy('staff.department', 'asc')
                ->orderBy('staff.last_name', 'asc')->get();
            return view('pages.accounting.employee.view', compact('_employees'));
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    function particular_additional_fee(Request $request)
    {
        try {
            $request->validate(
                ['particular' => 'required', 'fee_amount' => 'required']
            );
            AdditionalFees::create([
                'particular_id' => $request->particular,
                'amount' => $request->fee_amount
            ]);
            return back()->with('success', 'Additional Fee Successfully Added.');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
