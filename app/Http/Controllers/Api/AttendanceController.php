<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DebugReport;
use App\Models\EmployeeAttendance;
use App\Models\StudentAccount;
use App\Models\StudentOnboardingAttendance;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    function store_attendance(Request $request)
    {
        try {
            $data = $request->data;
            $word = 'employee';
            if (strpos($data, $word) !== false) {
                $data = explode(":", $data);
                $data = count($data) > 1 ? $data[1] : str_replace($word, '', $data);
                return $this->employeeProcess($data);
            } else {
                $data = explode(".", $data);
                $data = count($data) > 1 ? $data[0] : '';
                $this->studentProcess($data);
            }
        } catch (\Throwable $error) {
            $this->reportBug($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    public function studentProcess($username)
    {
        $first_day =  new DateTime();
        $last_day = new DateTime();
        $first_day->modify('last Sunday');
        $last_day->modify('Next Saturday');
        $week_dates = array(
            $first_day->format('Y-m-d') . '%',  $last_day->format('Y-m-d') . '%'
        );
        try {
            $account = StudentAccount::where('student_number', $username)->first();

            if (!$account) {
                //('Invalid User', 'No Data', 'warning', 'error');
                return null;
            }

            $profile = [
                'type' => 'student',
                'image' => $account->student->profile_picture(),
                'name' => strtoupper(trim($account->student->last_name . ', ' . $account->student->first_name)),
                'course' => $account->student->enrollment_assessment->course->course_name,
                'section' => $account->student->current_section->section->section_name,
            ];


            $attendance = StudentOnboardingAttendance::where([
                'student_id' => $account->student->id,
                'course_id' => $account->student->enrollment_assessment->course_id,
                'academic_id' => $account->student->enrollment_assessment->academic_id,
            ])
                ->whereBetween('created_at', $week_dates)->orderBy('id', 'desc')
                ->first();
            $content = [
                'student_id' => $account->student->id,
                'course_id' => $account->student->enrollment_assessment->course_id,
                'academic_id' => $account->student->enrollment_assessment->academic_id,
                'time_in' => now(),
                'time_in_status' => null,
                'time_in_remarks' => null,
                'time_in_process_by' => 'Save by System',
            ];

            if ($attendance) {
                if ($attendance->time_out !== null) {
                    StudentOnboardingAttendance::create($content);
                    $profile['time_status'] = 'TIME IN';
                    $profile['message'] = 'Welcome ' . $account->student->first_name . ', have a great day.';
                    $profile['sound'] = '/assets/audio/' . trim(strtolower(str_replace(' ', '', str_replace('/', '-', $account->student->first_name)))) . '-good-morning.mp3';
                } else {
                    $attendance->time_out = now();
                    $attendance->time_out_process_by = 'Save by System';
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
                return response(['data' => ['status' => 'error', 'message' => 'Invalid User']], 200);
            }
            $profile = [
                'type' => 'employee',
                'email' => $account->email,
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

            //$this->alert($profile['time_status'], $profile['message'], 'success', $profile['sound']);
            $profile['attendance'] = $account->staff->daily_attendance;
            return response(['data' => $profile], 200);
        } catch (\Throwable $th) {
            return response(['data' => ['status' => 'error', 'message' => $th->getMessage(),]], 200);
        }
    }

    function reportBug($error)
    {
        $_current_url = sprintf('%s://%s/%s', isset($_SERVER['HTTPS']) ? 'https' : 'http', $_SERVER['HTTP_HOST'], trim($_SERVER['REQUEST_URI'], '/\\'));
        $_data = array(
            'type_of_user' => 'admin',
            'user_name' => 'QR-Code Scanner',
            'user_ip_address' => $_SERVER['REMOTE_ADDR'] . ', ' . $_SERVER['HTTP_USER_AGENT'],
            'error_message' => $error->getMessage(),
            'url_error' => $_current_url,
            'is_status' => 0,
        );
        DebugReport::create($_data);
    }
}
