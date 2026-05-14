<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController as ShopProductController;

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
    Route::get('/dang-nhap', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/dang-nhap', [AuthController::class, 'login'])
        ->name('login.submit');

    // REGISTER
    Route::get('/dang-ky', [AuthController::class, 'showRegister'])
        ->name('register');

    Route::post('/dang-ky', [AuthController::class, 'register'])
        ->name('register.submit');

    // FORGOT PASSWORD
    Route::get('/quen-mat-khau', function () {
        return view('auth.forget-password');
    })->name('password.request');

    Route::post('/quen-mat-khau', function (Request $request) {

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email khong ton tai'
            ]);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()
            ->route('login')
            ->with('success', 'Doi mat khau thanh cong!');

    })->name('password.update.fake');
});


/*
|--------------------------------------------------------------------------
| LOGOUT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function (): void {

    Route::post('/dang-xuat', [AuthController::class, 'logout'])
        ->name('logout');
});


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
    ->middleware(['auth', CheckRole::class . ':1'])
    ->group(function (): void {

        // DASHBOARD
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

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