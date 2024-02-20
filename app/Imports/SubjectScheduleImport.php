<?php

namespace App\Imports;

use App\Models\Section;
use App\Models\SubjectClass;
use App\Models\SubjectClassSchedule;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

class SubjectScheduleImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $_feedback_message = "";
        $_log_path = "/registrar/schedule-import/" . date('dmy') . ".log";
        foreach ($collection as $key => $value) {
            if (count($value) > 4) {
                if ($key != 0) {
                    $dataToLog[] = array(
                        date("Y-m-d H:i:s"), //Date and time
                        $_SERVER['REMOTE_ADDR'], //IP address
                        'USER: ' . Auth::user()->name
                    );
                    $academic = base64_decode($value[0]); // Academic Year
                    $subject = base64_decode($value[1]); // Subject Course
                    $section = base64_decode($value[2]); // Section
                    $email = $value[5]; // Email
                    if ($email !== null) {
                        $user = User::where('email', $email)->first(); // Check if Exsiting
                        $section = Section::find($section); // Check if Exsiting
                        $checkTeachingLoad = SubjectClass::where(['curriculum_subject_id' => $subject, 'academic_id' => $academic, 'section_id' => $section->id, 'is_removed' => false])->first();
                        if ($user) {
                            $subjectClassDetail = array(
                                'staff_id' => $user->staff->id,
                                'curriculum_subject_id' => $subject,
                                'academic_id' => $academic,
                                'section_id' => $section->id,
                                'created_by' => Auth::user()->name,
                                'is_removed' => false,
                            );
                            if ($checkTeachingLoad) {
                                $checkTeachingLoad->update($subjectClassDetail); // Update Teaching Load
                                $this->classSchedule($checkTeachingLoad, $value);
                                $dataToLog[] = 'PROCESS STATUS: ' . $value[3] . " updated to section of " . $section->section_name . ' Successfully Completed';
                                $_feedback_message = $value[3] . " updated to section of " . $section->section_name . ' Successfully Completed<br>';
                            } else {
                                $subject = SubjectClass::create($subjectClassDetail);
                                $this->classSchedule($subject, $value);
                                //SubjectClass::create($subjectClassDetail); // Save Teaching Load
                                $dataToLog[] = 'PROCESS STATUS: ' . $value[3] . " saved to section of " . $section->section_name . ' Successfully Completed';
                                $_feedback_message =  $value[3] . " saved to section of " . $section->section_name . ' Successfully Completed<br>';
                            }
                        }/*  else {
                            $dataToLog[] = 'PROCESS STATUS: ' . "Missing Teacher for Row " . ($key + 1);
                            $_feedback_message = "Missing Teacher for Row " . ($key + 1) . "<br>";
                        } */
                    } /* else {
                        $dataToLog[] = 'PROCESS STATUS: ' .  "No Teacher Assigned on Row " . ($key + 1);
                        $_feedback_message = "No Teacher Assigned on Row " . ($key + 1) . "<br>";
                    } */
                }
                /*  $dataToLog[] .= PHP_EOL; // Next line in log file
                echo $_feedback_message;
                $dataToLog = implode(" - ", $dataToLog);
                Storage::disk('public')->append($_log_path, $dataToLog, null); */
                /*  if ($key != 0) {
                    $_academic = base64_decode($value[0]);
                    $_academic = base64_decode($value[0]); // Academic Year
                    $_subject = base64_decode($value[1]); // Subject Course
                    $_section = base64_decode($value[2]);
                    $dataToLog = array(
                        date("Y-m-d H:i:s"), //Date and time
                        $_SERVER['REMOTE_ADDR'], //IP address
                        'USER: ' . Auth::user()->name
                    );

                    if ($value[5] !== null) {
                        $_staff = User::where('email', $value[5])->first();
                        if ($_staff) {
                            $_subject_class_detail = [
                                'staff_id' => $_staff->staff->id,
                                'curriculum_subject_id' => $_subject,
                                'academic_id' => $_academic,
                                'section_id' => $_section,
                                'created_by' => Auth::user()->name,
                                'is_removed' => 0,
                            ];
                            $_check = SubjectClass::where([
                                'staff_id' => $_staff->staff->id,
                                'curriculum_subject_id' => $_subject,
                                'academic_id' => $_academic,
                                'section_id' => $_section,
                                'is_removed' => false
                            ])->first();
                            $_section = Section::find($_section);
                            if ($_check) {
                                $_check->update($_subject_class_detail);
                                $dataToLog[] = 'PROCESS STATUS: ' . $value[3] . " updated to section of " . $_section->section_name . ' Successfully Completed';
                                $_feedback_message = $value[3] . " updated to section of " . $_section->section_name . ' Successfully Completed<br>';
                            } else {
                                SubjectClass::create($_subject_class_detail);
                                $dataToLog[] = 'PROCESS STATUS: ' . $value[3] . " saved to section of " . $_section->section_name . ' Successfully Completed';
                                $_feedback_message =  $value[3] . " saved to section of " . $_section->section_name . ' Successfully Completed<br>';
                            }
                        } else {
                            $dataToLog[] = 'PROCESS STATUS: ' . "Missing Teacher for Row " . ($key + 1);

                            $_feedback_message = "Missing Teacher for Row " . ($key + 1) . "<br>";
                        }
                    } else {
                        $dataToLog[] = 'PROCESS STATUS: ' .  "No Teacher Assigned on Row " . ($key + 1);
                        $_feedback_message = "No Teacher Assigned on Row " . ($key + 1) . "<br>";
                    }
                    $dataToLog[] .= PHP_EOL; // Next line in log file
                    echo $_feedback_message;
                    $dataToLog = implode(" - ", $dataToLog);
                    Storage::disk('public')->append($_log_path, $dataToLog, null);
                } */
            }
        }
    }
    function classSchedule($class, $collection)
    {
        $days = array(
            'Monday' => $collection[7],
            'Tuesday' => $collection[8],
            'Wednesday' => $collection[9],
            'Thursday' => $collection[10],
            'Friday' => $collection[11]
        );
        SubjectClassSchedule::where('subject_class_id', $class->id)->update(['is_removed' => true]);
        foreach ($days as $day => $value) {
            if ($value !== null) {
                $separateTimeByComa =  explode(',', $value); // Seperate value if they use Coma
                if (count($separateTimeByComa) > 0) {
                    foreach ($separateTimeByComa as $key => $value1) {
                        $this->storeSchedule($value1, $class, $day);
                    }
                } else {
                    $this->storeSchedule($value, $class, $day);
                }
            }
        }
    }
    function storeSchedule($value, $class, $day)
    {
        try {
            $separateTimeByDash = explode('-', $value);
            $startTime = $separateTimeByDash[0];
            $endTime = $separateTimeByDash[1];
            $classSchedule = SubjectClassSchedule::where('subject_class_id', $class->id)
                ->where('day', $day)->where('start_time', $startTime . ':00')->where('end_time', $endTime . ':00')->first();
            if (!$classSchedule) {
                $schedule = array(
                    'subject_class_id' => $class->id,
                    'day' => $day,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'created_by' => Auth::user()->name,
                    'is_removed' => false
                );
                SubjectClassSchedule::create($schedule);
            } else {
                $classSchedule->is_removed = false;
                $classSchedule->save();
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
