<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8 lg:p-10">
        <p class="text-sm font-semibold tracking-wide text-primary uppercase mb-2">{{ $pageHeader }}</p>
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-3">{{ $pageHeader }}</h1>
        <p class="text-gray-600 mb-3">{{ $pageSubheader }}</p>
        <p class="text-sm text-gray-500 mb-8">Last Updated: {{ $lastUpdated }}</p>

        <div class="space-y-6 text-gray-700 leading-7 [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:text-gray-900 [&_h2]:mb-3 [&_section]:space-y-2 [&_a]:text-primary [&_a]:no-underline hover:[&_a]:underline">
            {!! $policy->content !!}
        </div>
    </div>
</div>
