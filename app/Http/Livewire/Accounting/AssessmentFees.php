<?php

namespace App\Http\Livewire\Accounting;

use App\Models\StudentDetails;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class AssessmentFees extends Component
{
    public $inputStudent;
    public $academic;
    public $profile = null;
    public $paymentMode;
    public function render()
    {
        $_academic = Auth::user()->staff->current_academic();
        $this->academic =  request()->query('_academic') ?: $this->academic;
        $academic = base64_decode($this->academic) ?: $_academic->id;
        $studentLists = $this->inputStudent != '' ? $this->findStudent($this->inputStudent) : $this->recentStudent($academic);
        $this->profile = request()->query('student') ? StudentDetails::find(base64_decode(request()->query('student'))) : $this->profile;
        $tuition_fees = [];
        if ($this->profile) {
            $enrollment_assessment = $this->profile->enrollment_assessment;
            if ($enrollment_assessment) {
                $tuition_fees = $enrollment_assessment->course_level_tuition_fee();
                if ($tuition_fees) {
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
                    if ($this->paymentMode == 1) {
                        $tuition_fees = array(
                            'fee_amount' => $total_tuition,
                            'upon_enrollment' => $upon_enrollment,
                            'monthly' => $monthly,
                            'total_fees' => $total_tuition_with_interest
                        );
                    }
                }
            }
        }
        return view('livewire.accounting.assessment-fees', compact('studentLists', 'tuition_fees'));
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
}
