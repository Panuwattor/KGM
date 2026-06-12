<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\ShopController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\CouponController as FrontendCouponController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\QuoteController;
use App\Http\Controllers\Frontend\DealerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\QuoteAdminController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ShowroomController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\LandingPageController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/order', [PageController::class, 'order'])->name('order');
Route::get('/embroidery-screen', [PageController::class, 'embroideryScreen'])->name('embroidery-screen');
Route::get('/school-uniforms', [PageController::class, 'schoolUniforms'])->name('school-uniforms');
Route::get('/showrooms', [PageController::class, 'showrooms'])->name('showrooms');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/cookie-policy', [PageController::class, 'cookiePolicy'])->name('cookie-policy');
Route::post('/consent', [PageController::class, 'saveConsent'])->name('consent.save');

// Blog/News
Route::get('/news', [PageController::class, 'news'])->name('news');
Route::get('/news/{slug}', [PageController::class, 'newsShow'])->name('news.show');

// Videos
Route::get('/videos', [PageController::class, 'videos'])->name('videos');

// Careers
Route::get('/careers', [PageController::class, 'careers'])->name('careers');
Route::get('/careers/{career}', [PageController::class, 'careerShow'])->name('careers.show');
Route::post('/careers/{career}/apply', [PageController::class, 'careerApply'])->name('careers.apply');

// B2B Quote
Route::get('/quote', [QuoteController::class, 'index'])->name('quote');
Route::post('/quote', [QuoteController::class, 'submit'])->name('quote.submit');
Route::get('/quote/success', [QuoteController::class, 'success'])->name('quote.success');

// Dealer
Route::get('/dealer', [DealerController::class, 'index'])->name('dealer');
Route::post('/dealer', [DealerController::class, 'submit'])->name('dealer.submit');

// Shop
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/shop/{product}/review', [ShopController::class, 'submitReview'])->name('shop.review')->middleware('auth:customer');

// Coupons
Route::get('/coupons', [FrontendCouponController::class, 'index'])->name('coupons.index');
Route::post('/coupons/{coupon}/collect', [FrontendCouponController::class, 'collect'])->name('coupons.collect')->middleware('auth:customer');

// Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon');
Route::delete('/cart/coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

// Checkout
Route::middleware('auth:customer')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/orders/{order}/upload-slip', [CheckoutController::class, 'uploadSlip'])->name('orders.upload-slip');
});

