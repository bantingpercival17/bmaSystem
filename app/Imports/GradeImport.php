<?php

namespace App\Imports;

use App\Models\GradeEncode;
use App\Models\Section;
use App\Models\StudentAccount;
use App\Models\StudentSection;
use App\Models\SubjectClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;

class GradeImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function __construct($_section)
    {
        $this->section  = Crypt::decrypt($_section);
    }
    public function collection(Collection $collection)
    {
        $this->grade_upload($collection);
    }
    public function grade_upload($collection)
    {
        $_section = SubjectClass::find($this->section);
        $_headers = $collection[0];
        $_file_name = 'log/' . str_replace(' ', '_', $_section->section->section_name)  . "/" . str_replace(' ', '_', $_section->curriculum_subject->subject->subject_code) . date('d_m_y') . '.log';
        foreach ($collection as $key => $_data) {
            if ($key > 0 && $_data[0]) {
                $_account = StudentAccount::where('campus_email',  $_data[5])->first(); // Find Student Id
                // Chech if the Student is Exist
                if ($_account) {
                    $_student_subject = StudentSection::where('student_id', $_account->student_id)->where('section_id', $_section->section->id)->first();
                    if ($_student_subject) {
                        // True
                        $_data_to_log = array(
                            date("Y-m-d H:i:s"), //Date and time
                            $_SERVER['REMOTE_ADDR'], //IP address
                            'Email : ' . $_data[5],
                        ); // Set the Logs
                        $_data_to_log[] .= PHP_EOL; // Next line in log file
                        foreach ($_headers as $_key => $value) {
                            // Fetch the Headeres
                            if ($_key > 5) { // Start on 6 index
                                $_header_data = $this->header_check($value); // Check Headers and Rename
                                $_score_details = array(
                                    'student_id' => $_account->student_id,
                                    'subject_class_id' => $this->section,
                                    'period' => strtolower(trim($_header_data['period'])),
                                    'type' => $_header_data['type'],
                                ); // Score Data
                                $_data_to_log[] = implode(" | ", $_score_details);
                                $_check_details = GradeEncode::where($_score_details)->first();
                                if ($_header_data['type'] != "Q0" && $_header_data['type'] != "none0") {
                                    if ($_check_details) {
                                        // Update Score
                                        $_save = GradeEncode::where($_score_details)->update(['score' => floatval($_data[$_key])]);
                                        $_data_to_log[] = 'Updated Grades';
                                    } else {
                                        // Save Score
                                        if ($_header_data['type'] != "none0") {
                                            $_score_details['score'] = floatval($_data[$_key]);
                                            $_score_details['is_removed'] = 0;
                                            $_save = GradeEncode::create($_score_details);
                                            $_data_to_log[] = 'Saved Grades:' . floatval($_data[$_key]);
                                        }
                                    }
                                }
                                $_data_to_log[] .= PHP_EOL; // Next line in log file
                            }
                        }
                    } else {
                        // False
                        $_data_to_log = array(
                            date("Y-m-d H:i:s"), //Date and time
                            $_SERVER['REMOTE_ADDR'], //IP address
                            'Email : ' . $_data[5] . " | Missing Student",
                        );
                    }
                } else {
                    // False
                    $_data_to_log = array(
                        date("Y-m-d H:i:s"), //Date and time
                        $_SERVER['REMOTE_ADDR'], //IP address
                        'Email : ' . $_data[5] . " | Missing Student",
                    );
                }
                //Turn array into a delimited string using
                //the implode function
                $_data_to_log = implode(" - ", $_data_to_log);

                //Add a newline onto the end.
                $_data_to_log .= PHP_EOL;
                Storage::disk('public')->append($_file_name, $_data_to_log, null);
                //Storage::disk('public')->put($_file_name, $data);
            }
        }
    }
    public function header_check($_value)
    {
        $_data = explode(":", $_value);
        $_number = 0;
        if (count($_data) > 2) {
            $_number = (int)filter_var($_data[2], FILTER_SANITIZE_NUMBER_INT);
        }
        switch ($_data[0]) {
            case 'Quiz':
                switch (trim($_data[2])) {
                    case str_contains($_data[2], 'ASSESSMENT'):
                        $_label = trim($_data[1])[0] . 'E1';
                        break;
                    default:
                        $_label = 'Q' . $_number;
                        break;
                }
                break;
            case 'Assignment':
                switch (trim($_data[2])) {
                    case str_contains($_data[2], 'Oral') || str_contains($_data[2], 'ORAL'):
                        $_label = 'O' . $_number;
                        break;
                    case str_contains($_data[2], 'Laboratory') || str_contains($_data[2], 'LABORATORY'):
                        $_label = 'A' . $_number;
                        break;
                    default:
                        $_label = 'R' . $_number;
                        break;
                }
                break;

            default:
                $_label = "none";
        }

        $_period = isset($_data[1]) ? $_data[1] : null;
        return array('type' => $_label, 'period' => $_period);
    }
}
