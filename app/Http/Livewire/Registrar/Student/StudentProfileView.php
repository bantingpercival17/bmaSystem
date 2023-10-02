<?php

namespace App\Http\Livewire\Registrar\Student;

use App\Models\StudentDetails;
use Livewire\Component;

class StudentProfileView extends Component
{
    public $profile = [];
    public $inputStudent;

    public $activeCard = 'profile';
    public function render()
    {
        $studentLists = $this->inputStudent != '' ? $this->findStudent($this->inputStudent) : [];
        $this->profile = request()->query('student') ? StudentDetails::find(base64_decode(request()->query('student'))) : $this->profile;
        return view('livewire.registrar.student.student-profile-view', compact('studentLists'));
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