// Customer Account
Route::middleware('auth:customer')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [AccountController::class, 'index'])->name('index');
    Route::get('/profile', [AccountController::class, 'profile'])->name('profile');
    Route::post('/profile', [AccountController::class, 'updateProfile'])->name('profile.update');
    Route::get('/orders', [AccountController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [AccountController::class, 'orderShow'])->name('orders.show');
    Route::post('/orders/{order}/reorder', [AccountController::class, 'reorder'])->name('orders.reorder');
    Route::get('/addresses', [AccountController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [AccountController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [AccountController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AccountController::class, 'deleteAddress'])->name('addresses.delete');
    Route::get('/wishlist', [AccountController::class, 'wishlist'])->name('wishlist');
    Route::post('/wishlist/{product}', [AccountController::class, 'toggleWishlist'])->name('wishlist.toggle');
    Route::get('/quotes', [AccountController::class, 'quotes'])->name('quotes');
    Route::get('/coupons', [AccountController::class, 'coupons'])->name('coupons');
});

// Auth (customer)
Route::middleware('guest:customer')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/login/otp', [LoginController::class, 'loginWithOtp'])->name('login.otp');
    Route::post('/otp/send-login', [LoginController::class, 'sendLoginOtp'])->name('login.otp.send');
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/otp/send', [RegisterController::class, 'sendOtp'])->name('otp.send');
});
Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth:customer');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminLoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'login']);
    Route::post('/logout',[AdminLoginController::class, 'logout'])->name('logout')->middleware('auth');
});

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/reports', [ReportController::class, 'index'])->name('reports');

    Route::resource('categories', CategoryController::class);
    Route::resource('product-types', ProductTypeController::class);
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/images', [ProductController::class, 'uploadImages'])->name('products.images.upload');
    Route::delete('products/{product}/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');

    Route::resource('orders', OrderController::class)->only(['index', 'show', 'update']);
    Route::post('orders/{order}/verify-payment', [OrderController::class, 'verifyPayment'])->name('orders.verify-payment');
    Route::post('orders/{order}/reject-payment', [OrderController::class, 'rejectPayment'])->name('orders.reject-payment');
    Route::post('orders/{order}/tracking', [OrderController::class, 'updateTracking'])->name('orders.tracking');

    Route::resource('banners', BannerController::class);
    Route::resource('users', UserController::class);
    Route::resource('customers', CustomerController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    Route::post('customers/{customer}/addresses', [CustomerController::class, 'storeAddress'])->name('customers.addresses.store');
    Route::put('customers/{customer}/addresses/{address}', [CustomerController::class, 'updateAddress'])->name('customers.addresses.update');
    Route::delete('customers/{customer}/addresses/{address}', [CustomerController::class, 'destroyAddress'])->name('customers.addresses.destroy');
    Route::resource('careers', CareerController::class);
    Route::get('careers/{career}/applications', [CareerController::class, 'applications'])->name('careers.applications');
    Route::patch('career-applications/{application}/status', [CareerController::class, 'updateApplicationStatus'])->name('career-applications.status');
    Route::get('career-applications/{application}/resume', [CareerController::class, 'downloadResume'])->name('career-applications.resume');

    Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
    Route::get('contacts/{message}', [ContactController::class, 'show'])->name('contacts.show');
    Route::post('contacts/{message}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
    Route::delete('contacts/{message}', [ContactController::class, 'destroy'])->name('contacts.destroy');

    Route::get('quotes', [QuoteAdminController::class, 'index'])->name('quotes.index');
    Route::get('quotes/{quote}', [QuoteAdminController::class, 'show'])->name('quotes.show');
    Route::post('quotes/{quote}/respond', [QuoteAdminController::class, 'respond'])->name('quotes.respond');
    Route::patch('quotes/{quote}/status', [QuoteAdminController::class, 'updateStatus'])->name('quotes.status');

    Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::resource('coupons', CouponController::class);
    Route::get('marketing', [MarketingController::class, 'index'])->name('marketing.index');
    Route::resource('flash-sales', MarketingController::class)->except(['index'])->names('flash-sales');

    Route::resource('posts', PostController::class);
    Route::resource('videos', VideoController::class);
    Route::resource('showrooms', ShowroomController::class);

    Route::get('dealers', [SettingController::class, 'dealers'])->name('dealers.index');
    Route::patch('dealers/{dealer}/status', [SettingController::class, 'dealerStatus'])->name('dealers.status');

    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    Route::get('settings/shipping', [SettingController::class, 'shipping'])->name('settings.shipping');
    Route::post('settings/shipping', [SettingController::class, 'updateShipping'])->name('settings.shipping.update');
    Route::get('settings/shipping-rates', [SettingController::class, 'shippingRates'])->name('settings.shipping-rates');
    Route::post('settings/shipping-rates', [SettingController::class, 'updateShippingRates'])->name('settings.shipping-rates.update');
    Route::get('settings/logs', [SettingController::class, 'logs'])->name('settings.logs');
    Route::get('settings/consent-logs', [SettingController::class, 'consentLogs'])->name('settings.consent-logs');

    Route::get('landing-pages', [LandingPageController::class, 'index'])->name('landing-pages.index');
    Route::post('landing-pages', [LandingPageController::class, 'store'])->name('landing-pages.store');
    Route::post('landing-pages/{landingPage}/toggle', [LandingPageController::class, 'toggle'])->name('landing-pages.toggle');
    Route::delete('landing-pages/{landingPage}', [LandingPageController::class, 'destroy'])->name('landing-pages.destroy');
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
