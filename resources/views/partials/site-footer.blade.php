<footer class="bg-gray-50 mt-auto py-8">
    <div class="max-w-[1200px] mx-auto px-8 text-center space-y-4">
        <p>&copy; {{ date('Y') }} {{ config('puppiary.name') }}. All rights reserved.</p>
        <nav class="flex justify-center gap-6 flex-wrap" aria-label="Footer">
            <a href="{{ route('privacy-policy') }}">Privacy Policy</a>
            <a href="{{ route('return-policy') }}">Return Policy</a>
            <a href="{{ route('shipping-policy') }}">Shipping Policy</a>
            <a href="{{ route('terms') }}">Terms & Conditions</a>
        </nav>
        <div class="flex justify-center gap-4 flex-wrap">
            <a href="https://www.instagram.com/puppiaryhq" target="_blank" rel="noopener" aria-label="Instagram">Instagram</a>
            <a href="https://www.tiktok.com/@puppiaryhq" target="_blank" rel="noopener" aria-label="TikTok">TikTok</a>
            <a href="https://twitter.com/puppiaryhq" target="_blank" rel="noopener" aria-label="Twitter">Twitter</a>
        </div>
    </div>
</footer>
