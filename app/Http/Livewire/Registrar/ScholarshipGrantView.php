<?php

namespace App\Http\Livewire\Registrar;

use App\Models\Voucher;
use Livewire\Component;

class ScholarshipGrantView extends Component
{
    public function render()
    {
        $scholarship = Voucher::where('is_removed', false)->get();
        return view('livewire.registrar.scholarship-grant-view', compact('scholarship'));
    }
}
