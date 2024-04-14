<?php

namespace App\Http\Livewire\OnboardTraining;

use App\Models\StudentDetails;
use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class MonthlyOnboardTraining extends Component
{
    public $profile = [];
    public $inputStudent;
    public $activeCard = 'performance-monitoring-report';
    public $subCardContent = 'card-content-mr-0';
    public $showModal = false;
    public function render()
    {
        $component_path = 'livewire.registrar.student.profile-components.';
        $subHeaders = array(
            array('profile', $component_path . 'information'),
            array('performance-monitoring-report', $component_path . 'performance-monitoring-report'),
        );
        $studentLists = $this->inputStudent != '' ? $this->findStudent($this->inputStudent) : $this->onboard_monitoring_list()->paginate(10);
        $this->profile = request()->query('student') ? StudentDetails::find(base64_decode(request()->query('student'))) : $this->profile;
        return view('livewire.onboard-training.monthly-onboard-training', compact('studentLists', 'subHeaders'));
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

        return $query->paginate(10);
    }
    function onboard_monitoring_list()
    {
        return StudentDetails::select('student_details.*')
            ->join('shipboard_journals', 'shipboard_journals.student_id', 'student_details.id')
            ->where('shipboard_journals.is_approved', null)
            ->groupBy('shipboard_journals.student_id')
            ->where('shipboard_journals.is_removed', false);
    }
    function swtchTab($data)
    {
        $this->activeCard = $data;
        Cache::put('menu', $data, 60);
    }
    function subCardSwtich($data)
    {
        $this->subCardContent = $data;
        Cache::put('menu-sub', $data, 60);
    }

}
