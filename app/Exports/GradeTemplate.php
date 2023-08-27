<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Color;

class GradeTemplate implements FromCollection, ShouldAutoSize,  WithEvents, WithMapping, WithHeadings
{
    public $data;
    public $subject;
    public $totalRows;
    public $totalColums;
    public function __construct($_data, $_subject)
    {
        $this->data = $_data;
        $this->subject = $_subject;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $this->totalRows = count($this->data);
        return $this->data;
    }
    public function map($_data): array
    {
        $_count = 0;
        $_content = [];
        $_column_content = [$_data->last_name, $_data->first_name, $_data->middle_name, $_data->account->student_number, $_data->enrollment_assessment->course->course_name, $_data->account->email];
        foreach ($_column_content as $key => $value) {
            $_content += [$_count => $value];
            $_count += 1;
        }
        for ($i = 1; $i <= 10; $i++) {
            $_content_value = $_data->subject_score([$this->subject->id, request()->input('_period'), "Q" . $i]);
            $_content += array($_count => (float)$_content_value /* ? number_format($_content_value, 2, '.', '') : '' */);
            $_count += 1;
        }
        for ($i = 1; $i <= 5; $i++) {
            $_content_value = $_data->subject_score([$this->subject->id, request()->input('_period'), "O" . $i]);
            $_content += array($_count => (float)$_content_value /* ? number_format($_content_value, 2, '.', '') : '' */);
            $_count += 1;
        }
        for ($i = 1; $i <= 10; $i++) {
            $_content_value = $_data->subject_score([$this->subject->id, request()->input('_period'), "R" . $i]);
            $_content += array($_count => (float)$_content_value /* ? number_format($_content_value, 2, '.', '') : '' */);
            $_count += 1;
        }
        if ($this->subject->curriculum_subject->subject->laboratory_hours > 0) {
            for ($i = 1; $i <= 10; $i++) {
                $_content_value = $_data->subject_score([$this->subject->id, request()->input('_period'), "A" . $i]);
                $_content += array($_count => (float)$_content_value /* ? number_format($_content_value, 2, '.', '') : '' */);
                $_count += 1;
            }
        }
        if (request()->input('_period') == 'finals') {
            for ($i = 1; $i <= 10; $i++) {
                $_content_value = $_data->subject_score([$this->subject->id, request()->input('_period'), "CO" . $i]);
                $_content += array($_count => (float)$_content_value /* ? number_format($_content_value, 2, '.', '') : '' */);
                $_count += 1;
            }
        }

        return $_content;
    }
    public function headings(): array
    {
        $_count = 0;
        $_column_name = ['LAST NAME', 'FIRST NAME', 'MIDDLE NAME', 'STUDENT NUMBER', 'COURSE', 'EMAIL',];
        $_data = [];
        foreach ($_column_name as $key => $value) {
            $_data += [$_count => $value];
            $_count += 1;
        }
        for ($i = 1; $i <= 10; $i++) {
            $_data += array($_count => 'Quiz :' . strtoupper(request()->input('_period')) . ": Quiz No." . $i);
            $_count += 1;
        }
        for ($i = 1; $i <= 5; $i++) {
            $_data += array($_count => 'Assignment :' . strtoupper(request()->input('_period')) . ": Oral No." . $i);
            $_count += 1;
        }
        for ($i = 1; $i <= 10; $i++) {
            $_data += array($_count => 'Assignment :' . strtoupper(request()->input('_period')) . ": Activity No." . $i);
            $_count += 1;
        }
        if ($this->subject->curriculum_subject->subject->laboratory_hours > 0) {
            for ($i = 1; $i <= 10; $i++) {
                $_data += array($_count => 'Assignment :' . strtoupper(request()->input('_period')) . ": Laboratory No." . $i);
                $_count += 1;
            }
        }
        $_data += array($_count => 'Quiz:' . strtoupper(request()->input('_period')) . ":EXAMINATION");
        if (request()->input('_period') == 'finals') {
            for ($r = 1; $r <= 10; $r++) {
                $_data += array($_count => 'Assignment :' . strtoupper(request()->input('_period')) . ": Course Outcome No." . $r);
                $_count += 1;
            }
        }
        $this->totalColums = count($_data);
        return $_data;
    }
    public function registerEvents(): array
    {

        return [
            AfterSheet::class => function (AfterSheet $event) {
                $cells = 'A1:' . $this->numberToAlphabet($this->totalColums) . '1';
                $event->sheet->getStyle($cells)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                    ->setWrapText(true);
                $event->sheet->getStyle($cells)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $event->sheet->getStyle($cells)->getFill()->getStartColor()->setARGB('18995B'); // Hex color code
                $event->sheet->getStyle($cells)->getFont()->setColor(new Color(Color::COLOR_WHITE));
                $event->sheet->getStyle($cells)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '0000000'],
                        ],
                    ]
                ]);
                $second = 'G1:' . $this->numberToAlphabet($this->totalColums) . '1';
                $event->sheet->getColumnDimension('G')->setWidth(10);
                $event->sheet->getColumnDimension('H')->setWidth(10);
                $event->sheet->getColumnDimension('I')->setWidth(10);
                $sheets = 'A2:' . $this->numberToAlphabet($this->totalColums) . ($this->totalRows + 1);
                $event->sheet->getStyle($sheets)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '0000000'],
                        ],
                    ]
                ]);
            }
        ];
    }
    function numberToAlphabet($number)
    {
        $alphabet = range('A', 'Z');
        $result = '';

        while ($number > 0) {
            $remainder = ($number - 1) % 26;
            $result = $alphabet[$remainder] . $result;
            $number = intval(($number - 1) / 26);
        }

        return $result;
    }
}
