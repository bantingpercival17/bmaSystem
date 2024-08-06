<?php

namespace App\Http\Livewire\Accounting;

use App\Models\AcademicYear;
use App\Models\AdditionalFees;
use App\Models\EnrollmentAssessment;
use App\Models\Particulars;
use App\Models\PaymentAdditionalFees;
use App\Models\PaymentTransaction as ModelsPaymentTransaction;
use App\Models\PaymentTrasanctionOnline;
use App\Models\Section;
use App\Models\Staff;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use App\Models\StudentSection;
use App\Models\VoidTransaction;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class PaymentTransaction extends Component
{
    public $inputStudent;
    public $staff = null;
    public $academic;
    public $academicData;
    public $academicHistory;
    public $profile = null;
    public $paymentMode = 0;
    public $particularLists = [];
    public $totalSemestralFees = 0;
    public $enrollmentAssessment = null;
    public $paymentAssessment = null;
    public $activeCard = 'overview';
    public $particularName = 'Tuition Fees';
    public $transactionStatus = 'tuition-fee';
    public $transactionRemarks = 'Upon Enrollment';
    public $transactionPaymentMethod = 'CASH';
    public $transactionOrNumber = null;
    public $transactionDate = null;
    public $transactionAmount = null;
    public $transactionVoucher = 0;
    public $onlinePaymentTransaction = null;
    public $paymentDetails = [];
    public $particularId = null;
    public $penaltyAmount = null;
    protected $rules = [
        'transactionOrNumber' => 'required',
        'transactionAmount' => 'required',
    ];
    protected $listeners = ['voidTransaction', 'paymentDisapproved'];
    public function render()
    {
        $this->staff = auth()->user()->staff->id;
        $this->academic =  $this->academicValue();
        $studentLists = $this->inputStudent != '' ? $this->findStudent($this->inputStudent) : $this->recentStudent(base64_decode($this->academic));
        $particularFees = AdditionalFees::select('additional_fees.*')
            ->join('particulars', 'particulars.id', 'additional_fees.particular_id')
            ->where('additional_fees.is_removed', false)
            ->where('particulars.particular_name', '!=', 'Surcharge')
            ->get();
        $scholarshipList = [];
        $additional_fees = [];
        $this->profile = request()->query('student') ? StudentDetails::find(base64_decode(request()->query('student'))) : $this->profile;
        if ($this->profile) {
            $this->academicHistory = $this->profile->enrollment_history;
            $this->academicData = AcademicYear::find(base64_decode($this->academic));
            if ($this->profile->enrollment_selection($this->academicData->id)) {
                $this->paymentAssessment = $this->profile->enrollment_selection($this->academicData->id)->payment_assessments;
                $this->enrollmentAssessment =  $this->profile->enrollment_selection($this->academicData->id);
                $additional_fees = $this->paymentAssessment->additional_fees;
            } else {
                $this->paymentAssessment = $this->profile->enrollment_selection($this->profile->enrollment_assessment->academic_id)->payment_assessments;
                $this->enrollmentAssessment = $this->profile->enrollment_selection($this->profile->enrollment_assessment->academic_id);
                $additional_fees = $this->paymentAssessment->additional_fees;
            }
            $scholarshipList =  Voucher::where('is_removed', false)->get();
        }
        return view('livewire.accounting.payment-transaction', compact('studentLists',  'particularFees', 'scholarshipList', 'additional_fees'));
    }
    function academicValue()
    {
        $data = $this->academic;
        if ($this->academic == '') {
            $_academic = AcademicYear::where('is_active', 1)->first();
            $data = base64_encode($_academic->id);
        }
        if (request()->query('_academic')) {
            $data = request()->query('_academic') ?: $this->academic;
        }
        Cache::put('academic', $data, 60);
        return $data;
    }
    function recentStudent($academic)
    {
        return StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
            ->join('enrollment_assessments', 'student_details.id', 'enrollment_assessments.student_id')
            ->join('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->where('enrollment_assessments.academic_id', $academic)
            ->where('pa.is_removed', false)
            ->join('payment_trasanction_onlines', 'payment_trasanction_onlines.assessment_id', 'pa.id')
            ->where('payment_trasanction_onlines.is_removed', false)
            ->whereNull('payment_trasanction_onlines.is_approved')
            ->groupBy('student_details.id')->paginate(10);
    }
    function findStudent($data)
    {
        $query = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name', 'student_details.middle_initial')
            ->where('student_details.is_removed', false);
        $_student = explode(',', $data); // Seperate the Sentence
        $_count = count($_student);
        if (is_numeric($data)) {
            $query = $query->join('student_accounts', 'student_accounts.student_id', 'student_details.id')
                ->where('student_accounts.student_number', 'like', '%' . $data . '%')
                ->orderBy('student_details.last_name', 'asc');
        } else {
            if ($_count > 1) {
                $query = $query->where('student_details.last_name', 'like', '%' . $_student[0] . '%')
                    ->where('student_details.first_name', 'like', '%' . trim($_student[1]) . '%')
                    ->orderBy('student_details.last_name', 'asc');
            } else {
                $query = $query->where('student_details.last_name', 'like', '%' . $_student[0] . '%')
                    ->orderBy('student_details.last_name', 'asc');
            }
        }
        return $query->paginate(20);
    }
    function swtchTab($data)
    {
        $this->activeCard = $data;
        $this->particularName =  'Tuition Fees';
        $this->transactionStatus = 'tuition-fee';
        $this->transactionRemarks = 'Upon Enrollment';
    }
    function addFees($data)
    {
        $checkFees = PaymentAdditionalFees::where('assessment_id', $this->paymentAssessment->id)->where('fees_id', $data)->where('is_removed', false)->first();
        if ($checkFees) {
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'Payment Transaction!',
                'text' => "This Fee is Already Added.",
                'type' => 'info',
            ]);
        } else {
            PaymentAdditionalFees::create([
                'enrollment_id' => $this->paymentAssessment->enrollment_assessment->id,
                'assessment_id' => $this->paymentAssessment->id,
                'fees_id' => $data
            ]);
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'Payment Transaction!',
                'text' => 'Added Fees',
                'type' => 'success',
            ]);
        }
        $this->paymentAssessment = $this->profile->enrollment_status->payment_assessments;;
    }
    function addTransaction($term, $status, $id)
    {
        $this->activeCard = 'transaction';
        $this->particularName =  $term;
        $this->transactionStatus = $status;
        $this->transactionRemarks = $term;
        $this->particularId = base64_decode($id);
        $fees = PaymentAdditionalFees::find(base64_decode($id));
        $this->paymentDetails = array(
            'total_payable' => $fees->fee_details->amount,
            'total_paid' => $fees->fee_total_paid(),
            'balance' => $fees->fee_details->amount - $fees->fee_total_paid()
        );
    }
    function paymentTransaction()
    {
        try {
            $convertedAmount = str_replace(",", "", $this->transactionAmount);
            // Payment transaction types
            $tuition_fee_remarks = ['Tuition Fee', 'Upon Enrollment', '1ST MONTHLY', '2ND MONTHLY', '3RD MONTHLY', '4TH MONTHLY'];
            // Determine payment transaction type
            $payment_transaction = in_array($this->transactionRemarks, $tuition_fee_remarks) ? 'TUITION FEE' : 'ADDITIONAL FEE';
            if ($this->transactionVoucher) {
                $voucher = Voucher::find($this->transactionVoucher);
                $orNumber = $voucher->voucher_code . '.' . $this->profile->account->student_number;
                $fullVoucher = ['TCC SCHOLAR', 'MMSL - SCHOLAR'];
                if ($voucher->voucher_code == 'CAPT.SINGSON') {
                    $tuition_fees = $this->paymentAssessment->enrollment_assessment->course_level_tuition_fee();
                    $amount =  $tuition_fees->tuition_fee_discount($this->paymentAssessment->enrollment_assessment);
                } else {
                    $amount = in_array($voucher->voucher_name, $fullVoucher) ? $this->paymentAssessment->total_payment : $voucher->voucher_amount;
                }
                $paymentMethod = 'VOUCHER';
            } else {
                $this->validate();
            }
            // Create a Payment Details
            $staff = Staff::where('user_id', Auth::user()->id)->first();
            $paymentDetails = array(
                'assessment_id' => $this->paymentAssessment->id,
                'or_number' => $this->transactionVoucher ? $orNumber : $this->transactionOrNumber,
                'payment_transaction' => $payment_transaction,
                'payment_amount' => $this->transactionVoucher ? $amount : $convertedAmount,
                'payment_method' => $this->transactionVoucher ? $paymentMethod : $this->transactionPaymentMethod,
                'remarks' => strtoupper($this->transactionRemarks),
                'transaction_date' => $this->transactionDate ?: date('Y-m-d'),
                'staff_id' => $staff->id,
                'is_removed' => false
            );
            // For Additional Penalty Transaction
            $this->penaltyAmountFunction($this->enrollmentAssessment, $this->paymentAssessment);
            if ($this->particularId) {
                $fees = PaymentAdditionalFees::find($this->particularId);
                $fees->status = $convertedAmount;
                $fees->save();
            }
            $paymentTransaction = ModelsPaymentTransaction::create($paymentDetails);
            // TODO: Change into Payment Transaction History
            if (count($this->paymentAssessment->payment_transaction) < 1) {
                // The Auto Section
                $this->setStudentSection($this->paymentAssessment);
                $this->setStudentAccount($this->paymentAssessment->enrollment_assessment);
                if ($this->onlinePaymentTransaction) {
                    $onlinePayment = PaymentTrasanctionOnline::find($this->onlinePaymentTransaction);
                    $onlinePayment->payment_id = $paymentTransaction->id;
                    $onlinePayment->is_approved = 1;
                    $onlinePayment->or_number = $this->transactionOrNumber;
                    $onlinePayment->save();
                }
            }
            $this->reset(['transactionAmount', 'transactionOrNumber', 'transactionVoucher', 'transactionPaymentMethod', 'transactionRemarks', 'transactionDate', 'particularId']);
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'Payment Transaction!',
                'text' => 'Successfully Transact!',
                'type' => 'success',
            ]);
            $this->swtchTab('history');
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'System Bug!',
                'text' => $th->getMessage(),
                'type' => 'warning',
            ]);
        }
    }
    function setStudentSection($assessment)
    {
        // Get the Enrollment Details
        $enrollment = $assessment->enrollment_assessment;
        // Find Section by Curriculum and Year Level
        $section = Section::where('academic_id', $enrollment->academic_id)
            ->where('course_id', $enrollment->course_id)
            ->where('curriculum_id', $enrollment->curriculum_id)
            ->where('year_level', 'like', '%' . $enrollment->year_level . '%')
            ->where(function ($_sub_query) {
                $_sub_query->select(DB::raw('count(*)'))->from('student_sections')
                    ->whereColumn('student_sections.section_id', 'sections.id')
                    ->where('student_sections.is_removed', false);
            }, '<', 40)->first();

        if ($section) {
            $oldValidation = StudentSection::where('section_id', $section->id)
                ->where('student_id', $enrollment->student->id)
                ->where('is_removed', false)->first(); // Verify if the Student will save on Section
            if (!$enrollment->student_section) {
                StudentSection::create([
                    'student_id' => $enrollment->student->id,
                    'section_id' => $section->id,
                    'enrollment_id' => $enrollment->id,
                    'created_by' => 'Auto Section',
                    'is_removed' => 0,
                ]);
            }
        }
    }
    function setStudentAccount($enrollment)
    {
        $account = $enrollment->student->account;
        if (!$account) {
            # Get the Year level of the Student
            $yearLevel = $enrollment->year_level;
            # Get the Total Number of Enrollee per Year Level
            $_enrollment_count = EnrollmentAssessment::where('enrollment_assessments.is_removed', false)
                ->where('enrollment_assessments.year_level', $yearLevel)
                ->where('enrollment_assessments.academic_id', $this->academicData->id)
                ->groupBy('enrollment_assessments.student_id')
                ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
                ->join('payment_transactions', 'payment_transactions.assessment_id', 'payment_assessments.id')
                ->groupBy('enrollment_assessments.student_id')->get();
            $_enrollment_count = count($_enrollment_count);
            // Set the student number
            $student_count = $_enrollment_count >= 10 ? ($_enrollment_count >= 100 ? $_enrollment_count : '0' . $_enrollment_count) : '00' . $_enrollment_count;
            $pattern = $yearLevel == 11 ? '07-' . date('y') : date('y'); // Set the Year and Batch
            $student_number = $pattern . $student_count; // Final Student Number
            $email = $student_number . '.' . str_replace(' ', '', strtolower($enrollment->student->last_name)) . '@bma.edu.ph'; // Set Email
            // Set the value for Student Account
            $_account_details = array(
                'student_id' => $enrollment->student_id,
                'email' => $email,
                'personal_email' => $email,
                'student_number' => $student_number,
                'password' => Hash::make($student_number),
                'is_actived' => true,
                'is_removed' => false,
            );
            StudentAccount::create($_account_details);
            /*  if ($yearLevel == 11 && $yearLevel == 4) {
                StudentAccount::create($_account_details);
            } */
        } else {
            # Get the Year level of the Student
            $yearLevel = $enrollment->year_level;
            # Get the Total Number of Enrollee per Year Level
            $_enrollment_count = EnrollmentAssessment::where('enrollment_assessments.is_removed', false)
                ->where('enrollment_assessments.year_level', $yearLevel)
                ->where('enrollment_assessments.academic_id', $this->academicData->id)
                ->groupBy('enrollment_assessments.student_id')
                ->join('payment_assessments', 'payment_assessments.enrollment_id', 'enrollment_assessments.id')
                ->join('payment_transactions', 'payment_transactions.assessment_id', 'payment_assessments.id')
                ->groupBy('enrollment_assessments.student_id')->get();
            $_enrollment_count = count($_enrollment_count);
            // Set the student number
            $student_count = $_enrollment_count >= 10 ? ($_enrollment_count >= 100 ? $_enrollment_count : '0' . $_enrollment_count) : '00' . $_enrollment_count;
            $pattern = $yearLevel == 11 ? '07-' . date('y') : date('y'); // Set the Year and Batch
            $student_number = $pattern . $student_count; // Final Student Number
            $email = $student_number . '.' . str_replace(' ', '', strtolower($enrollment->student->last_name)) . '@bma.edu.ph'; // Set Email
            // Set the value for Student Account
            $_account_details = array(
                'student_id' => $enrollment->student_id,
                'email' => $email,
                'personal_email' => $email,
                'student_number' => $student_number,
                'password' => Hash::make($student_number),
                'is_actived' => true,
                'is_removed' => false,
            );
            if ($yearLevel == 11 && $yearLevel == 4) {
                StudentAccount::create($_account_details);
            }
        }
    }
    function voidTransaction($payment, $remarks)
    {
        try {
            $_void_details = array(
                'payment_id' => $payment,
                'void_reason' => base64_encode($remarks),
            );
            VoidTransaction::create($_void_details);
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'Payment Void Complete!',
                'text' => 'Payment was Void.',
                'type' => 'success',
            ]);
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'System Bug!',
                'text' => $th->getMessage(),
                'type' => 'warning',
            ]);
        }
    }
    function removeFee($data)
    {
        $fee = PaymentAdditionalFees::find($data);
        $fee->is_removed = true;
        $fee->save();
        $this->dispatchBrowserEvent('swal:alert', [
            'title' => 'Payment Transaction!',
            'text' => 'Remove Addtion Fees',
            'type' => 'success',
        ]);
    }
    function dialogBox($payment)
    {
        $this->dispatchBrowserEvent('swal:confirmInputVoid', [
            'title' => "VOID TRANSACTION",
            'text' => "Void Reason",
            'type' => 'warning',
            'confirmButtonText' => 'Submit',
            'cancelButtonText' => 'Cancel',
            'method' => 'voidTransaction',
            'input' => 'text',
            'inputPlaceholder' => 'Enter a Reason',
            'params' => ['payment' => $payment],
        ]);
    }
    function disapprovedDialogBox($payment)
    {
        $this->dispatchBrowserEvent('swal:confirmInputVoid', [
            'title' => "ONLINE PAYMENT DISAPPROVED",
            'text' => "Reason for disapproved payment proof",
            'type' => 'warning',
            'confirmButtonText' => 'Submit',
            'cancelButtonText' => 'Cancel',
            'method' => 'paymentDisapproved',
            'input' => 'text',
            'inputPlaceholder' => 'Enter a Reason',
            'params' => ['payment' => $payment],
        ]);
    }
    function paymentDisapproved($payment, $remark)
    {
        $onlinePayment = PaymentTrasanctionOnline::find(base64_decode($payment));
        $onlinePayment->is_approved = false;
        $onlinePayment->comment_remarks = $remark;
        $onlinePayment->save();
        $this->dispatchBrowserEvent('swal:alert', [
            'title' => 'Payment Transaction!',
            'text' => 'Proof of Payment Disapproved.' . base64_decode($payment),
            'type' => 'success',
        ]);
    }
    function approvedPayment($payment)
    {
        $this->activeCard = 'transaction';
        $payment = PaymentTrasanctionOnline::find(base64_decode($payment));
        $this->transactionAmount = $payment->amount_paid;
        $this->transactionRemarks =  str_replace('_', ' ', $payment->transaction_type);
        $this->onlinePaymentTransaction = $payment->id;
    }
    function penaltyAmountFunction($enrollment, $assessment)
    {
        if ($this->penaltyAmount != null) {
            // Set the Surchange
            $particular_fee = Particulars::where('particular_name', 'Surcharge')->first();
            if (!$particular_fee) {
                $particular_fee = Particulars::create(['particular_name' => 'Surcharge', 'particular_tag' => 'additional_type', 'particular_type' => 'other_tags', 'department' => 'college']);
            }
            $additional_fees =   AdditionalFees::create(['particular_id' => $particular_fee->id, 'amount' => $this->penaltyAmount, 'status' => $this->penaltyAmount]);
            if ($additional_fees) {
                $paymentAdditionalFee = PaymentAdditionalFees::create(['fees_id' => $additional_fees->id, 'enrollment_id' => $enrollment->id, 'assessment_id' => $assessment->id]);
                $staff = Staff::where('user_id', Auth::user()->id)->first();
                $paymentDetails = array(
                    'assessment_id' => $this->paymentAssessment->id,
                    'or_number' => $this->transactionOrNumber,
                    'payment_transaction' => 'ADDITIONAL FEE',
                    'payment_amount' => $this->penaltyAmount,
                    'payment_method' =>  $this->transactionPaymentMethod,
                    'remarks' => strtoupper($this->transactionRemarks . ' Surcharge'),
                    'transaction_date' => $this->transactionDate ?: date('Y-m-d'),
                    'staff_id' => $staff->id,
                    'is_removed' => false
                );
                ModelsPaymentTransaction::create($paymentDetails);
            }
        }
    }
}
