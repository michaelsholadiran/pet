<x-filament-widgets::widget class="fi-quick-links-widget">
    <x-filament::section heading="Quick Links">
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
            @foreach($this->getLinks() as $link)
                <a
                    href="{{ $link['url'] }}"
                    class="fi-quick-link flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition"
                >
                    <x-filament::icon
                        :icon="$link['icon']"
                        class="h-5 w-5 text-gray-500 dark:text-gray-400"
                    />
                    {{ $link['label'] }}
                </a>
            @endforeach
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
