<?php

namespace App\Http\Livewire\Accounting;

use App\Models\CourseOffer;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FeesView extends Component
{
    public $academic;
    public function render()
    {
        $_academic = Auth::user()->staff->current_academic();
        $this->academic =  request()->query('_academic') ?: $this->academic;
        $academic = base64_decode($this->academic) ?: $_academic->id;
        $courses = CourseOffer::where('is_removed', false)->get();
        return view('livewire.accounting.fees-view', compact('courses'));
    }
}
