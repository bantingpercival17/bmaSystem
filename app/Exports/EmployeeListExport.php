<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EmployeeListExport implements FromCollection, ShouldAutoSize, WithMapping, WithHeadings, WithEvents, WithTitle
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Staff::where('is_removed', false)->get();
    }
    public function headings(): array
    {
        return [
            'ID no',
            'LAST NAME',
            'FIRST NAME',
            'MIDDLE NAME',
            'EMAIL',
            'MARITME DEPT',
            'QR-CODE'
        ];
    }
    public function map($_data): array
    {
        $_staff_details = array(
            $_data->user->email,
            json_encode(array(
                'body_temp' => 39,
                0,
                0,
                0,

            )),
            date('Y-m-d H:i:s'),
        );
        $_qr_Code = json_encode($_staff_details);
        $_qr_Code = base64_encode($_qr_Code);
        return [
            $_data->id,
            $_data->last_name,
            $_data->first_name,
            $_data->middle_name,
            $_data->user->email,
            $_data->department,
            // QrCode::format('png')->style('round', 0.5)->eye('square')->size(300)->generate($_qr_Code)
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('A1:D1')->applyFromArray([
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
    public function title(): string
    {
        return 'Empolyee';
    }
}
