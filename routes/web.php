<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductReviewController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController as ShopProductController;
use App\Http\Controllers\ProfileUserController;

use App\Http\Middleware\CheckRole;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

use App\Models\User;

/*
|--------------------------------------------------------------------------
| WEB ROUTES
|--------------------------------------------------------------------------
*/

// HOME
Route::get('/', HomeController::class)->name('home');

// CUSTOMER PRODUCT DISCOVERY
Route::get('/san-pham', [ShopProductController::class, 'index'])
    ->name('products.index');

Route::get('/san-pham/{product:slug}', [ShopProductController::class, 'show'])
    ->name('products.show');

Route::post('/san-pham/{product:slug}/danh-gia', [ShopProductController::class, 'storeReview'])
    ->name('products.reviews.store');

// CART
Route::get('/gio-hang', [CartController::class, 'index'])
    ->name('cart.index');

Route::post('/gio-hang/{product}', [CartController::class, 'add'])
    ->name('cart.add');

Route::post('/mua-ngay/{product}', [CartController::class, 'buyNow'])
    ->name('cart.buy-now');

Route::patch('/gio-hang/{product}', [CartController::class, 'update'])
    ->name('cart.update');

Route::delete('/gio-hang/{product}', [CartController::class, 'remove'])
    ->name('cart.remove');

// CHECKOUT
Route::get('/dat-hang', [CheckoutController::class, 'index'])
    ->name('checkout.index');

Route::post('/dat-hang', [CheckoutController::class, 'store'])
    ->name('checkout.store');

Route::get('/dat-hang/thanh-cong/{order}', [CheckoutController::class, 'success'])
    ->name('checkout.success');

// DEMO PAYMENT
Route::get('/thanh-toan-demo/{order}', [PaymentController::class, 'show'])
    ->name('payment.demo');

Route::post('/thanh-toan-demo/{order}', [PaymentController::class, 'confirm'])
    ->name('payment.confirm');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function (): void {

    // LOGIN
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit');

    // REGISTER
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.submit');

    // FORGOT PASSWORD
    Route::get('/forgetpassword', [AuthController::class, 'showForgetPassword'])
        ->name('password.request');

    Route::post('/forgetpassword', [AuthController::class, 'forgetPassword'])
        ->name('password.update.fake');
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function (): void {

Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

});

/*
|--------------------------------------------------------------------------
| PROFILE USER
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // profile
    Route::get('/profile',
        [ProfileUserController::class, 'index']
    )->name('profile');

    Route::post('/profile/update',
        [ProfileUserController::class, 'update']
    )->name('profile.update');

    // change password page
    Route::get('/changepassword',
        [ProfileUserController::class, 'showChangePassword']
    )->name('change.password');

    // update password
    Route::post('/changepassword',
        [ProfileUserController::class, 'changePassword']
    )->name('password.update');
	
	// delete account
	Route::delete('/deleteaccount',
    [ProfileUserController::class, 'deleteAccount']
)->name('profile.delete');

});

/*
|--------------------------------------------------------------------------
| PROFILE ADMIN
|--------------------------------------------------------------------------
*/

Route::get('/profile-admin', function () {
    return view('admin.profile-admin');
})->middleware('auth')->name('profile.admin');


/*
|--------------------------------------------------------------------------
>>>>>>> VAN_TRONG/Ho_so_nguoi_dung
| ICON USER
|--------------------------------------------------------------------------
*/
Route::get('/profile-user', function () {
    return view('user.profile-user');
})->name('profile.user');

Route::get('/settings', function () {

    return view('settings.setting');

})->name('settings');



/*
|--------------------------------------------------------------------------
| DASHBOARD USER
|--------------------------------------------------------------------------
*/

Route::get('/home', function () {
    return view('admin.dashboard');
})->middleware('auth')->name('user.home');

