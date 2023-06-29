<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class TopNavigationScanner extends Component
{
    public $time;
    public $dateTime;
    public function mount()
    {
        $this->time = now()->format('H:i:s');
        $this->dateTime = now()->format('F d,Y H:i:s');
    }
    public function render()
    {
        return view('livewire.components.top-navigation-scanner');
    }
    public function updateTime()
    {
        $this->time = now()->format('H:i:s');
        $this->dateTime = now()->format('F d,Y H:i:s');
        $this->dispatchBrowserEvent('swal:alert', [
            'title' => 'Complete!',
            'text' => $this->dateTime,
            'type' => 'success',
        ]);
    }

    public function hydrate()
    {
        $this->time = now()->format('H:i:s');
        $this->dateTime = now()->format('F d,Y H:i:s');
        
    }
    public function startClock()
    {
        $this->dispatchBrowserEvent('clockTick');
    }
}
