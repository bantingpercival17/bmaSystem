<?php

namespace App\Imports;

use App\Models\Staff;
use App\Models\StaffSalaryDetailes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\ToCollection;

class ImportSalaryDetails implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $_data) {
            if ($key > 0 && $_data[0]) {
                //$_staff = Staff::find($_data[0]);
                echo $_data[0] . " | ";
                echo $_data[4] ?: null;
                echo   "<br>";
                $this->process($_data);
            }
        }
    }
    public function process($_data)
    {
        $_staff = StaffSalaryDetailes::where('staff_id', $_data[0])->where('is_removed', false)->first();
        $_details = array(
            'staff_id' => $_data[0],
            'salary_amount' => $_data[2],
            'allowance_amount' => $_data[3],
            'sss_contribution' => $_data[4],
            'philhealth_contribution' => $_data[5],
            'pagibig_contribution' =>  $_data[6],
            'created_by_id' => Auth::user()->staff->id
        );
        $_data_details = implode(" | ", $_details);
        $_data_to_log = array(
            date("Y-m-d H:i:s"), //Date and time
            $_SERVER['REMOTE_ADDR'], //IP address
            'staff_id : ' . $_data[0],
            'staff_name : ' . $_data[1],
            ///'staff_details : ' . $_data_details
        ); // Set the Logs

        if ($_staff) {
            $_staff->is_removed = true;
            $_staff->save();
            StaffSalaryDetailes::create($_details);
            $_data_to_log[] = 'process-type : new-salary - process-status : done';
        } else {
            StaffSalaryDetailes::create($_details);
            $_data_to_log[] = 'process-type : new-salary - process-status : done';
        }
        $_data_to_log[] .= PHP_EOL; // Next line in log file
        $_data_to_log = implode(" - ", $_data_to_log);
        //Add a newline onto the end.
        $_file_name = 'log/accounting/upload-salary' . date('d_m_y') . '.log';
        Storage::disk('public')->append($_file_name, $_data_to_log, null);
    }
}
