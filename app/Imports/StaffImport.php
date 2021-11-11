<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class StaffImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $key => $_data) {
            if ($key > 0 && $_data[0]) {
                $_rank = mb_strtolower($_data[1]);
                $_last_name = mb_strtolower($_data[0]);
                $_first_name = mb_strtolower($_data[2]);
                $_email = $_first_name[0] . '.' . str_replace('jr', '', str_replace(' ', '', $_last_name)) . "@bma.edu.ph";
                $_role = Role::where('name', $_data[5])->first();
                $_user_store = array(
                    'name' => ucwords($_first_name . " " . $_last_name),
                    'email' => $_email,
                    'password' => Hash::make('bmafaculty'),
                    'password-' => str_replace(' ', '', 'bmafaculty')
                );

                $_user = User::where('email', $_email)->first();
                echo $_first_name . " " . $_last_name . " | ";
                if (!$_user) {
                    $_user = User::create($_user_store);
                    if ($_role) {
                        echo $_data[5] . " | ";
                        // Create Role 
                        $_user->attachRole($_role->id);
                        // Create Staff
                        $_staff = array(
                            'user_id' => $_user->id,
                            'staff_no' => date('hms'),
                            'first_name' => ucwords($_rank . " " . $_first_name),
                            'last_name' => ucwords($_last_name),
                            'middle_name' => '',
                            'job_description' => $_data[4],
                            'department' => $_data[3],
                            'created_by' => Auth::user() ? Auth::user()->name  : 'System Setup'
                        );
                        Staff::create($_staff);
                        echo "Saved <br>";
                    } else {
                        echo "Missing Section <br>";
                    }
                } else {
                    Staff::where('user_id', $_user->id)->update(['staff_no' => date('hms')]);
                }
            }
        }
    }
}
