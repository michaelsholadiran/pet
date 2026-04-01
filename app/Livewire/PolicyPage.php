<?php

namespace App\Livewire;

use App\Models\Policy;
use Livewire\Attributes\Layout;
use Livewire\Component;

class PolicyPage extends Component
{
    private const SLUG_MAP = [
        'privacy-policy' => Policy::TYPE_PRIVACY,
        'return-policy' => Policy::TYPE_RETURN,
        'shipping-policy' => Policy::TYPE_SHIPPING,
    ];

    private const PAGE_SUBHEADERS = [
        'privacy-policy' => 'Please read this policy carefully before using our website.',
        'return-policy' => 'Please review this policy before requesting a return.',
        'shipping-policy' => 'Please review this policy to understand our shipping process.',
    ];

    public string $slug = '';

    public function mount(): void
    {
        $slug = request()->path();
        if (! array_key_exists($slug, self::SLUG_MAP)) {
            abort(404);
        }
        $this->slug = $slug;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        $type = self::SLUG_MAP[$this->slug];
        $policy = Policy::findByType($type);

        if (! $policy) {
            abort(404, 'Policy not found');
        }

        view()->share([
            'page_title' => $policy->title.' - '.config('puppiary.name'),
            'page_canonical' => '/'.$this->slug,
        ]);

        return view('livewire.policy-page', [
            'policy' => $policy,
            'pageHeader' => $policy->title,
            'pageSubheader' => self::PAGE_SUBHEADERS[$this->slug] ?? 'Please read this policy carefully before using our website.',
            'lastUpdated' => optional($policy->updated_at)->format('F j, Y') ?? now()->format('F j, Y'),
        ]);
    }
}
