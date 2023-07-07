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
    public function render()
    {
        $_academic = Auth::user()->staff->current_academic();
        $this->academic =  request()->query('_academic') ?: $this->academic;
        $academic = base64_decode($this->academic) ?: $_academic->id;
        $studentLists = $this->inputStudent != '' ? $this->findStudent($this->inputStudent) : $this->recentStudent($academic);
        $this->profile = request()->query('student') ? StudentDetails::find(base64_decode(request()->query('student'))) : $this->profile;
        return view('livewire.accounting.assessment-fees', compact('studentLists'));
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
