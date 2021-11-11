<?php

namespace App\Imports;

use App\Models\Section;
use App\Models\StudentAccount;
use App\Models\StudentSection as ModelsStudentSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentSection implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function __construct($_data, $_data_1)
    {
        $this->academic  = Crypt::decrypt($_data);
        $this->course = Crypt::decrypt($_data_1);
    }
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $_data) {
            if ($key > 0 && $_data[0]) {
                $_section = Section::where('section_name', $_data[3])->where('academic_id', $this->academic)->first(); // find Section
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
                }
            }
        }
    }
}
