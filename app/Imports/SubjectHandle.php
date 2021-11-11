<?php

namespace App\Imports;

use App\Models\CourseOffer;
use App\Models\Curriculum;
use App\Models\Section;
use App\Models\Staff;
use App\Models\Subject;
use App\Models\SubjectClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class SubjectHandle implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function __construct($_data)
    {
        $this->academic  = $_data;
    }
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $_data) {

            if ($key > 0 && $_data[0]) {
                $_course = $_data[5] == "BSMT" ? 2 : ($_data[5] == "BSMARE" ? 1 : 3); // Set Course ID
                $_curriculum = Curriculum::where('curriculum_name', 'like', '%' . $_data[6] . '%')->first(); // Get Curriculum Details
                $_subject = Subject::select('cs.id as id')
                    ->join('curriculum_subjects as cs', 'subjects.id', 'cs.subject_id')
                    ->where('cs.curriculum_id', $_curriculum->id)
                    ->where('cs.course_id', $_course)
                    ->where('cs.year_level', $_data[3])
                    ->where('subjects.subject_code', $_data[2])
                    ->first(); // Get the Curriculum Subject Details
                $_section_details = array(
                    'section_name' => $_data[3] . "/C " . $_data[5] . " " . $_data[4],
                    'academic_id' => $this->academic->id,
                    'course_id' => $_course,
                    'year_level' => $_data[3] . "/C",
                ); // Set up the section Details for the Checking and Saving
                $_section = Section::where($_section_details)->first() ?: Section::create([
                    'section_name' => $_data[3] . "/C " . $_data[5] . " " . $_data[4],
                    'academic_id' => $this->academic->id,
                    'course_id' => $_course,
                    'year_level' => $_data[3] . "/C",
                    'created_by' => Auth::user()->name,
                    'is_removed' => 0

                ]); // Check and Save
                // Find Teacher
                $_teacher = Staff::where('last_name', $_data[1])->where('first_name', "like", "%" . $_data[0] . "%")->first();
                if ($_teacher) {
                    if ($_subject) {
                        if ($_section) {
                            //echo  $_data[3] . "/C " . $_data[5] . " " . $_data[4] . " | ";
                            SubjectClass::create([
                                'staff_id' => $_teacher->id,
                                'curriculum_subject_id' => $_subject->id,
                                'academic_id' => $this->academic->id,
                                'section_id' => $_section->id,
                                'created_by' => Auth::user()->name,
                                'is_removed' => 0
                            ]);
                        } else {
                            echo $_data[0] . " " . $_data[1] . " | ";
                            echo $_data[2] . " | " . $_data[3] . "/C " . $_data[5] . " " . $_data[4] . " | ";
                            echo "Missing Section: "  . $_data[3] . "/C " . $_data[5] . " " . $_data[4] . "<br><br>";
                        }
                    } else {
                        echo $_data[0] . " " . $_data[1] . " | ";
                        echo $_data[2] . " | " . $_data[3] . "/C " . $_data[5] . " " . $_data[4] . " | ";
                        echo "Missing Subject: " . $_data[2] . "<br><br>";
                    }
                } else {
                    echo $_data[0] . " " . $_data[1] . " | ";
                    echo $_data[2] . " | " . $_data[3] . "/C " . $_data[5] . " " . $_data[4] . " | ";
                    echo " This Teacher is missing <br><br>";
                }

                // Find Section
                // Find Subject by Curriculum

            }
        }
    }
}
