<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class CartPage extends Component
{
    #[Layout('layouts.app')]
    public function mount(): void
    {
        $target = url()->previous();

        if (! is_string($target) || $target === '') {
            $target = route('home');
        } else {
            $path = parse_url($target, PHP_URL_PATH) ?? '';
            if ($path !== '' && str_contains($path, '/cart')) {
                $target = route('home');
            }
        }

        session()->flash('open_cart', true);
        $this->redirect($target, navigate: false);
    }

    public function render()
    {
        return view('livewire.cart-page');
    }
}
