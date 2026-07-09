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
use App\Http\Controllers\Frontend\FlashSaleController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductTypeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\NewsTickerController;
use App\Http\Controllers\Admin\CareerController;
use App\Http\Controllers\Admin\ContactController;
use App\Http\Controllers\Admin\QuoteAdminController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\MarketingController;
use App\Http\Controllers\Admin\PostController;
use App\Http\Controllers\Admin\ShowroomController;
use App\Http\Controllers\Admin\ManufacturingProductController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\LandingPageController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\SubdistrictController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\LocationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// ข้อมูลจังหวัด/อำเภอ/ตำบล (สำหรับ dropdown แบบ cascade)
Route::get('/locations/provinces', [LocationController::class, 'provinces'])->name('locations.provinces');
Route::get('/locations/provinces/{province}/districts', [LocationController::class, 'districts'])->name('locations.districts');
Route::get('/locations/districts/{district}/subdistricts', [LocationController::class, 'subdistricts'])->name('locations.subdistricts');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/services', [PageController::class, 'services'])->name('services');
Route::get('/order', [PageController::class, 'order'])->name('order');
Route::get('/order/products/{manufacturingProduct}', [PageController::class, 'manufacturingProductShow'])->name('manufacturing-products.show');
Route::get('/embroidery-screen', [PageController::class, 'embroideryScreen'])->name('embroidery-screen');
Route::get('/school-uniforms', [PageController::class, 'schoolUniforms'])->name('school-uniforms');
Route::get('/showrooms', [PageController::class, 'showrooms'])->name('showrooms');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/privacy-policy', [PageController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/cookie-policy', [PageController::class, 'cookiePolicy'])->name('cookie-policy');
Route::get('/size-guide', [PageController::class, 'sizeGuide'])->name('size-guide');
Route::get('/faq', [PageController::class, 'faq'])->name('faq');
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
Route::get('/promotions', [ShopController::class, 'promotions'])->name('promotions');
Route::get('/shop/category/{slug}', [ShopController::class, 'category'])->name('shop.category');
Route::get('/shop/{slug}', [ShopController::class, 'show'])->name('shop.show');
Route::post('/shop/{product}/review', [ShopController::class, 'submitReview'])->name('shop.review')->middleware('auth:customer');

