<?php

namespace App\Http\Livewire\Executive\Attendance;

use App\Models\EmployeeAttendance;
use App\Models\StudentAccount;
use App\Models\StudentOnboardingAttendance;
use App\Models\User;
use DateTime;
use Illuminate\Database\Query\Expression;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ScannerView extends Component
{
    public $scanData;
    public $testing;
    public $dataProfile;
    public $activeTab;
    public $week_dates;
    function mount()
    {
        $first_day =  new DateTime();
        $last_day = new DateTime();
        $first_day->modify('last Sunday');
        $last_day->modify('Next Saturday');
        $this->week_dates = array(
            $first_day->format('Y-m-d') . '%',  $last_day->format('Y-m-d') . '%'
        );
    }
    public function render()
    {
        $data = [];
        $profile = [];
        if ($this->scanData != '') {
            $word = 'employee';
            if (strpos($this->scanData, $word) !== false) {
                $data =  explode(":", $this->scanData);
                //return count($data);
                if (count($data) > 1) {
                    $this->dataProfile = $data[1];
                } else {
                    $this->dataProfile = str_replace('employee', '', $this->scanData);
                }
                $this->testing =  'employee';
            } else {
                $data =  explode(".", $this->scanData);
                if (count($data) > 1) {
                    $this->dataProfile = $data[0];
                }
                $this->testing = 'student';
            }
            $this->scanData = '';
            $profile = $this->scanQrcode($this->testing, $this->dataProfile);
        }
        $employees = $this->attendance_table('employee');
        $students = $this->attendance_table('student');
        return view('livewire.executive.attendance.scanner-view', compact('employees', 'students', 'profile'));
    }
    function scanQrcode($userType, $username)
    {
        $this->swtchTab($userType);
        switch ($userType) {
            case 'student':
                return $this->student_process($username);
                break;
            case 'employee':
                return $this->employee_process($username);
                break;
        }
    }
    function student_process($username)
    {
        try {
            // Get the Student Account
            $account = StudentAccount::where('student_number', $username)->first();
            if ($account) {
                /* Set the Return Value */
                $profile = array(
                    'type' => 'student',
                    'image' =>  $account->student->profile_picture(),
                    'name' => strtoupper(trim($account->student->last_name . ', ' . $account->student->first_name)),
                    'course' => $account->student->enrollment_assessment->course->course_name,
                    'section' => $account->student->current_section->section->section_name,
                );
                /* Student Attendance Details*/
                $content = array(
                    'student_id' => $account->student->id,
                    'course_id' => $account->student->enrollment_assessment->course_id,
                    'academic_id' => $account->student->enrollment_assessment->academic_id,
                    'time_in' => now(),
                    'time_in_status', 'time_in_remarks',
                    'time_in_process_by' => Auth::user()->name,
                );
                /* Check the Studen Attendance per Week */
                $attendance = StudentOnboardingAttendance::where('student_id', $account->student_id)
                    ->where('course_id', $account->student->enrollment_assessment->course_id)
                    ->where('academic_id', $account->student->enrollment_assessment->academic_id)
                    ->whereBetween('created_at', $this->week_dates)->first();
                // Create an time in
                // if the Attendance is true
                // Add the Time Out DateTime on Attendance of the Employee
                if ($attendance) {
                    if ($attendance->time_out !== null) {
                        StudentOnboardingAttendance::create($content);
                        $profile['time_status'] = 'TIME IN';
                        $profile['message'] = 'Welcome ' . $account->student->first_name . ' have a Great day..';
                        $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $account->student->first_name))))) . '-good-morning.mp3';
                    } else {
                        $attendance->time_out = now();
                        $attendance->time_out_process_by = Auth::user()->name;
                        $attendance->save();
                        $profile['time_status'] = 'TIME OUT';
                        $profile['message'] = 'Good bye ' . $account->student->first_name . ' Ingat..';
                        $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $account->student->first_name))))) . '-good-bye.mp3';
                    }
                } // Additonal Attendance for Week
                else {
                    StudentOnboardingAttendance::create($content);
                    $profile['time_status'] = 'TIME IN';
                    $profile['message'] = 'Welcome ' . $account->student->first_name . ' have a Great day..';
                    $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $account->student->first_name))))) . '-good-bye.mp3';
                }
                $this->alert($profile['time_status'], $profile['message'], 'success', $profile['sound']);
                return $profile;
            } else {
                // Invalid Student
                $this->alert('Invalid User', 'No Data', 'warning', 'error');
            }
        } catch (\Throwable $th) {
            $this->alert('Scanner Error','Scan Again', 'warning', 'error');
        }
    }
    function employee_process($username)
    {
        try {
            // Get the Employee Details
            $account =  User::where('email', $username)->first();
            /* Set the Return Value */
            $profile = array(
                'type' => 'employee',
                'image' =>  $account->staff->profile_picture(),
                'name' => strtoupper(trim($account->name)),
                'department' => $account->staff->department,
            );
            /* Check the Attendance Daily */
            $attendance = EmployeeAttendance::where('staff_id', $account->staff->id)
                ->where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->orderBy('id', 'desc')->first();
            /* Employee Attendance Details */
            $content = array(
                'staff_id' => $account->staff->id,
                'description' => 'n/a',
                'time_in' => date('Y-m-d H:i:s'),
            ); // Create an time in
            // if the Attendance is true
            // Add the Time Out DateTime on Attendance of the Employee
            if ($attendance) {
                // Check the Existing attendance have a time out
                if ($attendance->time_out) {
                    EmployeeAttendance::create($content);
                    $profile['time_status'] = 'TIME IN';
                    $profile['message'] = 'Welcome ' . $account->staff->first_name . ' have a Great day..';
                    $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $account->staff->first_name))))) . '-good-morning.mp3';
                }
                // Update Time Out 
                else {
                    $attendance->update(['time_out' => now()]);
                    $profile['time_status'] = 'TIME OUT';
                    $profile['message'] = 'Good bye ' . $account->staff->first_name . ' Ingat..';
                    $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $account->staff->first_name))))) . '-good-bye.mp3';
                }
            }
            // Add Attendance for Today
            else {
                EmployeeAttendance::create($content);
                $profile['time_status'] = 'TIME IN';
                $profile['message'] = 'Welcome ' . $account->staff->first_name . ' have a Great day..';
                $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '-', trim(str_replace('/', '-', $account->staff->first_name))))) . '-good-morning.mp3';
            }
            $this->alert($profile['time_status'], $profile['message'], 'success', $profile['sound']);
            $profile['attendance'] = $account->staff->daily_attendance;
            return $profile;
        } catch (\Throwable $th) {
            $this->alert('Scanner Error', 'Scan Again', 'warning', 'error');
        }
    }
    function attendance_table($user)
    {
        $data = [];
        switch ($user) {
            case 'employee':
                $data = EmployeeAttendance::where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')->orderBy('updated_at', 'desc')->paginate(20);
                break;
            case 'student':
                $data = StudentOnboardingAttendance::whereBetween('created_at', $this->week_dates)
                    ->with('student:id,last_name,first_name,middle_name', /* 'student.enrollment_assessment', */ 'student.current_section.section')
                    ->orderBy('updated_at', 'desc')->paginate(20);
                break;
        }
        return $data;
    }
    function alert($title, $message, $icon, $sound)
    {
        $this->dispatchBrowserEvent('qrcode:alert', [
            'title' => $title,
            'text' => $message,
            'type' => $icon,
            'audio' => $sound
        ]);
    }
    function swtchTab($data)
    {
        $this->activeTab = $data;
    }
}
