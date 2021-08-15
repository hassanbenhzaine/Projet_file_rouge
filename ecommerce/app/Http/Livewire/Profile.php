<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Profile extends Component
{
    public function render()
    {
        return view('livewire.profile');
    }

    public function showprofile(){
        echo 123;
    }
}
