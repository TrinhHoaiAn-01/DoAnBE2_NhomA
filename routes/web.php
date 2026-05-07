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
    });
