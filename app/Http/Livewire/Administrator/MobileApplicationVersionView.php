<?php

namespace App\Http\Livewire\Administrator;

use App\Models\ThirdDatabase\MobileApplicationDetails;
use Livewire\Component;

class MobileApplicationVersionView extends Component
{
    public $application = [];
    public function render()
    {
        $this->application = request()->query('app') ? MobileApplicationDetails::find(base64_decode(request()->query('app'))) : $this->application;
        return view('livewire.administrator.mobile-application-version-view');
    }
}
