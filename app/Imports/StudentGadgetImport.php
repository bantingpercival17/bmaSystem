<?php

namespace App\Imports;

use App\Models\StudentAccount;
use App\Models\StudentGadget;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class StudentGadgetImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            $serial_number = $value[0];
            $student_number = $value[1];
            if ($student_number != '') {
                $_student = StudentAccount::where('student_number', $student_number)->where('is_removed', false)->first();
                $_check = StudentGadget::where('student_id', $_student->student_id)->where('gadget_serial', $serial_number)->first();
                if (!$_check) {
                    StudentGadget::create([
                        'student_id' => $_student->student_id,
                        'gadget_type' => $value[2],
                        'gadget_brand' => $value[3],
                        'gadget_serial' => $serial_number,
                    ]);
                    echo "Row " . $key . ": Saved Data. <br>";
                }
            } else {
                echo "Row " . $key . ": No Student Number <br>";
            }
        }
    }
}
