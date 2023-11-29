<?php

namespace App\Http\Livewire\Registrar;

use App\Models\StudentDetails;
use App\Models\Voucher;
use Livewire\Component;

class ScholarshipGrantView extends Component
{
    public $searchInput = null;
    public function render()
    {
        $scholarship = Voucher::where('is_removed', false)->get();
        $studentsList = StudentDetails::where('is_removed', false)->paginate(20);
        return view('livewire.registrar.scholarship-grant-view', compact('scholarship', 'studentsList'));
    }
}
