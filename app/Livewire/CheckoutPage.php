<?php

namespace App\Livewire;

use App\Data\ProductsData;
use App\Helpers\CurrencyHelper;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CheckoutPage extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        $states = CurrencyHelper::isNgn() ? ProductsData::states('NG') : ProductsData::states('US');
        $country = CurrencyHelper::isNgn() ? 'Nigeria' : 'United States';

        return view('livewire.checkout-page', [
            'states' => $states,
            'country' => $country,
        ]);
    }
}
