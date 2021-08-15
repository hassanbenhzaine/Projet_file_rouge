<?php

namespace App\Http\Livewire;

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class Navigation extends Component
{
    protected $listeners = [
        '$refresh'
    ];
    
    public $showProfile = false;
    public $name;
    public $email;
    public $phone;
    public $password = '********';
    public $password_confirmation = '********';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => 'required|confirmed',
    ];

    public function render()
    {
        return view('livewire.navigation');
    }

    public function editProfile(){

        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
        $this->phone = Auth::user()->phone;
        $this->showProfile = true;
    }

    public function saveProfile(){
        $this->validate();

        $userController = new UserController;
        // $this->password_confirmation
        $userController->edit(Auth::id(), $this->name, $this->email, $this->phone, $this->password);

        $this->showProfile = false;
    }

    // public function deleteFromCart($rowId){
    //     Cart::remove($rowId);

    //     switch (Route::currentRouteName()) {
    //       case 'search':
    //         $component = 'search';
    //         break;
          
    //       case 'home':
    //         $component = 'featured-products';
    //         break;
    //     }


    //     $this->emitTo($component, '$refresh');
    // }

    
}
