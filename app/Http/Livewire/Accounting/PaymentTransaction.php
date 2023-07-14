<?php

namespace App\Http\Livewire\Accounting;

use App\Models\AcademicYear;
use App\Models\AdditionalFees;
use App\Models\StudentDetails;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PaymentTransaction extends Component
{
    public $inputStudent;
    public $academic;
    public $academicData;
    public $academicHistory;
    public $profile = null;
    public $paymentMode = 0;
    public $particularLists = [];
    public $totalSemestralFees = 0;
    public $enrollmentAssessment = null;
    public function render()
    {
        $_academic = Auth::user()->staff->current_academic();
        $this->academic =  request()->query('_academic') ?: $this->academic;
        $academic = base64_decode($this->academic) ?: $_academic->id;
        
        $studentLists = $this->inputStudent != '' ? $this->findStudent($this->inputStudent) : $this->recentStudent($academic);
        $particularFees = AdditionalFees::where('is_removed', false)->get();

        $this->profile = request()->query('student') ? StudentDetails::find(base64_decode(request()->query('student'))) : $this->profile;
        if ($this->profile) {
            $this->academicData = AcademicYear::find($academic);
            $this->academicHistory = $this->profile->enrollment_history;
            
        }
        return view('livewire.accounting.payment-transaction', compact('studentLists',  'particularFees'));
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
}
