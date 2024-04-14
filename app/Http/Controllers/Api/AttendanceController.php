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
    function fetch_attendance()
    {
        $data = array('employee' => $this->attendanceTable('employee'), 'student' => $this->attendanceTable('student'));
        return response(['data' => $data], 200);
    }
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
                return $this->studentProcess($data);
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
                return response(['data' => ['status' => 'error', 'message' => 'Invalid User']], 200);
            }

            $profile = [
                'type' => 'student',
                'image' => asset($account->student->profile_picture()),
                'email' => str_replace('@bma.edu.ph', '', $account->email),
                'name' => strtoupper(trim($account->student->last_name . ', ' . $account->student->first_name)),
                'department' => $account->student->enrollment_assessment->course->course_name,
                /*  'section' => $account->student->current_section->section->section_name, */
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
            return response(['data' => $profile], 200);
            /*   $this->alert($profile['time_status'], $profile['message'], 'success', $profile['sound']); */
            /* return response(['data' => ['status' => 'error', 'message' => $th->getMessage(),]], 200);
            return $profile; */
        } catch (\Throwable $th) {
            /* $this->alert('Scanner Error', $th->getMessage(), 'warning', 'error'); */
            return response(['data' => ['status' => 'error', 'message' => $th->getMessage(),]], 200);
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
                'image' => asset($account->staff->profile_picture()),
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
    public function attendanceTable($user)
    {
        $first_day =  new DateTime();
        $last_day = new DateTime();
        $first_day->modify('last Sunday');
        $last_day->modify('Next Saturday');
        $week_dates = array(
            $first_day->format('Y-m-d') . '%',  $last_day->format('Y-m-d') . '%'
        );
        switch ($user) {
            case 'employee':
                return EmployeeAttendance::select('users.email', 'staff.first_name', 'staff.last_name', 'staff.department', 'employee_attendances.time_in', 'employee_attendances.time_out')
                    ->join('staff', 'staff.id', 'employee_attendances.staff_id')
                    ->join('users', 'users.id', 'staff.user_id')
                    ->where('employee_attendances.created_at', 'like', '%' . now()->format('Y-m-d') . '%')
                    ->orderBy('employee_attendances.updated_at', 'desc')
                    ->get();
            case 'student':
                return StudentOnboardingAttendance::select('student_accounts.email', 'student_details.first_name', 'student_details.last_name', 'student_onboarding_attendances.time_in', 'student_onboarding_attendances.time_out')
                    ->join('student_details', 'student_details.id', 'student_onboarding_attendances.student_id')
                    ->join('student_accounts', 'student_accounts.student_id', 'student_details.id')
                    /* ->join('enrollment_assessments', 'enrollment_assessments.student_id', 'student_details.id')
                    ->join('course_offers', 'course_offers.id', 'enrollment_assessments.course_id') */
                    ->whereBetween('student_onboarding_attendances.created_at', $week_dates)
                    ->orderBy('student_onboarding_attendances.updated_at', 'desc')->get();
            default:
                return [];
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
    function data_sync()
    {
        try {
            // Employee Data
            $employeesList = User::all();
            foreach ($employeesList as $key => $value) {
                if ($value->staff) {
                    $employees[] = array(
                        'type' => 'employee',
                        'email' => $value->email,
                        'name' => strtoupper(trim($value->name)),
                        'department' => $value->staff->department,
                        'image' => asset($value->staff->profile_picture()),
                    );
                }
            }
            $students = [];
            $studentAccount = StudentAccount::where('is_actived', true)->get();
            foreach ($studentAccount as $key => $value) {
                if ($value->student) {
                    $students[] = array(
                        'type' => 'student',
                        'username' => str_replace('@bma.edu.ph', '', $value->email),
                        'name' => strtoupper(trim($value->student->first_name . ' ' . $value->student->last_name)),
                        'course' => $value->student->enrollment_assessment,
                        'image' => $value->student->profile_picture()
                    );
                }
            }
            $data = compact('employees', 'students');
            return response($data, 200);
        } catch (\Throwable $error) {
            $this->reportBug($error);
            return response([
                'message' => $error->getMessage()
            ], 500);
        }
    }
    function employee_attendance_sync(Request $request)
    {
        try {
            // Employee Data
            // Check the First if the Request data of ResponseId is not null
            if ($request->response_id) {
                $attendance = EmployeeAttendance::find($request->response_id);
                $attendance->time_out = $request->time_out;
                $attendance->save();
                $data = array(
                    'response_id' => $request->response_id,
                    'status' => '201'
                );
            } else {
                // Create / Store Attendance
                // If the the Response ID is null check the email
                $employee = User::where('email', $request->email)->first();
                $attendanceDetails = array(
                    'staff_id' => $employee->staff->id,
                    'description' => 'ATTENDANCE SYNC',
                    'time_in' => $request->time_in,
                    'time_out' => $request->time_out,
                );
                $attendance = EmployeeAttendance::create($attendanceDetails);
                $data = array(
                    'response_id' => $attendance->id,
                    'status' => '200'
                );
            }
            return response($data, 200);
        } catch (\Throwable $error) {
            $this->reportBug($error);
            return response([
                'message' => $error->getMessage()
            ], 404);
        }
    }
    function students_attendance_sync(Request $request)
    {
        try {
            $first_day =  new DateTime();
            $last_day = new DateTime();
            $first_day->modify('last Sunday');
            $last_day->modify('Next Saturday');
            $week_dates = array(
                $first_day->format('Y-m-d') . '%',  $last_day->format('Y-m-d') . '%'
            ); // the Weekly Attendance of the Student

            $account = StudentAccount::where('email',  $request->username . '@bma.edu.ph')->first(); // The Student Account Details
            if ($account) {
                $attendance = StudentOnboardingAttendance::where([
                    'student_id' => $account->student->id,
                    'course_id' => $account->student->enrollment_assessment->course_id,
                    'academic_id' => $account->student->enrollment_assessment->academic_id,
                ])
                    ->whereBetween('created_at', $week_dates)->orderBy('id', 'desc')
                    ->first(); // Check the weekly Attendance of the Student

                $content = [
                    'student_id' => $account->student->id,
                    'course_id' => $account->student->enrollment_assessment->course_id,
                    'academic_id' => $account->student->enrollment_assessment->academic_id,
                    'time_in' => $request->time_in,
                    'time_in_status' => null,
                    'time_in_remarks' => null,
                    'time_in_process_by' => 'ATTENDANCE SYNC',
                ]; // SET THE DETAILS OF THE ATTENDANCE TO STORE


                // Check the First if the Request data of ResponseId is not null
                if ($request->response_id) {
                    $attendance = StudentOnboardingAttendance::find($request->response_id);
                    $attendance->time_out = $request->time_out;
                    $attendance->save();
                    $data = array(
                        'response_id' => $request->response_id,
                        'status' => '201'
                    );
                } else {
                    // Create / Store Attendance
                    $attendance = StudentOnboardingAttendance::create($content);
                    $data = array(
                        'response_id' => $attendance->id,
                        'status' => '200'
                    );
                }
                return response($data, 200);
            } else {
                return response([
                    'message' => 'Missing Account'
                ], 404);
            }
        } catch (\Throwable $error) {
            $this->reportBug($error);
            return response([
                'message' => $error->getMessage()
            ], 404);
        }
    }
}