// Coupons
Route::get('/flash-sales/{flashSale}', [FlashSaleController::class, 'show'])->name('flash-sales.show');

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
    Route::post('/orders/{order}/confirm-received', [AccountController::class, 'confirmReceived'])->name('orders.confirm-received');
    Route::post('/orders/{order}/cancel', [AccountController::class, 'cancelOrder'])->name('orders.cancel');
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
    // Dashboard & Profile (ทุกคนเข้าได้)
    Route::middleware(['check.permission:dashboard_view'])->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::middleware(['check.permission:report_view'])->group(function () {
        Route::get('/reports', [ReportController::class, 'index'])->name('reports');
    });

    Route::get('/manual', fn() => view('admin.manual.index'))->name('manual');

    // Profile - ทุกคนแก้ไขได้
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Products
    Route::middleware(['check.permission:product_manage'])->group(function () {
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products', [ProductController::class, 'store'])->name('products.store');
        Route::get('products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::post('products/{product}/images', [ProductController::class, 'uploadImages'])->name('products.images.upload');
        Route::delete('products/{product}/images/{image}', [ProductController::class, 'deleteImage'])->name('products.images.delete');
    });
    Route::middleware(['check.permission:product_view'])->group(function () {
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/{product}', [ProductController::class, 'show'])->name('products.show');
    });

    // Categories
    Route::middleware(['check.permission:category_view'])->group(function () {
        Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    });
    Route::middleware(['check.permission:category_manage'])->group(function () {
        Route::get('categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });

    // Product Types
    Route::middleware(['check.permission:product_type_view'])->group(function () {
        Route::get('product-types', [ProductTypeController::class, 'index'])->name('product-types.index');
    });
    Route::middleware(['check.permission:product_type_manage'])->group(function () {
        Route::get('product-types/create', [ProductTypeController::class, 'create'])->name('product-types.create');
        Route::post('product-types', [ProductTypeController::class, 'store'])->name('product-types.store');
        Route::get('product-types/{productType}/edit', [ProductTypeController::class, 'edit'])->name('product-types.edit');
        Route::put('product-types/{productType}', [ProductTypeController::class, 'update'])->name('product-types.update');
        Route::delete('product-types/{productType}', [ProductTypeController::class, 'destroy'])->name('product-types.destroy');
    });

    // Orders
    Route::middleware(['check.permission:order_view'])->group(function () {
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });
    Route::middleware(['check.permission:order_manage'])->group(function () {
        Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
        Route::post('orders/{order}/verify-payment', [OrderController::class, 'verifyPayment'])->name('orders.verify-payment');
        Route::post('orders/{order}/reject-payment', [OrderController::class, 'rejectPayment'])->name('orders.reject-payment');
        Route::post('orders/{order}/tracking', [OrderController::class, 'updateTracking'])->name('orders.tracking');
    });

    // Customers
    Route::middleware(['check.permission:customer_view'])->group(function () {
        Route::get('customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    });
    Route::middleware(['check.permission:customer_manage'])->group(function () {
        Route::get('customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::delete('customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
        Route::post('customers/{customer}/addresses', [CustomerController::class, 'storeAddress'])->name('customers.addresses.store');
        Route::put('customers/{customer}/addresses/{address}', [CustomerController::class, 'updateAddress'])->name('customers.addresses.update');
        Route::delete('customers/{customer}/addresses/{address}', [CustomerController::class, 'destroyAddress'])->name('customers.addresses.destroy');
    });

    // Users
    Route::middleware(['check.permission:user_manage'])->group(function () {
        Route::get('users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('users', [UserController::class, 'store'])->name('users.store');
        Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });
    Route::middleware(['check.permission:user_view'])->group(function () {
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [UserController::class, 'show'])->name('users.show');
    });

    // Roles & Permissions
    Route::middleware(['check.permission:role_manage'])->group(function () {
        Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create');
        Route::post('roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit');
        Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });
    Route::middleware(['check.permission:role_view'])->group(function () {
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index');
    });

    // Banners
    Route::middleware(['check.permission:banner_view'])->group(function () {
        Route::get('banners', [BannerController::class, 'index'])->name('banners.index');
    });
    Route::middleware(['check.permission:banner_manage'])->group(function () {
        Route::get('banners/create', [BannerController::class, 'create'])->name('banners.create');
        Route::post('banners', [BannerController::class, 'store'])->name('banners.store');
        Route::get('banners/{banner}/edit', [BannerController::class, 'edit'])->name('banners.edit');
        Route::put('banners/{banner}', [BannerController::class, 'update'])->name('banners.update');
        Route::delete('banners/{banner}', [BannerController::class, 'destroy'])->name('banners.destroy');
    });

    // News Tickers
    Route::middleware(['check.permission:news_ticker_view'])->group(function () {
        Route::get('news-tickers', [NewsTickerController::class, 'index'])->name('news-tickers.index');
    });
    Route::middleware(['check.permission:news_ticker_manage'])->group(function () {
        Route::get('news-tickers/create', [NewsTickerController::class, 'create'])->name('news-tickers.create');
        Route::post('news-tickers', [NewsTickerController::class, 'store'])->name('news-tickers.store');
        Route::get('news-tickers/{newsTicker}/edit', [NewsTickerController::class, 'edit'])->name('news-tickers.edit');
        Route::put('news-tickers/{newsTicker}', [NewsTickerController::class, 'update'])->name('news-tickers.update');
        Route::delete('news-tickers/{newsTicker}', [NewsTickerController::class, 'destroy'])->name('news-tickers.destroy');
    });

    // Posts
    Route::middleware(['check.permission:post_view'])->group(function () {
        Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    });
    Route::middleware(['check.permission:post_manage'])->group(function () {
        Route::get('posts/create', [PostController::class, 'create'])->name('posts.create');
        Route::post('posts', [PostController::class, 'store'])->name('posts.store');
        Route::get('posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
        Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
        Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    });

    // Videos
    Route::middleware(['check.permission:video_view'])->group(function () {
        Route::get('videos', [VideoController::class, 'index'])->name('videos.index');
    });
    Route::middleware(['check.permission:video_manage'])->group(function () {
        Route::get('videos/create', [VideoController::class, 'create'])->name('videos.create');
        Route::post('videos', [VideoController::class, 'store'])->name('videos.store');
        Route::get('videos/{video}/edit', [VideoController::class, 'edit'])->name('videos.edit');
        Route::put('videos/{video}', [VideoController::class, 'update'])->name('videos.update');
        Route::delete('videos/{video}', [VideoController::class, 'destroy'])->name('videos.destroy');
    });

    // Showrooms
    Route::middleware(['check.permission:showroom_view'])->group(function () {
        Route::get('showrooms', [ShowroomController::class, 'index'])->name('showrooms.index');
    });
    Route::middleware(['check.permission:showroom_manage'])->group(function () {
        Route::get('showrooms/create', [ShowroomController::class, 'create'])->name('showrooms.create');
        Route::post('showrooms', [ShowroomController::class, 'store'])->name('showrooms.store');
        Route::get('showrooms/{showroom}/edit', [ShowroomController::class, 'edit'])->name('showrooms.edit');
        Route::put('showrooms/{showroom}', [ShowroomController::class, 'update'])->name('showrooms.update');
        Route::delete('showrooms/{showroom}', [ShowroomController::class, 'destroy'])->name('showrooms.destroy');
    });

    // Manufacturing Products (สินค้าที่รับผลิต)
    Route::middleware(['check.permission:manufacturing_product_view'])->group(function () {
        Route::get('manufacturing-products', [ManufacturingProductController::class, 'index'])->name('manufacturing-products.index');
    });
    Route::middleware(['check.permission:manufacturing_product_manage'])->group(function () {
        Route::get('manufacturing-products/create', [ManufacturingProductController::class, 'create'])->name('manufacturing-products.create');
        Route::post('manufacturing-products', [ManufacturingProductController::class, 'store'])->name('manufacturing-products.store');
        Route::get('manufacturing-products/{manufacturingProduct}/edit', [ManufacturingProductController::class, 'edit'])->name('manufacturing-products.edit');
        Route::put('manufacturing-products/{manufacturingProduct}', [ManufacturingProductController::class, 'update'])->name('manufacturing-products.update');
        Route::delete('manufacturing-products/{manufacturingProduct}', [ManufacturingProductController::class, 'destroy'])->name('manufacturing-products.destroy');
        Route::delete('manufacturing-products/{manufacturingProduct}/images/{image}', [ManufacturingProductController::class, 'deleteImage'])->name('manufacturing-products.images.delete');
    });

    // Coupons
    Route::middleware(['check.permission:coupon_view'])->group(function () {
        Route::get('coupons', [CouponController::class, 'index'])->name('coupons.index');
    });
    Route::middleware(['check.permission:coupon_manage'])->group(function () {
        Route::get('coupons/create', [CouponController::class, 'create'])->name('coupons.create');
        Route::post('coupons', [CouponController::class, 'store'])->name('coupons.store');
        Route::get('coupons/{coupon}/edit', [CouponController::class, 'edit'])->name('coupons.edit');
        Route::put('coupons/{coupon}', [CouponController::class, 'update'])->name('coupons.update');
        Route::delete('coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');
    });

    // Flash Sales / Marketing
    Route::middleware(['check.permission:flash_sale_manage'])->group(function () {
        Route::get('flash-sales/create', [MarketingController::class, 'create'])->name('flash-sales.create');
        Route::post('flash-sales', [MarketingController::class, 'store'])->name('flash-sales.store');
        Route::get('flash-sales/{flashSale}/edit', [MarketingController::class, 'edit'])->name('flash-sales.edit');
        Route::put('flash-sales/{flashSale}', [MarketingController::class, 'update'])->name('flash-sales.update');
        Route::delete('flash-sales/{flashSale}', [MarketingController::class, 'destroy'])->name('flash-sales.destroy');
    });
    Route::middleware(['check.permission:flash_sale_view'])->group(function () {
        Route::get('marketing', [MarketingController::class, 'index'])->name('marketing.index');
        Route::get('flash-sales/{flashSale}', [MarketingController::class, 'show'])->name('flash-sales.show');
    });

    // Reviews
    Route::middleware(['check.permission:review_view'])->group(function () {
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
    });
    Route::middleware(['check.permission:review_manage'])->group(function () {
        Route::put('reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
        Route::post('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::post('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    });

    // Quotes
    Route::middleware(['check.permission:quote_view'])->group(function () {
        Route::get('quotes', [QuoteAdminController::class, 'index'])->name('quotes.index');
        Route::get('quotes/{quote}', [QuoteAdminController::class, 'show'])->name('quotes.show');
    });
    Route::middleware(['check.permission:quote_manage'])->group(function () {
        Route::post('quotes/{quote}/respond', [QuoteAdminController::class, 'respond'])->name('quotes.respond');
        Route::patch('quotes/{quote}/status', [QuoteAdminController::class, 'updateStatus'])->name('quotes.status');
    });

    // Dealers
    Route::middleware(['check.permission:dealer_view'])->group(function () {
        Route::get('dealers', [SettingController::class, 'dealers'])->name('dealers.index');
    });
    Route::middleware(['check.permission:dealer_manage'])->group(function () {
        Route::patch('dealers/{dealer}/status', [SettingController::class, 'dealerStatus'])->name('dealers.status');
    });

    // Careers
    Route::middleware(['check.permission:career_manage'])->group(function () {
        Route::get('careers/create', [CareerController::class, 'create'])->name('careers.create');
        Route::post('careers', [CareerController::class, 'store'])->name('careers.store');
        Route::get('careers/{career}/edit', [CareerController::class, 'edit'])->name('careers.edit');
        Route::put('careers/{career}', [CareerController::class, 'update'])->name('careers.update');
        Route::delete('careers/{career}', [CareerController::class, 'destroy'])->name('careers.destroy');
        Route::patch('career-applications/{application}/status', [CareerController::class, 'updateApplicationStatus'])->name('career-applications.status');
    });
    Route::middleware(['check.permission:career_view'])->group(function () {
        Route::get('careers', [CareerController::class, 'index'])->name('careers.index');
        Route::get('careers/{career}/applications', [CareerController::class, 'applications'])->name('careers.applications');
        Route::get('career-applications/{application}/resume', [CareerController::class, 'downloadResume'])->name('career-applications.resume');
    });

    // Contacts
    Route::middleware(['check.permission:contact_view'])->group(function () {
        Route::get('contacts', [ContactController::class, 'index'])->name('contacts.index');
        Route::get('contacts/{message}', [ContactController::class, 'show'])->name('contacts.show');
    });
    Route::middleware(['check.permission:contact_manage'])->group(function () {
        Route::post('contacts/{message}/reply', [ContactController::class, 'reply'])->name('contacts.reply');
        Route::delete('contacts/{message}', [ContactController::class, 'destroy'])->name('contacts.destroy');
    });

    // Locations (Provinces, Districts, Subdistricts)
    Route::middleware(['check.permission:location_view'])->group(function () {
        Route::get('provinces', [ProvinceController::class, 'index'])->name('provinces.index');
        Route::get('provinces/{province}/districts', [DistrictController::class, 'index'])->name('provinces.districts.index');
        Route::get('districts/{district}/subdistricts', [SubdistrictController::class, 'index'])->name('districts.subdistricts.index');
    });
    Route::middleware(['check.permission:location_manage'])->group(function () {
        Route::get('provinces/create', [ProvinceController::class, 'create'])->name('provinces.create');
        Route::post('provinces', [ProvinceController::class, 'store'])->name('provinces.store');
        Route::get('provinces/{province}/edit', [ProvinceController::class, 'edit'])->name('provinces.edit');
        Route::put('provinces/{province}', [ProvinceController::class, 'update'])->name('provinces.update');
        Route::delete('provinces/{province}', [ProvinceController::class, 'destroy'])->name('provinces.destroy');
        Route::get('provinces/{province}/districts/create', [DistrictController::class, 'create'])->name('provinces.districts.create');
        Route::post('provinces/{province}/districts', [DistrictController::class, 'store'])->name('provinces.districts.store');
        Route::get('districts/{district}/edit', [DistrictController::class, 'edit'])->name('districts.edit');
        Route::put('districts/{district}', [DistrictController::class, 'update'])->name('districts.update');
        Route::get('districts/{district}/subdistricts/create', [SubdistrictController::class, 'create'])->name('districts.subdistricts.create');
        Route::post('districts/{district}/subdistricts', [SubdistrictController::class, 'store'])->name('districts.subdistricts.store');
        Route::get('subdistricts/{subdistrict}/edit', [SubdistrictController::class, 'edit'])->name('subdistricts.edit');
        Route::put('subdistricts/{subdistrict}', [SubdistrictController::class, 'update'])->name('subdistricts.update');
    });

    // Settings
    Route::middleware(['check.permission:setting_view'])->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('settings/shipping', [SettingController::class, 'shipping'])->name('settings.shipping');
        Route::get('settings/shipping-rates', [SettingController::class, 'shippingRates'])->name('settings.shipping-rates');
        Route::get('settings/shipping-providers', [SettingController::class, 'shippingProviders'])->name('settings.shipping-providers');
        Route::get('settings/logs', [SettingController::class, 'logs'])->name('settings.logs');
        Route::get('settings/consent-logs', [SettingController::class, 'consentLogs'])->name('settings.consent-logs');
    });
    Route::middleware(['check.permission:setting_manage'])->group(function () {
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
        Route::post('settings/shipping', [SettingController::class, 'updateShipping'])->name('settings.shipping.update');
        Route::post('settings/shipping-rates', [SettingController::class, 'updateShippingRates'])->name('settings.shipping-rates.update');
        Route::post('settings/shipping-providers', [SettingController::class, 'updateShippingProviders'])->name('settings.shipping-providers.update');
    });

    // Landing Pages
    Route::middleware(['check.permission:landing_page_view'])->group(function () {
        Route::get('landing-pages', [LandingPageController::class, 'index'])->name('landing-pages.index');
    });
    Route::middleware(['check.permission:landing_page_manage'])->group(function () {
        Route::post('landing-pages', [LandingPageController::class, 'store'])->name('landing-pages.store');
        Route::post('landing-pages/{landingPage}/toggle', [LandingPageController::class, 'toggle'])->name('landing-pages.toggle');
        Route::delete('landing-pages/{landingPage}', [LandingPageController::class, 'destroy'])->name('landing-pages.destroy');
    });
});

Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
