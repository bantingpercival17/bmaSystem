<?php

namespace App\Imports;

use App\Models\ExaminationCategory;
use App\Models\ExaminationQuestion;
use App\Models\ExaminationQuestionChoice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportExaminationV2 implements ToCollection
{
    public $examination;
    /**
     * @param Collection $collection
     */
    public function __construct($_data)
    {
        $this->examination = base64_decode($_data);
    }
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $value) {
            if ($key > 0) {
                if ($value[0] != '') {
                    $category = $this->store_category($value); // Store Category
                    $question = $this->store_question($category, $value);
                    $this->store_choices($question, $value);
                }
            }
        }
    }
    function store_category($data)
    {
        $category = ExaminationCategory::where([
            'examination_id' => $this->examination,
            'subject_name' => $data[0],
            'category_name' => $data[1],
            'is_removed' => 0
        ])->first();
        if (!$category) {
            $data = array(
                'examination_id' => $this->examination,
                'subject_name' => $data[0],
                'category_name' => $data[1],
                'instruction' => $data[2],
                'image_path' => $data[3],
            );
            // Create Category
            $category = ExaminationCategory::create($data);
        }
        return $category;
    }
    function store_question($category, $data)
    {
        $_data = array(
            'category_id' => $category->id,
            'question' => strlen($data[4]) > 0 ? $data[4] : null,
            'image_path' => strlen($data[5]) > 0 ? $data[5] : null,
            'score' => 1
        );
        // Create Question
        return ExaminationQuestion::create($_data);
    }
    function store_choices($question, $data)
    {
        $data = array(
            array(
                'question_id' => $question->id,
                'choice_name' => strlen($data[6]) > 0 ? $data[6] : 'null',
                'image_path' => strlen($data[10]) > 0 ? $data[10] : null,
                'is_answer' => $data[14]
            ),
            array(
                'question_id' => $question->id,
                'choice_name' => strlen($data[7]) > 0 ? $data[7] : 'null',
                'image_path' => strlen($data[11]) > 0 ? $data[11] : null,
                'is_answer' => $data[15]
            ),
            array(
                'question_id' => $question->id,
                'choice_name' => strlen($data[8]) > 0 ? $data[8] : 'null',
                'image_path' => strlen($data[12]) > 0 ? $data[12] : null,
                'is_answer' => $data[16]
            ),
            array(
                'question_id' => $question->id,
                'choice_name' => strlen($data[9]) > 0 ? $data[9] : 'null',
                'image_path' => strlen($data[13]) > 0 ? $data[13] : null,
                'is_answer' => $data[17]
            )
        );
        //echo json_encode($data);
        //echo "<br>";
        ExaminationQuestionChoice::create($data[0]);
        ExaminationQuestionChoice::create($data[1]);
        ExaminationQuestionChoice::create($data[2]);
        ExaminationQuestionChoice::create($data[3]);
         /* foreach ($data as $key => $value) {
            ExaminationQuestionChoice::create($value);
        } */
    }
}
