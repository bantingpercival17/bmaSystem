<?php

namespace App\Http\Livewire\Accounting;

use App\Models\AcademicYear;
use App\Models\AdditionalFees;
use App\Models\EnrollmentAssessment;
use App\Models\PaymentAdditionalFees;
use App\Models\PaymentAssessment;
use App\Models\PaymentForwardedAmount;
use App\Models\PaymentTransaction;
use App\Models\StudentDetails;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class AssessmentFees extends Component
{
    public $inputStudent;
    public $staff = null;
    public $academic;
    public $profile = null;
    public $paymentMode = 0;
    public $tempPaymentMode = null;
    public $particularLists = [];
    public $totalSemestralFees = 0;
    public $enrollmentAssessment = null;
    public function render()
    {
        $this->staff = Auth::user()->staff->id;
        $this->academic =  $this->academicValue();
        $studentLists = $this->inputStudent != '' ? $this->findStudent($this->inputStudent) : $this->recentStudent(base64_decode($this->academic));
        $this->profile = request()->query('student') ? StudentDetails::find(base64_decode(request()->query('student'))) : $this->profile;
        $tuition_fees = [];
        if ($this->profile) {
            /* if (request()->input('reassessment')) {
                # code...
            } */
            $enrollment_assessment = $this->profile->enrollment_status;
            if ($enrollment_assessment) {
                $this->enrollmentAssessment = $enrollment_assessment->id;
                $tuition_fees = $enrollment_assessment->course_level_tuition_fee();
                if ($tuition_fees) {

                    if ($enrollment_assessment->payment_assessments) {
                        $this->paymentMode = $enrollment_assessment->payment_assessments->payment_mode;
                        if (count($enrollment_assessment->payment_assessments->additional_fees)) {
                            foreach ($enrollment_assessment->payment_assessments->additional_fees as $key => $value) {
                                $fee = AdditionalFees::with('particular')->find($value->fees_id);
                                $this->particularLists[] = $fee;
                            }
                        }
                    }
                    if ($this->tempPaymentMode !== null) {
                        $this->paymentMode = $this->tempPaymentMode;
                    }
                    $tags = $tuition_fees->semestral_fees();
                    $total_tuition  = $tuition_fees->total_tuition_fees($enrollment_assessment);
                    $total_tuition_with_interest  = $tuition_fees->total_tuition_fees_with_interest($enrollment_assessment);
                    $upon_enrollment = 0;
                    $upon_enrollment = $tuition_fees->upon_enrollment_v2($enrollment_assessment);
                    $monthly = 0;
                    $monthly = $tuition_fees->monthly_fees_v2($enrollment_assessment);
                    $tuition_fees = array(
                        'fee_amount' => $total_tuition,
                        'upon_enrollment' => $total_tuition,
                        'monthly' => 0.00,
                        'total_fees' => $total_tuition
                    );
                    $this->totalSemestralFees = $total_tuition;
                    if ($this->paymentMode == 1) {
                        $tuition_fees = array(
                            'fee_amount' => $total_tuition,
                            'upon_enrollment' => $upon_enrollment,
                            'monthly' => $monthly,
                            'total_fees' => $total_tuition_with_interest
                        );
                        $this->totalSemestralFees = $total_tuition_with_interest;
                    }
                }
            }
        }
        $particularFees = AdditionalFees::where('is_removed', false)->get();
        return view('livewire.accounting.assessment-fees', compact('studentLists', 'tuition_fees', 'particularFees'));
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
            ->leftJoin('payment_assessments as pa', 'pa.enrollment_id', 'enrollment_assessments.id')
            ->where('enrollment_assessments.academic_id', $academic)
            ->whereNull('pa.enrollment_id')->paginate(20);
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
    function addFees($fee)
    {
        $fee = AdditionalFees::with('particular')->find($fee);
        $this->particularLists[] = $fee;
    }

    function storeAssessment()
    {
        try {
            $enrollment_assessment = EnrollmentAssessment::find($this->enrollmentAssessment);
            $tuition_fees = $enrollment_assessment->course_level_tuition_fee();
            if ($this->paymentMode == 1) {
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
                'enrollment_id' => $this->enrollmentAssessment,
                'course_semestral_fee_id' => $tuition_fees->id,
                'payment_mode' => $this->paymentMode,
                'staff_id' => $this->staff,
                'total_payment' => $total_tuitionfee,
                'upon_enrollment' => $upon_enrollment,
                'monthly_payment' => $monthly_payment,
                'voucher_amount' => 0,
                'is_removed' => 0
            );
            $_payment_assessment = PaymentAssessment::where('enrollment_id', $this->enrollmentAssessment)->first();
            if (!$_payment_assessment) {
                $assessment = PaymentAssessment::create($_details);
                if (count($this->particularLists) > 0) {
                    foreach ($this->particularLists as $key => $list) {
                        $add = PaymentAdditionalFees::where('assessment_id', $assessment->id)->where('fees_id', $list['id'])->first();
                        if (!$add) {
                            PaymentAdditionalFees::create([
                                'enrollment_id' => $this->enrollmentAssessment,
                                'assessment_id' => $assessment->id,
                                'fees_id' => $list['id'],
                                'status' => 'NO PAYMENT'
                            ]);
                        }
                    }
                }
                $this->forwardedPayment($assessment);
                return redirect(route('accounting.payment-transactions-v2') . "?student=" . base64_encode($this->profile->id))->with('success', 'Payment Assessment Complete.');
            } else {
                $this->forwardedPayment($_payment_assessment);
                $_payment_assessment->course_semestral_fee_id =   $tuition_fees->id;
                $_payment_assessment->payment_mode = $this->paymentMode;
                $_payment_assessment->total_payment =  $total_tuitionfee;
                $_payment_assessment->upon_enrollment =  $upon_enrollment;
                $_payment_assessment->monthly_payment =  $monthly_payment;
                $_payment_assessment->staff_id = $this->staff;
                $_payment_assessment->save();
                if (count($_payment_assessment->additional_fees) == 0) {
                    if (count($this->particularLists) > 0) {
                        foreach ($this->particularLists as $key => $list) {
                            PaymentAdditionalFees::create([
                                'enrollment_id' => $this->enrollmentAssessment,
                                'assessment_id' => $_payment_assessment->id,
                                'fees_id' => $list['id'],
                                'status' => 'NO PAYMENT'
                            ]);
                        }
                    }
                }
                return redirect(route('accounting.payment-transactions-v2') . "?student=" . base64_encode($this->profile->id) . ($this->academic ? '&_academic=' . $this->academic : ''))->with('success', 'Payment Assessment Updated');
            }
        } catch (\Throwable $th) {
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'System Bug!',
                'text' => $th->getMessage(),
                'type' => 'warning',
            ]);
        }
    }
    function paymentModeChange($item)
    {
        $this->tempPaymentMode = $item;
    }
    function forwardedPayment($currentAssessment)
    {
        //$profile->enrollment_assessment->over_payment();
        // Get the Previous Enrollment Semester
        $enrollment = EnrollmentAssessment::where('student_id', $this->profile->id)->orderBy('id', 'desc')
            ->where('academic_id', '!=', $this->academic)->first(); //Check if the Enrollment 
        if ($enrollment) {
            $paymentAssessment = $enrollment->payment_assessments;
            if ($paymentAssessment) {
                // Then Check the Payment Fowarded Amount
                $checkForwardedPayment = PaymentForwardedAmount::where('previous_assessment_id', $paymentAssessment->id)->where('is_removed', false)->first();
                $value =  ($paymentAssessment->course_semestral_fee_id ? $paymentAssessment->course_semestral_fee->total_payments($paymentAssessment) : $paymentAssessment->total_payment) - $paymentAssessment->total_paid_amount->sum('payment_amount');
                if (($value * -1) > 100) {
                    if (!$checkForwardedPayment) {
                        $data =  array('previous_assessment_id' => $paymentAssessment->id, 'forwarded_amount' => $value * -1, 'current_assessment_id' => $currentAssessment->id);
                        if (strtoupper($enrollment->academic->semester) == 'FIRST SEMESTER') {
                            $semester = '1ST SEM';
                        } else {
                            $semester = '2ND SEM';
                        }
                        $orName = $semester . ' SY ' . str_replace('20', '', $enrollment->academic->school_year);
                        PaymentForwardedAmount::create($data);
                        $paymentDetails = array(
                            'assessment_id' => $currentAssessment->id,
                            'or_number' => $orName,
                            'payment_transaction' => 'TUITION FEE',
                            'payment_amount' => $value * -1,
                            'payment_method' => 'OVER-PAYMENT',
                            'remarks' => 'FORWARDED PAYMENT',
                            'transaction_date' => date('Y-m-d'),
                            'staff_id' => $this->staff,
                            'is_removed' => false
                        );
                        PaymentTransaction::create($paymentDetails);
                    }
                }
            }
        }
    }
}
