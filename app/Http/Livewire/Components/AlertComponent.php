<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class AlertComponent extends Component
{
    public function render()
    {
        return view('livewire.components.alert-component');
    }

    public function showAlert()
    {
        $this->emit('showAlert', 'This is a success message!', 'success');
    }
}
