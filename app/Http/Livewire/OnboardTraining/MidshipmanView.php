<?php

namespace App\Http\Livewire\OnboardTraining;

use Livewire\Component;
use App\Models\StudentDetails;
use Illuminate\Support\Facades\Cache;

class MidshipmanView extends Component
{
    public $profile = [];
    public $inputStudent;

    public $activeCard = 'profile';
    public function render()
    {
        $component_path = 'livewire.registrar.student.profile-components.';
        $subHeaders = array(
            array('profile', $component_path . 'information'),
            array('shipboard-application', $component_path . 'shipboard-application')
        );
        $studentLists = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name', 'student_details.extention_name')
            ->join('ship_board_information', 'ship_board_information.student_id', 'student_details.id')
            ->whereNull('ship_board_information.is_approved')
            ->orderBy('ship_board_information.updated_at', 'desc')->get();
        $studentLists = $this->inputStudent != '' ? $this->findStudent($this->inputStudent) : $studentLists;
        $this->profile = request()->query('student') ? StudentDetails::find(base64_decode(request()->query('student'))) : $this->profile;
        return view('livewire.onboard-training.midshipman-view', compact('studentLists', 'subHeaders'));
    }
    function swtchTab($data)
    {
        $this->activeCard = $data;
        Cache::put('menu', $data, 60);
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
