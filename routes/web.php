<?php

use App\Http\Controllers\Auth\CustomerLoginController;
use App\Http\Controllers\PayPalController;
use App\Livewire\AboutPage;
use App\Livewire\AuthLogin;
use App\Livewire\CartPage;
use App\Livewire\CheckoutPage;
use App\Livewire\ContactPage;
use App\Livewire\CustomerAccountPage;
use App\Livewire\CustomerDashboard;
use App\Livewire\CustomerOrderShowPage;
use App\Livewire\CustomerOrdersPage;
use App\Livewire\CustomerPuppiesPage;
use App\Livewire\CustomerReviewsPage;
use App\Livewire\FaqPage;
use App\Livewire\HomePage;
use App\Livewire\PolicyPage;
use App\Livewire\ProductDetailPage;
use App\Livewire\ProductsPage;
use App\Livewire\SuccessPage;
use App\Livewire\TermsPage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', HomePage::class)->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/sign-in', AuthLogin::class)->name('sign-in');
    Route::post('/sign-in/otp/send', [CustomerLoginController::class, 'sendOtp'])
        ->middleware('throttle:6,1')
        ->name('sign-in.otp.send');
    Route::post('/sign-in/otp/verify', [CustomerLoginController::class, 'verifyOtp'])
        ->middleware('throttle:20,1')
        ->name('sign-in.otp.verify');
});

Route::redirect('/login', '/sign-in', 301);

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('home');
})->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('dashboard')->name('customer.')->group(function (): void {
    Route::get('/', CustomerDashboard::class)->name('dashboard');
    Route::get('/account', CustomerAccountPage::class)->name('account');
    Route::get('/orders', CustomerOrdersPage::class)->name('orders');
    Route::get('/orders/{order}', CustomerOrderShowPage::class)->name('orders.show');
    Route::get('/puppies', CustomerPuppiesPage::class)->name('puppies');
    Route::get('/reviews', CustomerReviewsPage::class)->name('reviews');
});

Route::redirect('/start-here', '/guide/puppy', 301);
Route::get('/starter-kit', \App\Livewire\StarterKitPage::class)->name('starter-kit');
Route::get('/products/{category}', ProductsPage::class)
    ->whereIn('category', ['food', 'treats', 'essentials'])
    ->name('products.category');
Route::get('/products', ProductsPage::class)->name('products.index');
Route::get('/product/{slug}', ProductDetailPage::class)->name('products.show');
Route::get('/cart', CartPage::class)->name('cart');
Route::get('/checkout', CheckoutPage::class)->name('checkout');
Route::get('/success', SuccessPage::class)->name('success');

Route::get('/guide/puppy', \App\Livewire\PuppyGuidePage::class)->name('puppy-guide');
Route::get('/guide/{slug}', \App\Livewire\ArticleShowPage::class)->name('guide.show');

Route::get('/about', AboutPage::class)->name('about');
Route::get('/contact', ContactPage::class)->name('contact');
Route::get('/faq', FaqPage::class)->name('faq');
Route::get('/privacy-policy', PolicyPage::class)->name('privacy-policy');
Route::get('/return-policy', PolicyPage::class)->name('return-policy');
Route::get('/shipping-policy', PolicyPage::class)->name('shipping-policy');
Route::get('/terms', TermsPage::class)->name('terms');

Route::redirect('/privacy', '/privacy-policy', 301);
Route::redirect('/refund', '/return-policy', 301);
Route::redirect('/puppy-guide', '/guide/puppy', 301);

Route::match(['get', 'post'], '/api/paypal', function (\Illuminate\Http\Request $request) {
    $controller = app(PayPalController::class);
    if ($request->isMethod('get') && $request->get('action') === 'client-token') {
        return $controller->clientToken($request);
    }
    if ($request->isMethod('post')) {
        return $controller->handleApi($request);
    }

    return response()->json(['error' => 'Not found'], 404);
});
