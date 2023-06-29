<?php

namespace App\Http\Livewire\Executive\Attendance;

use App\Models\EmployeeAttendance;
use App\Models\StudentAccount;
use App\Models\StudentOnboardingAttendance;
use App\Models\User;
use Carbon\Carbon;
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

    public function mount()
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
        $employees = $this->attendanceTable('employee');
        $students = $this->attendanceTable('student');
        if ($this->scanData) {
            $word = 'employee';
            if (strpos($this->scanData, $word) !== false) {
                $data = explode(":", $this->scanData);
                $this->dataProfile = count($data) > 1 ? $data[1] : str_replace($word, '', $this->scanData);
                $this->testing = $word;
            } else {
                $data = explode(".", $this->scanData);
                $this->dataProfile = count($data) > 1 ? $data[0] : '';
                $this->testing = 'student';
            }
            $this->scanData = '';
            $profile = $this->scanQrcode($this->testing, $this->dataProfile);
            $employees = $this->attendanceTable('employee');
            $students = $this->attendanceTable('student');
            $this->scanData = '';
        }

        return view('livewire.executive.attendance.scanner-view', compact('employees', 'students', 'profile'));
    }

    public function scanQrcode($userType, $username)
    {
        $this->switchTab($userType);

        switch ($userType) {
            case 'student':
                return $this->studentProcess($username);
            case 'employee':
                return $this->employeeProcess($username);
        }
    }

    public function studentProcess($username)
    {
        try {
            $account = StudentAccount::where('student_number', $username)->first();

            if (!$account) {
                $this->alert('Invalid User', 'No Data', 'warning', 'error');
                return null;
            }

            $profile = [
                'type' => 'student',
                'image' => $account->student->profile_picture(),
                'name' => strtoupper(trim($account->student->last_name . ', ' . $account->student->first_name)),
                'course' => $account->student->enrollment_assessment->course->course_name,
                'section' => $account->student->current_section->section->section_name,
            ];

            /*  $attendance = StudentOnboardingAttendance::where('student_id', $account->student->id)
                ->where('course_id', $account->student->enrollment_assessment->course_id)
                ->where('academic_id', $account->student->enrollment_assessment->academic_id)
                ->whereBetween('created_at', $this->week_dates)->first(); */
            $attendance = StudentOnboardingAttendance::where([
                'student_id' => $account->student->id,
                'course_id' => $account->student->enrollment_assessment->course_id,
                'academic_id' => $account->student->enrollment_assessment->academic_id,
            ])
                ->whereBetween('created_at', $this->week_dates)->orderBy('id', 'desc')
                ->first();
            $content = [
                'student_id' => $account->student->id,
                'course_id' => $account->student->enrollment_assessment->course_id,
                'academic_id' => $account->student->enrollment_assessment->academic_id,
                'time_in' => now(),
                'time_in_status' => null,
                'time_in_remarks' => null,
                'time_in_process_by' => Auth::user()->name,
            ];

            if ($attendance) {
                if ($attendance->time_out !== null) {
                    StudentOnboardingAttendance::create($content);
                    $profile['time_status'] = 'TIME IN';
                    $profile['message'] = 'Welcome ' . $account->student->first_name . ', have a great day.';
                    $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '', str_replace('/', '-', $account->student->first_name)))) . '-good-morning.mp3';
                } else {
                    $attendance->time_out = now();
                    $attendance->time_out_process_by = Auth::user()->name;
                    $attendance->save();
                    $profile['time_status'] = 'TIME OUT';
                    $profile['message'] = 'Goodbye ' . $account->student->first_name . ', Ingat.';
                    $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '', str_replace('/', '-', $account->student->first_name)))) . '-good-bye.mp3';
                }
            } else {
                StudentOnboardingAttendance::create($content);
                $profile['time_status'] = 'TIME IN';
                $profile['message'] = 'Welcome ' . $account->student->first_name . ', have a great day.';
                $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '', str_replace('/', '-', $account->student->first_name)))) . '-good-morning.mp3';
            }
            $profile['attendance'] = $account->student->student_attendance_per_week;
            $this->alert($profile['time_status'], $profile['message'], 'success', $profile['sound']);
            return $profile;
        } catch (\Throwable $th) {
            $this->alert('Scanner Error', $th->getMessage(), 'warning', 'error');
        }
    }

    public function employeeProcess($username)
    {
        try {
            $account = User::where('email', $username)->first();

            if (!$account) {
                $this->alert('Invalid User', 'No Data', 'warning', 'error');
                return null;
            }

            $profile = [
                'type' => 'employee',
                'image' => $account->staff->profile_picture(),
                'name' => strtoupper(trim($account->name)),
                'department' => $account->staff->department,
            ];

            $attendance = EmployeeAttendance::where('staff_id', $account->staff->id)
                ->where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')
                ->orderBy('id', 'desc')
                ->first();

            $content = [
                'staff_id' => $account->staff->id,
                'description' => 'n/a',
                'time_in' => now(),
            ];

            if ($attendance) {
                if ($attendance->time_out) {
                    EmployeeAttendance::create($content);
                    $profile['time_status'] = 'TIME IN';
                    $profile['message'] = 'Welcome ' . $account->staff->first_name . ', have a great day.';
                    $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '', str_replace('/', '-', $account->staff->first_name)))) . '-good-morning.mp3';
                } else {
                    $attendance->update(['time_out' => now()]);
                    $profile['time_status'] = 'TIME OUT';
                    $profile['message'] = 'Goodbye ' . $account->staff->first_name . ', Ingat.';
                    $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '', str_replace('/', '-', $account->staff->first_name)))) . '-good-bye.mp3';
                }
            } else {
                EmployeeAttendance::create($content);
                $profile['time_status'] = 'TIME IN';
                $profile['message'] = 'Welcome ' . $account->staff->first_name . ', have a great day.';
                $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '', str_replace('/', '-', $account->staff->first_name)))) . '-good-morning.mp3';
            }

            $this->alert($profile['time_status'], $profile['message'], 'success', $profile['sound']);
            $profile['attendance'] = $account->staff->daily_attendance;
            return $profile;
        } catch (\Throwable $th) {
            $this->alert('Scanner Error', $th->getMessage(), 'warning', 'error');
        }
    }

    public function attendanceTable($user)
    {
        switch ($user) {
            case 'employee':
                return EmployeeAttendance::where('created_at', 'like', '%' . now()->format('Y-m-d') . '%')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
            case 'student':
                return StudentOnboardingAttendance::whereBetween('created_at', $this->week_dates)
                    ->with('student:id,last_name,first_name,middle_name', 'student.current_section.section')
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
            default:
                return [];
        }
    }

    public function alert($title, $message, $icon, $sound)
    {
        $this->dispatchBrowserEvent('qrcode:alert', [
            'title' => $title,
            'text' => $message,
            'type' => $icon,
            'audio' => $sound
        ]);
    }

    public function switchTab($data)
    {
        $this->activeTab = $data;
    }
    function inputClear()
    {
        $this->scanData = '';
    }
}
