<?php

namespace App\Imports;

use App\Models\GradeEncode;
use App\Models\StudentAccount;
use App\Models\StudentSection;
use App\Models\SubjectClass;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;

class GradeBulkImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public $section;
    public function __construct($_section)
    {
        $this->section = Crypt::decrypt($_section);
    }
    public function collection(Collection $collection)
    {
        $this->grade_upload_v2($collection);
    }
    public function grade_upload_v2($collection)
    {
        $section = SubjectClass::find($this->section); // Get the Subject Section
        $_path = '/upload-logs/upload-grades/' . $section->academic->school_year . '/' . $section->academic->semester . '/' . str_replace('/', '', $section->section->section_name) . '/'; // Set the File Path
        $_file_name = $_path . str_replace(' ', '-', str_replace('/', '', $section->section->section_name) . ' ' . $section->curriculum_subject->subject->subject_code . date('dmyhis')) . '.log'; // Set the Filename
        $_headers = $collection[0]; // The Headers

        foreach ($collection as $key => $_data) {
            $_data_to_log[] = $_SERVER['REMOTE_ADDR'];
            $_data_to_log[] = date('Y-m-d H:i:s');
            if ($key > 0 && $_data[0]) {
                $_account = StudentAccount::where('email', $_data[5])->first(); // Find Student Id
                if ($_account) {
                    $_student_subject = StudentSection::where('student_id', $_account->student_id)
                        ->where('section_id', $section->section->id)
                        ->first();
                    if ($_student_subject) {
                        $_data_to_log[] = 'Email : ' . $_data[5]; // Email Status for Logs
                        foreach ($_headers as $column => $header) {
                            if ($column > 5) {
                                // Fetch the Headers
                                //$_data_to_log[] .= PHP_EOL; // Next line in log file
                                $_data_to_log[] = 'Header: ' . trim($header);
                                $_data_header = $this->header_checker_v2(strtoupper(strtolower($header)));
                                //$_data_to_log[] .= PHP_EOL; // Next line in log file
                                $_data_to_log[] = 'Section: ' . $this->section;
                                $_data_to_log[] = 'Student: ' . $_account->student_id;
                                if ($_data_header['type'] != null) {
                                    $_score_details = [
                                        'student_id' => $_account->student_id,
                                        'subject_class_id' => $this->section,
                                        'period' => strtolower(trim($_data_header['period'])),
                                        'type' => $_data_header['type'],
                                    ];
                                    // Score Details
                                    $_data_to_log[] = 'Header status: ' . implode(':', $_data_header);
                                    $_check_details = GradeEncode::where($_score_details)->first();
                                    if ($_data[$column] !== null) {
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
                                    }
                                } else {
                                    $_data_to_log[] = 'Header Status: Invalid ';
                                    $_data_to_log[] = 'Header Error: ' . $_data_header['error'];
                                }
                                // $_data_to_log[] .= PHP_EOL; // Next line in log file
                            }
                        }
                        //$_data_to_log[] .= PHP_EOL; // Next line in log file
                    } else {
                        $_data_to_log[] = 'Section : ' . $_data[5] . ' | Invalid Section'; // Email Status
                    }
                    $_data_to_log[] = 'Email : ' . $_data[5] . ' | Invalid Student'; // Email Status
                }
            } else {
                $_data_to_log[] = 'Email : ' . $_data[5] . ' | Missing Student'; // Email Status
            }

            //$_data_to_log[] .= PHP_EOL; // Next line in log file
        }
        // Config for the Log Activities
        $_data_to_log = implode(' - ', $_data_to_log);
        Storage::disk('public')->append($_file_name, $_data_to_log, null);
    }
    public function header_checker_v2($_value)
    {
        $_data = explode(':', $_value); // Separates the Header Categories
        if (count($_data) > 2) {
            $_index_zero = trim($_data[0]); // First Value
            $_index_one = trim($_data[1]); // Second Value
            $_index_two = trim($_data[2]); // Three Value
            $_period = isset($_index_one) ? $_index_one : null; // get the Period of terms
            $_number = count($_data) > 2 ? (int) filter_var($_index_two, FILTER_SANITIZE_NUMBER_INT) : ''; // Get the Number of Item of Category
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
                        case str_contains($_index_two, 'OUTCOME'):
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
        return ['type' => $_label, 'period' => $_period, 'error' => $_error];
    }
}
