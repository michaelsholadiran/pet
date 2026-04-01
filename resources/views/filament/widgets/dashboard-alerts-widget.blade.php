<x-filament-widgets::widget class="fi-dashboard-alerts-widget">
    <x-filament::section heading="Alerts">
        <div class="space-y-3">
            @foreach($this->getAlerts() as $alert)
                <div
                    @class([
                        'flex items-center gap-3 rounded-lg p-3',
                        'bg-warning-50 dark:bg-warning-500/10 text-warning-700 dark:text-warning-400' => $alert['type'] === 'warning',
                        'bg-info-50 dark:bg-info-500/10 text-info-700 dark:text-info-400' => $alert['type'] === 'info',
                        'bg-danger-50 dark:bg-danger-500/10 text-danger-700 dark:text-danger-400' => $alert['type'] === 'danger',
                        'bg-success-50 dark:bg-success-500/10 text-success-700 dark:text-success-400' => $alert['type'] === 'success',
                    ])
                >
                    <x-filament::icon :icon="$alert['icon']" class="h-5 w-5 shrink-0" />
                    <span class="flex-1">{{ $alert['message'] }}</span>
                    @if($alert['url'])
                        <x-filament::link
                            :href="$alert['url']"
                            tag="a"
                            size="sm"
                        >
                            View
                        </x-filament::link>
                    @endif
                </div>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
