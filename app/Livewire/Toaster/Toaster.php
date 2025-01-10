<?php

namespace App\Livewire\Toaster;

use Livewire\Attributes\On;
use Livewire\Component;

class Toaster extends Component
{
    public $message = '';
    public $type = ''; // Default type
    public $show = false;


    protected $listeners = ['showSuccessToaster','showErrorToaster'];

    #[On('showSuccessToaster')]
    public function showSuccessToaster($message, $type = 'success')
    {

        $this->message = $message;
        $this->type = $type;
        $this->show = true;

        $this->resetShowAfterDelay();
    }

    #[On('showErrorToaster')]
    public function showErrorToaster($message, $type = 'error')
    {

        $this->message = $message;
        $this->type = $type;
        $this->show = true;

        $this->resetShowAfterDelay();
    }

    private function resetShowAfterDelay()
    {
        
        $this->dispatch('hide-toaster');
        
    }


    public function render()
    {
        return view('livewire.toaster.toaster');
    }
}
