@props([
    'images' => [],
    'slug' => '',
    'productName' => '',
])

@php
    $images = is_array($images) ? $images : [];
@endphp

<div
    wire:key="product-gallery-{{ $slug }}"
    {{ $attributes->class(['w-full min-w-0']) }}
    x-data="productGallery(@js($images))"
    x-effect="lightbox ? document.documentElement.classList.add('overflow-hidden') : document.documentElement.classList.remove('overflow-hidden')"
    @keydown.escape.window="if (lightbox) lightbox = false"
    @keydown.window="if (lightbox && $event.key === 'ArrowLeft') { prev(); $event.preventDefault(); } if (lightbox && $event.key === 'ArrowRight') { next(); $event.preventDefault(); }"
>
    <template x-if="len === 0">
        <div class="aspect-[4/3] rounded-xl border border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-gray-500 text-sm">
            No images yet
        </div>
    </template>

    <template x-if="len > 0">
        <div>
            <div
                class="relative aspect-[4/3] w-full rounded-xl overflow-hidden bg-gray-100 border border-gray-200 group"
                @touchstart.passive="onTouchStart($event)"
                @touchend.passive="onTouchEnd($event)"
            >
                <img
                    :src="images[selectedIndex]"
                    alt="{{ e($productName) }}"
                    class="w-full h-full object-contain cursor-zoom-in"
                    id="main-product-image"
                    width="800"
                    height="600"
                    @click="lightbox = true"
                    @keydown.enter.prevent="lightbox = true"
                    tabindex="0"
                >

                <div class="absolute bottom-3 left-3 rounded-full bg-black/55 text-white text-xs font-medium px-2.5 py-1 backdrop-blur-sm pointer-events-none" x-show="len > 1" x-text="(selectedIndex + 1) + ' / ' + len"></div>

                <button
                    type="button"
                    x-show="len > 1"
                    @click.stop="prev()"
                    class="absolute left-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 text-gray-800 border border-gray-200 flex items-center justify-center hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-primary opacity-0 group-hover:opacity-100 transition-opacity max-sm:opacity-100"
                    aria-label="Previous image"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button
                    type="button"
                    x-show="len > 1"
                    @click.stop="next()"
                    class="absolute right-2 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 text-gray-800 border border-gray-200 flex items-center justify-center hover:bg-white focus:outline-none focus-visible:ring-2 focus-visible:ring-primary opacity-0 group-hover:opacity-100 transition-opacity max-sm:opacity-100"
                    aria-label="Next image"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                </button>

                <button
                    type="button"
                    @click.stop="lightbox = true"
                    class="absolute bottom-3 right-3 inline-flex items-center gap-1.5 rounded-full bg-black/55 text-white text-xs font-medium px-3 py-1.5 backdrop-blur-sm hover:bg-black/70 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                    aria-label="Open fullscreen gallery"
                >
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                    Enlarge
                </button>
            </div>

            <div class="flex gap-2 flex-wrap mt-3" role="tablist" aria-label="Product thumbnails">
                <template x-for="(src, i) in images" :key="i">
                    <button
                        type="button"
                        role="tab"
                        :aria-selected="selectedIndex === i"
                        class="w-16 h-16 rounded-full overflow-hidden border-2 shrink-0 transition focus:outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-2"
                        :class="selectedIndex === i ? 'border-primary ring-2 ring-primary/25' : 'border-gray-200 hover:border-gray-400'"
                        @click="selectedIndex = i"
                    >
                        <img :src="src" :alt="'Thumbnail ' + (i + 1) + ' of ' + len" class="w-full h-full object-cover" width="64" height="64">
                    </button>
                </template>
            </div>
        </div>
    </template>

    <template x-teleport="body">
        <div
            x-show="lightbox && len > 0"
            x-transition.opacity.duration.200ms
            class="fixed inset-0 z-[2000] flex items-center justify-center bg-black/95 p-4 sm:p-8"
            @click.self="lightbox = false"
            role="dialog"
            aria-modal="true"
            aria-label="Fullscreen: {{ e($productName) }}"
        >
            <button
                type="button"
                @click="lightbox = false"
                class="absolute top-4 right-4 z-10 rounded-full bg-white/10 text-white p-2 hover:bg-white/20 focus:outline-none focus-visible:ring-2 focus-visible:ring-white"
                aria-label="Close gallery"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <img
                :src="images[selectedIndex]"
                alt="{{ e($productName) }}"
                class="max-h-[85vh] max-w-full object-contain select-none"
                @click.stop
            >

            <template x-if="len > 1">
                <div>
                    <button type="button" @click.stop="prev()" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/15 text-white flex items-center justify-center hover:bg-white/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-white" aria-label="Previous image">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" @click.stop="next()" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/15 text-white flex items-center justify-center hover:bg-white/25 focus:outline-none focus-visible:ring-2 focus-visible:ring-white" aria-label="Next image">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                    </button>
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/90 text-sm" x-text="(selectedIndex + 1) + ' / ' + len"></div>
                </div>
            </template>
        </div>
    </template>
</div>
