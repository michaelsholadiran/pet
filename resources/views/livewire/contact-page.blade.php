<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12 sm:py-16">
    <header class="text-center mb-12">
        <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 mb-4">Get in Touch</h1>
        <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
            We'd love to hear from you.
            Whether you have a question about your puppy, need help choosing products, or just want to share how your puppy is doing — we're here to help.
        </p>
    </header>

    @if (session('contact_sent'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-6 py-5 text-green-900 mb-10 text-center" role="status">
            <p class="font-semibold">Thank you — your message is on its way.</p>
            <p class="text-sm mt-1 text-green-800">We read every note personally and usually reply within 24 hours.</p>
        </div>
    @endif

    <div class="space-y-10">
        <section class="rounded-2xl border border-gray-200 bg-white p-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-2">Email Us Directly</h2>
            <a href="mailto:hello@puppiary.com" class="text-lg font-semibold text-primary hover:text-primary-dark no-underline">
                hello@puppiary.com
            </a>
            <p class="text-gray-600 mt-3 text-sm sm:text-base">
                We read every email personally and usually reply within 24 hours.
            </p>
        </section>

        <section class="rounded-2xl border border-gray-200 bg-white p-8">
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-1">Send Us a Message</h2>
                <p class="text-gray-600 text-sm sm:text-base">Prefer to write? Use the simple form below:</p>
            </div>

            <form wire:submit="submit" class="space-y-5">
                <div>
                    <label for="contact-name" class="block text-sm font-medium text-gray-700 mb-1">Your Name</label>
                    <input
                        id="contact-name"
                        type="text"
                        wire:model="name"
                        autocomplete="name"
                        class="w-full rounded-full border-0 px-4 py-3 text-gray-900 placeholder:text-gray-500 focus:outline-none {{ $errors->has('name') ? 'bg-red-50 ring-2 ring-red-500' : 'bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-primary/25' }}"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="contact-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                    <input
                        id="contact-email"
                        type="email"
                        wire:model="email"
                        autocomplete="email"
                        class="w-full rounded-full border-0 px-4 py-3 text-gray-900 placeholder:text-gray-500 focus:outline-none {{ $errors->has('email') ? 'bg-red-50 ring-2 ring-red-500' : 'bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-primary/25' }}"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="contact-message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea
                        id="contact-message"
                        wire:model="message"
                        rows="6"
                        class="w-full rounded-3xl border-0 px-4 py-3 text-gray-900 placeholder:text-gray-500 focus:outline-none resize-y {{ $errors->has('message') ? 'bg-red-50 ring-2 ring-red-500' : 'bg-gray-100 focus:bg-gray-100 focus:ring-2 focus:ring-primary/25' }}"
                    ></textarea>
                    @error('message')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <button
                    type="submit"
                    wire:loading.attr="disabled"
                    class="w-full sm:w-auto min-w-[200px] inline-flex items-center justify-center px-8 py-4 rounded-full bg-primary text-white font-semibold text-lg hover:bg-primary-dark transition disabled:opacity-60"
                >
                    <span wire:loading.remove wire:target="submit">Send Message</span>
                    <span wire:loading wire:target="submit">Sending…</span>
                </button>
            </form>
        </section>
    </div>

    <footer class="mt-14 pt-10 border-t border-gray-200 text-center">
        <p class="text-base text-gray-800">Thank you for choosing Puppiary</p>
    </footer>
</div>
