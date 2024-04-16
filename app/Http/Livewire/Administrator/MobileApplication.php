<?php

namespace App\Http\Livewire\Administrator;

use App\Models\ThirdDatabase\MobileApplicationDetails;
use Livewire\Component;

class MobileApplication extends Component
{
    public function render()
    {
        $mobile_application_lists = MobileApplicationDetails::where('is_removed', false)->get();
        return view('livewire.administrator.mobile-application',compact('mobile_application_lists'));
    }
}
