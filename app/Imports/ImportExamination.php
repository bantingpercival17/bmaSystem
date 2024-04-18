<?php

namespace App\Imports;

use App\Models\Examination;
use App\Models\ExaminationCategory;
use App\Models\ExaminationQuestion;
use App\Models\ExaminationQuestionChoice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportExamination implements ToCollection
{
    public $data;
    /**
     * @param Collection $collection
     */
    public function __construct($_data)
    {
        $this->data = base64_decode($_data);
    }
    public function collection(Collection $collection)
    {
        $_examination = Examination::find($this->data);
        foreach ($collection as $key => $value) {
            if ($value[0] != 'SUBJECT_NAME') {
                // Find Examination Category
                $_examination_category = ExaminationCategory::where([
                    'examination_id' => $_examination->id,
                    'subject_name' => $value[0],
                    'category_name' => $value[1],
                    'is_removed' => 0
                ])->first();
                if ($_examination_category) {
                    // If the Category is Exsiting
                    $this->create_question($_examination_category->id, $value);
                } else {
                    // If New Category
                    $_data = array(
                        'examination_id' => $_examination->id,
                        'subject_name' => $value[0],
                        'category_name' => $value[1],
                        'instruction' => $value[2],
                        'image_path' => $value[3],
                    );
                    // Create Category
                    $_category = ExaminationCategory::create($_data);
                    $this->create_question($_category->id, $value);
                }
            }
        }
    }
    public function create_question($_category_id, $_value)
    {
        $_data = array(
            'category_id' => $_category_id,
            'question' => strlen($_value[4]) > 0 ? $_value[4] : null,
            'image_path' => strlen($_value[5]) > 0 ? $_value[5] : null,
            'score' => $_value[6]
        );
        // Create Question
        $_question =  ExaminationQuestion::create($_data);
        echo $_value[7];
        echo "<br>";
        foreach (json_decode($_value[7]) as $key => $choices) {
            $_dataChoices = array(
                'question_id' => $_question->id,
                'choice_name' => $choices->choices_name,
                'image_path' => null,
                'is_answer' => $choices->choices_status
            );
            // create Choices
            ExaminationQuestionChoice::create($_dataChoices);
        }
    }
}
