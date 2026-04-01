@props(['href', 'active' => false])

<a
    href="{{ $href }}"
    {{ $attributes->class([
        'block px-3 py-2 rounded-full text-sm font-medium transition',
        'bg-primary/10 text-primary' => $active,
        'text-gray-700 hover:bg-gray-50 hover:text-primary' => ! $active,
    ]) }}
>
    {{ $slot }}
</a>
