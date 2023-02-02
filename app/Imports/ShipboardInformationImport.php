<?php

namespace App\Imports;

use App\Models\ShipBoardInformation;
use App\Models\StudentAccount;
use App\Models\StudentDetails;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ShipboardInformationImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $_data) {
            if ($key > 0 && !empty($_data[0])) {
                if (!empty($_data[2])) {
                    $_student = StudentDetails::where([
                        'last_name' => ucwords(mb_strtolower(trim($_data[2]))),
                        'first_name' => ucwords(mb_strtolower(trim($_data[1]))),
                    ])->first();
                    if ($_student) {
                        $_account = $_student->account;
                        $_shipboard = ShipBoardInformation::where('student_id', $_student->id)->first();
                        echo  ucwords(mb_strtolower(trim($_data[1] . ', ' . $_data[2]))) . ": " . $_data[0] . ".<br>";
                        //echo $_account->email . "<br>";
                        if ($_shipboard) {
                            $this->update_information($_shipboard, $_data);
                            echo "Shipboard Information : Update <br>";
                        } else {
                            $this->create_information($_account->id, $_data);
                            echo "Shipboard Infomation : Store<br>";
                        }
                    } else {
                        echo "Not Found <br>";
                        echo  ucwords(mb_strtolower(trim($_data[1] . ', ' . $_data[2]))) . ": " . $_data[0] . ".<br>";
                    }
                    echo "<br>";
                }
            }
        }
    }
    public function update_information($_shipboard, $_data)
    {
        $_shipboard->company_name = $_data[4];
        $_shipboard->vessel_name = $_data[5];
        $_shipboard->vessel_type = $_data[6];
        $_shipboard->shipping_company = $_data[7];
        $_shipboard->shipboard_status = $_data[8];
        $_shipboard->sbt_batch = $_data[9];
        $_shipboard->embarked = $_data[10];
        $_shipboard->disembarked = $_data[11];
        $_shipboard->save();
    }
    public function create_information($_data_id, $_data)
    {
        $_shipboard  = new ShipBoardInformation();
        $_shipboard_details = array(
            'student_id' => $_data_id,
            'company_name' => $_data[4],
            'vessel_name' => $_data[5],
            'vessel_type' => $_data[6],
            'shipping_company' => $_data[7],
            'shipboard_status' => $_data[8],
            'sbt_batch' => $_data[9],
            'embarked' => $_data[10],
            'disembarked' => $_data[11]
        );
        $_shipboard->create($_shipboard_details);
    }
}
