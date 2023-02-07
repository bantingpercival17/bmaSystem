<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class GradeTemplate implements FromCollection, /* ShouldAutoSize,  */ WithEvents, WithMapping, WithHeadings
{
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
        if(request()->input('_period')=='finals'){
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
        if(request()->input('_period')=='finals'){
            for ($i = 1; $i <= 10; $i++) {
                $_data += array($_count => 'Assignment :' . strtoupper(request()->input('_period')) . ": Course-Outcome No." . $i);
                $_count += 1;
            }
        }
        $_data+=array($_count=>'Quiz:'. strtoupper(request()->input('_period')) . ":EXAMINATION");
        return $_data;
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:Z1')->applyFromArray([
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
            }
        ];
    }
}
