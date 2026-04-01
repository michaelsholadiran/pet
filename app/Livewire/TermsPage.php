<?php

namespace App\Livewire;

use Livewire\Attributes\Layout;
use Livewire\Component;

class TermsPage extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        view()->share([
            'page_title' => 'Terms & Conditions - '.config('puppiary.name'),
            'page_canonical' => '/terms',
        ]);

        return view('livewire.terms-page', [
            'pageTitle' => 'Terms & Conditions',
            'pageHeader' => 'Terms & Conditions',
            'pageSubheader' => 'Please read these terms carefully before using our website.',
            'lastUpdated' => 'March 27, 2026',
        ]);
    }
}
