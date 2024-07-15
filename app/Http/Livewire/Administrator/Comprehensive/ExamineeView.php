<?php

namespace App\Http\Livewire\Administrator\Comprehensive;

use App\Models\ComprehensiveExamination;
use App\Models\StudentDetails;
use Livewire\Component;

class ExamineeView extends Component
{
    public $profile = [];
    public $inputStudent = '';
    public $activeCard = 'profile';
    public function render()
    {
        $studentLists = $this->inputStudent != '' ? $this->findStudent($this->inputStudent) : [];
        $this->profile = request()->query('student') ? StudentDetails::find(base64_decode(request()->query('student'))) : $this->profile;
        if ($this->profile) {
            $course = $this->profile->enrollment_assessment;
            $competence = ComprehensiveExamination::select('id', 'competence_code', 'competence_name','function', 'file_name')
            ->with(['examination_details' => function ($query) {
            }])
            ->where('course_id', $course->course_id)->get();
        }
        return view('livewire.administrator.comprehensive.examinee-view', compact('studentLists', 'competence'));
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
