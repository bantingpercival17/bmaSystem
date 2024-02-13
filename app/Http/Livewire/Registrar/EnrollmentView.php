<?php

namespace App\Http\Livewire\Registrar;

use App\Models\AcademicYear;
use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\EnrollmentApplication;
use App\Models\StudentDetails;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
//use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;

class EnrollmentView extends Component
{
    //use WithPagination;

    public $searchInput;
    public $academic;
    public $yearLevelList = [11, 12, 4, 3, 2, 1];
    protected $listeners = ['approvedEnrollment', 'disapprovedEnrollment'];
    public function render()
    {
        $courseLists = CourseOffer::all();
        $_courses = CourseOffer::all();
        $_curriculums = Curriculum::where('is_removed', false)->get();
        $scholarship = Voucher::where('is_removed', false)->get();
        $this->academic =  $this->academicValue();
        $studentsList = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name')
            ->leftJoin('enrollment_applications as ea', 'ea.student_id', 'student_details.id')
            ->where('ea.academic_id', base64_decode($this->academic))
            ->whereNull('ea.is_approved')
            ->where('ea.is_removed', false)->orderBy('ea.created_at', 'desc')->paginate(10);
        if ($this->searchInput != '') {
            $query = StudentDetails::select('student_details.id', 'student_details.first_name', 'student_details.last_name', 'student_details.middle_initial')
                ->where('student_details.is_removed', false);
            $_student = explode(',', $this->searchInput); // Seperate the Sentence
            $_count = count($_student);
            if (is_numeric($this->searchInput)) {
                $query = $query->join('student_accounts', 'student_accounts.student_id', 'student_details.id')
                    ->where('student_accounts.student_number', 'like', '%' . $this->searchInput . '%')
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

            $studentsList = $query->paginate(10);
        }
        return view('livewire.registrar.enrollment-view', compact('_courses', 'courseLists', '_curriculums', 'studentsList', 'scholarship'));
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
    /* Disapproved Application */
    function confirmBox($data, $status)
    {
        switch (base64_decode($status)) {
            case 'disapproved':
                $value = array('text' => 'Do you want to disapproved this Enrollment Application?', 'method' => 'disapprovedEnrollment');
                break;
            case 'approved':
                $value = array('text' => 'Do you want to approved this Enrollment Application?', 'method' => 'approvedEnrollment');
                break;
        }
        $this->dispatchBrowserEvent('swal:confirm', [
            'title' => 'Enrollment Assessment',
            'text' => $value['text'],
            'type' => 'info',
            'confirmButtonText' => 'Yes',
            'cancelButtonText' => 'Cancel',
            'method' => $value['method'],
            'params' => ['data' => $data],
        ]);
    }
    function approvedEnrollment($data)
    {
        $this->dispatchBrowserEvent('submit:form', [
            'form' => $data
        ]);
        /*  $this->dispatchBrowserEvent('swal:alert', [
            'title' => 'Complete!',
            'text' => 'Successfully Transact',
            'type' => 'success',
        ]); */
    }
    function disapprovedEnrollment($data)
    {
        $_academic = Auth::user()->staff->current_academic();
        $this->academic =  request()->query('_academic') ?: $this->academic;
        $academic = base64_decode($this->academic) ?: $_academic->id;
        $application = EnrollmentApplication::where('student_id', base64_decode($data))
            ->where('academic_id', $academic)
            ->where('is_removed', false)->first();
        if ($application) {
            $application->is_approved = false;
            $application->save();
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'Complete!',
                'text' => 'Successfully Transact',
                'type' => 'success',
            ]);
        } else {
            $this->dispatchBrowserEvent('swal:alert', [
                'title' => 'Complete!',
                'text' => 'Invalid Data',
                'type' => 'warning',
            ]);
        }
    }
}
