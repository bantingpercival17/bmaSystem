<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;

class ModalComponent extends Component
{
    public $showModal = true;

    protected $listeners = ['openModal' => 'openModal'];

    public function openModal()
    {
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
    }
    public function render()
    {
        return view('livewire.components.modal-component');
    }
}