/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', CheckRole::class . ':5'])
    ->group(function (): void {

        // DASHBOARD
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // BASIC STATISTICS
        Route::get('/statistics', [AdminController::class, 'statistics'])
            ->name('statistics');

        // SUPPLIERS
        Route::get('/suppliers', [SupplierController::class, 'index'])
            ->name('suppliers.index');

        Route::post('/suppliers', [SupplierController::class, 'store'])
            ->name('suppliers.store');

        Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])
            ->name('suppliers.destroy');

        Route::put('/suppliers/{id}', [SupplierController::class, 'update'])
            ->name('suppliers.update');

        // CATEGORIES
        Route::resource('categories', CategoryController::class)
            ->except(['show', 'create', 'edit']);

        // PRODUCTS
        Route::resource('products', ProductController::class)
            ->except(['show', 'create', 'edit']);

        // PROMOTIONS
        Route::resource('promotions', PromotionController::class)
            ->except(['show', 'create', 'edit']);

        // REVIEWS
        Route::resource('reviews', ProductReviewController::class)
            ->only(['index', 'update', 'destroy']);

        // ORDERS
        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{order}', [OrderController::class, 'update'])
            ->name('orders.update');

        // USERS
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::patch('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        // PERMISSIONS
        Route::get('/permissions', [AdminController::class, 'permissions'])
            ->name('permissions');

        Route::post('/permissions', [AdminController::class, 'updatePermissions'])
            ->name('permissions.update');

        // LOGS
        Route::get('/logs', [AdminController::class, 'logs'])
            ->name('logs');

        // WAREHOUSE (Nhập Xuất Kho)
        Route::prefix('warehouse')->name('warehouse.')->group(function () {
            Route::get('/receipts', [\App\Http\Controllers\Admin\WarehouseController::class, 'receipts'])->name('receipts');
            Route::get('/receipts/create', [\App\Http\Controllers\Admin\WarehouseController::class, 'createReceipt'])->name('receipts.create');
            Route::post('/receipts', [\App\Http\Controllers\Admin\WarehouseController::class, 'storeReceipt'])->name('receipts.store');
            Route::get('/receipts/{id}', [\App\Http\Controllers\Admin\WarehouseController::class, 'showReceipt'])->name('receipts.show');
            
            Route::get('/inventory', [\App\Http\Controllers\Admin\WarehouseController::class, 'inventory'])->name('inventory');
            Route::get('/inventory/{id}/history', [\App\Http\Controllers\Admin\WarehouseController::class, 'stockHistory'])->name('inventory.history');
            
            Route::get('/issues', [\App\Http\Controllers\Admin\WarehouseController::class, 'issues'])->name('issues');
            Route::get('/issues/create', [\App\Http\Controllers\Admin\WarehouseController::class, 'createIssue'])->name('issues.create');
            Route::post('/issues', [\App\Http\Controllers\Admin\WarehouseController::class, 'storeIssue'])->name('issues.store');
            Route::get('/issues/{id}', [\App\Http\Controllers\Admin\WarehouseController::class, 'showIssue'])->name('issues.show');
            
            Route::get('/checks', [\App\Http\Controllers\Admin\WarehouseController::class, 'checks'])->name('checks');
            Route::get('/checks/create', [\App\Http\Controllers\Admin\WarehouseController::class, 'createCheck'])->name('checks.create');
            Route::post('/checks', [\App\Http\Controllers\Admin\WarehouseController::class, 'storeCheck'])->name('checks.store');
            Route::get('/checks/{id}', [\App\Http\Controllers\Admin\WarehouseController::class, 'showCheck'])->name('checks.show');
        });

        // CONTACTS (Hỗ trợ - Task 46)
        Route::prefix('contacts')->name('contacts.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('index');
            Route::get('/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('show');
            Route::post('/{id}/reply', [\App\Http\Controllers\Admin\ContactController::class, 'reply'])->name('reply');
        });

        // FAQS (Trung tâm trợ giúp - Task 47)
        Route::prefix('faqs')->name('faqs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\FaqController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\FaqController::class, 'store'])->name('store');
            Route::post('/{id}/toggle', [\App\Http\Controllers\Admin\FaqController::class, 'toggle'])->name('toggle');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('destroy');
        });

        // BANNERS (Quản lý nội dung - Task 48)
        Route::prefix('banners')->name('banners.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\BannerController::class, 'store'])->name('store');
            Route::post('/{id}/toggle', [\App\Http\Controllers\Admin\BannerController::class, 'toggle'])->name('toggle');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('destroy');
        });
    });
