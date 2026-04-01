<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class AuthLogin extends Component
{
    #[Layout('layouts.app')]
    public function mount(): void
    {
        view()->share([
            'page_title' => 'Sign in - '.config('puppiary.name'),
            'page_canonical' => '/sign-in',
        ]);
    }

    public function render()
    {
        return view('livewire.auth-login');
    }
}
