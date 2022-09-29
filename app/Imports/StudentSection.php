<?php

namespace App\Imports;

use App\Models\Section;
use App\Models\StudentAccount;
use App\Models\StudentSection as ModelsStudentSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentSection implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function __construct($_section)
    {
        $this->section = $_section;
    }
    public function collection(Collection $collection)
    {
        $_log_path = "/registrar/section-import/" . strtoupper(str_replace(' ', '-', str_replace('/', '', $this->section->section_name))) . date('dmy') . ".log";
        foreach ($collection as $key => $_data) {
            if ($key > 0 && $_data[0]) {
                // Get the Student Number
                $_student_number = $_data[0];
                // Find the Student using the Student Number
                $_student_account = StudentAccount::where('student_number', $_student_number)->where('is_removed', false)->first();
                // Set the Import Logs
                $_data_to_log = array(
                    date("Y-m-d H:i:s"), //Date and time
                    $_SERVER['REMOTE_ADDR'], //IP address
                    'USER: ' . Auth::user()->name
                );
                if ($_student_account) {
                    $_data_to_log[] = 'STUDENT NUMBER: ' .  $_student_account->student_number;
                    // Check if the Student already Added to this Section
                    $_student_section = ModelsStudentSection::where('student_id', $_student_account->student_id)->where('section_id', $this->section->id)->first();
                    if ($_student_section) {
                        // Update the Status of the data
                        $_student_section->is_removed = false;
                        $_student_section->save();
                        $_data_to_log[] = 'PROCESS STATUS: ' .  "Successfully Updated the Section status on Row" . ($key + 1);
                    } else {
                        // If the Student is not Add to the Section it will Save/Add to the section 
                        ModelsStudentSection::create([
                            'student_id' => $_student_account->student_id,
                            'section_id' => $this->section->id,
                            'created_by' => Auth::user()->name,
                            'is_removed' => 0
                        ]);
                        $_data_to_log[] = 'PROCESS STATUS: ' .  "Successfully Added to the Section on Row" . ($key + 1);
                    }
                } else {
                    $_data_to_log[] = 'STUDENT NUMBER: ' .  "No Student Number in Row" . ($key + 1);
                }
                $_data_to_log[] .= PHP_EOL; // Next line in log file
                $_data_to_log = implode(" - ", $_data_to_log);
                Storage::disk('public')->append($_log_path, $_data_to_log, null);
                /* $_section = Section::where('section_name', $_data[3])->where('academic_id', $this->academic)->first(); // find Section
                $_student = StudentAccount::where('student_number', $_data[0])->first(); // Find Student Number
                if ($_student) {
                    if ($_section) {
                        $_student_section = ModelsStudentSection::where('student_id', $_student->student_id)->where('section_id', $_section->id)->first();
                        if (!$_student_section) {
                            ModelsStudentSection::create([
                                'student_id' => $_student->student_id,
                                'section_id' => $_section->id,
                                'created_by' => Auth::user()->name,
                                'is_removed' => 0
                            ]);
                        }
                    }
                    echo $_data[1] . " " . $_data[2] . " | " . $_section->section_name . " | ";
                    echo $_student->student_id . "<br>";
                } else {
                    echo "STUDENT MISSING : " . $_data[1] . " " . $_data[2] . "<br>";
                } */
            }
        }
    }
}
