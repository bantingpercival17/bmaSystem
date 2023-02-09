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
        //$this->grade_upload($collection);
        $this->grade_upload_v2($collection);
    }
    public function grade_upload($collection)
    {
        $_section = SubjectClass::find($this->section);
        $_headers = $collection[0];
        $_file_name = 'log/' . str_replace(' ', '_', $_section->section->section_name)  . "/" . str_replace(' ', '_', $_section->curriculum_subject->subject->subject_code) . date('d_m_y') . '.log';
        foreach ($collection as $key => $_data) {
            if ($key > 0 && $_data[0]) {
                $_account = StudentAccount::where('email',  $_data[5])->first(); // Find Student Id
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
                                        $_save = GradeEncode::where($_score_details)->update(['score' => floatval($_data[$_key]), 'is_removed' => 0]);
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
                    case str_contains($_data[2], 'ASSESSMENT') || str_contains($_data[2], 'EXAMINATION'):
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

    public function grade_upload_v2($collection)
    {
        $_section = SubjectClass::find($this->section); // Get the Subject Section
        $_path = '/upload-logs/upload-grades/' . $_section->academic->school_year . '/' . str_replace('/', '', $_section->section->section_name) . "/"; // Set the File Path
        $_file_name = $_path . str_replace(' ', '-', str_replace('/', '', $_section->section->section_name) . " " . $_section->curriculum_subject->subject->subject_code . date('dmyhis')) . '.log'; // Set the Filename
        $_headers = $collection[0]; // The Headers

        foreach ($collection as $key => $_data) {
            $_data_to_log[] =  $_SERVER['REMOTE_ADDR'];
            $_data_to_log[]  = date("Y-m-d H:i:s");
            if ($key > 0 && $_data[0]) {
                $_account = StudentAccount::where('email',  $_data[5])->first(); // Find Student Id
                if ($_account) {
                    $_student_subject = StudentSection::where('student_id', $_account->student_id)->where('section_id', $_section->section->id)->first();
                    if ($_student_subject) {
                        $_data_to_log[] = 'Email : ' . $_data[5]; // Email Status for Logs
                        foreach ($_headers as $column => $header) {
                            if ($column > 5) {
                                // Fetch the Headers
                                $_data_to_log[] .= PHP_EOL; // Next line in log file
                                $_data_to_log[] = "Header: "  . trim($header);
                                $_data_header = $this->header_checker_v2(strtoupper(strtolower($header)));
                                $_data_to_log[] .= PHP_EOL; // Next line in log file
                                $_data_to_log[] = "Section: " . $this->section;
                                $_data_to_log[] = "Student: " . $_account->student_id;
                                if ($_data_header['type'] != null) {
                                    $_score_details = array(
                                        'student_id' => $_account->student_id,
                                        'subject_class_id' => $this->section,
                                        'period' => strtolower(trim($_data_header['period'])),
                                        'type' => $_data_header['type'],
                                    );
                                    // Score Details
                                    $_data_to_log[] = 'Header status: ' . implode(':', $_data_header);
                                    $_check_details = GradeEncode::where($_score_details)->first();
                                    if ($_check_details) {
                                        // Update Score
                                        $_save = GradeEncode::where($_score_details)->update(['score' => floatval($_data[$column]), 'is_removed' => 0]);
                                        $_data_to_log[] = 'Updated Grades';
                                    } else {
                                        // Save Score
                                        $_score_details['score'] = floatval($_data[$column]);
                                        $_score_details['is_removed'] = 0;
                                        $_save = GradeEncode::create($_score_details);
                                        $_data_to_log[] = 'Saved Grades:' . floatval($_data[$column]);
                                    }
                                } else {
                                    $_data_to_log[] = "Header Status: Invalid ";
                                    $_data_to_log[] = 'Header Error: ' . $_data_header['error'];
                                }
                                // $_data_to_log[] .= PHP_EOL; // Next line in log file


                            }
                        }
                        $_data_to_log[] .= PHP_EOL; // Next line in log file
                    } else {
                        $_data_to_log[] = 'Section : ' . $_data[5] . " | Invalid Section"; // Email Status
                    }
                    $_data_to_log[] = 'Email : ' . $_data[5] . " | Invalid Student"; // Email Status
                }
            } else {
                $_data_to_log[] = 'Email : ' . $_data[5] . " | Missing Student"; // Email Status
            }

            $_data_to_log[] .= PHP_EOL; // Next line in log file
        }
        // Config for the Log Activities
        $_data_to_log = implode(" - ", $_data_to_log);
        Storage::disk('public')->append($_file_name, $_data_to_log, null);
    }

    public function header_checker_v2($_value)
    {
        $_data = explode(":", $_value); // Separates the Header Categories
        if (count($_data) > 2) {
            $_index_zero = trim($_data[0]); // First Value
            $_index_one = trim($_data[1]); // Second Value
            $_index_two = trim($_data[2]); // Three Value
            $_period = isset($_index_one) ? $_index_one : null; // get the Period of terms
            $_number = count($_data) > 2 ? (int)filter_var($_index_two, FILTER_SANITIZE_NUMBER_INT) : ''; // Get the Number of Item of Category
            $_error = null;
            // Check the index 0 for Category
            switch ($_index_zero) {
                case 'QUIZ':
                    switch ($_index_two) {
                        case str_contains($_index_two, 'ASSESSMENT') || str_contains($_index_two, 'EXAMINATION'):
                            $_label = $_index_one[0] . 'E1';
                            break;
                        case str_contains($_index_two, 'QUIZ'):
                            $_label = 'Q' . $_number;
                            break;
                        default:
                            $_label = null;
                            $_error = str_contains($_index_two, 'Oral');
                            break;
                    }
                    break;
                case 'ASSIGNMENT':
                    switch ($_index_two) {
                        case str_contains($_index_two, 'ORAL'):
                            $_label = 'O' . $_number;
                            break;
                        case str_contains($_index_two, 'LABORATORY'):
                            $_label = 'A' . $_number;
                            break;
                        case str_contains($_index_two, 'ACTIVITY'):
                            $_label = 'R' . $_number;
                            break;
                        case str_contains($_index_two, "COURSE-OUTCOME"):
                            $_label = 'CO' . $_number;
                            break;
                        default:
                            $_label = null;
                            $_error = str_contains($_index_two, 'Oral');
                            break;
                    }
                    break;
                default:
                    $_label = null;
                    $_error = null;
                    break;
            }
        } else {
            $_label = null;
            $_period = null;
            $_error = null;
        }
        return array('type' => $_label, 'period' => $_period, 'error' => $_error);
    }
}
