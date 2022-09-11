<?php

namespace App\Imports;

use App\Models\SubjectClass;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;

class SubjectScheduleImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $_feedback_message = "";
        foreach ($collection as $key => $value) {
            if ($key != 0) {
                $_academic = base64_decode($value[0]); // Academic Year
                $_curriculum = base64_decode($value[1]); // Subject Curriculum,
                $_subject = base64_decode($value[2]); // Subject Course
                $_section = base64_decode($value[4]);
                $_staff = User::where('email', $value[6])->first();
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
                    if ($_check) {
                        $_check->update($_subject_class_detail);
                        //$_check->is_removed = true;
                        //$_check->save();
                        #$_check->update(['is_removed', true]);
                        $_feedback_message = $value[3] . " updated to section of " . $value[5] . ' Successfully Completed<br>';
                    } else {
                        SubjectClass::create($_subject_class_detail);
                        $_feedback_message =  $value[3] . " saved to section of " . $value[5] . ' Successfully Completed<br>';
                        //$_subject_class = $_check ?: SubjectClass::create($_subject_class_detail);
                    }
                } else {
                    $_feedback_message = "Missing Teacher for Row " . ($key + 1) . "<br>";
                }
                echo $_feedback_message;
            }
        }

        echo "<a href=''>BACK</a><br>";
    }
}
