<?php

namespace App\Imports;

use App\Models\Section;
use App\Models\SubjectClass;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;

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
                    $email = $value[5];
                    if ($email !== null) {
                        $user = User::where('email', $value[5])->first(); // Check if Exsiting
                        $section = Section::find($section); // Check if Exsiting
                        $checkTeachingLoad = SubjectClass::where(['curriculum_subject_id' => $subject, 'academic_id' => $academic, 'section_id' => $section->id, 'is_removed' => false])->first();
                        $subjectClassDetail = array(
                            'staff_id' => $user->staff->id,
                            'curriculum_subject_id' => $subject,
                            'academic_id' => $academic,
                            'section_id' => $section->id,
                            'created_by' => Auth::user()->name,
                            'is_removed' => false,
                        );
                        if ($user) {
                            if ($checkTeachingLoad) {
                                $this->classSchedule($checkTeachingLoad, $collection);
                                //$checkTeachingLoad->update($subjectClassDetail); // Update Teaching Load
                                $dataToLog[] = 'PROCESS STATUS: ' . $value[3] . " updated to section of " . $section->section_name . ' Successfully Completed';
                                $_feedback_message = $value[3] . " updated to section of " . $section->section_name . ' Successfully Completed<br>';
                            } else {
                                $subject = SubjectClass::create($subjectClassDetail);
                                $this->classSchedule($subject, $collection);
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
    }
}
