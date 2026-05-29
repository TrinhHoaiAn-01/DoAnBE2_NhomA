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
use App\Http\Controllers\OrderHistoryController;
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
| ĐỊNH TUYẾN WEB (WEB ROUTES)
|--------------------------------------------------------------------------
|
| Tệp này chứa tất cả các định tuyến web cho ứng dụng của bạn. Các định tuyến này
| được tải bởi RouteServiceProvider và sẽ được gán cho nhóm middleware "web".
| Hãy xây dựng một hệ thống định tuyến an toàn và tường minh.
|
*/

// TRANG CHỦ
Route::get('/', HomeController::class)->name('home');

// KHÁCH HÀNG: KHÁM PHÁ SẢN PHẨM (PRODUCT DISCOVERY)
Route::get('/san-pham', [ShopProductController::class, 'index'])
    ->name('products.index'); // Danh sách sản phẩm kèm bộ lọc tìm kiếm

Route::get('/san-pham/{product:slug}', [ShopProductController::class, 'show'])
    ->name('products.show'); // Chi tiết sản phẩm theo Slug đường dẫn thân thiện

Route::post('/san-pham/{product:slug}/danh-gia', [ShopProductController::class, 'storeReview'])
    ->name('products.reviews.store'); // Gửi đánh giá cho sản phẩm

// GIỎ HÀNG (CART)
Route::get('/gio-hang', [CartController::class, 'index'])
    ->name('cart.index'); // Trang chi tiết giỏ hàng hiện tại

Route::post('/gio-hang/{product}', [CartController::class, 'add'])
    ->name('cart.add'); // Thêm sản phẩm vào giỏ hàng

Route::post('/mua-ngay/{product}', [CartController::class, 'buyNow'])
    ->name('cart.buy-now'); // Thêm vào giỏ và chuyển hướng ngay tới trang thanh toán

Route::patch('/gio-hang/{product}', [CartController::class, 'update'])
    ->name('cart.update'); // Cập nhật số lượng sản phẩm trong giỏ

Route::delete('/gio-hang/{product}', [CartController::class, 'remove'])
    ->name('cart.remove'); // Xóa sản phẩm khỏi giỏ hàng

// ĐẶT HÀNG & THANH TOÁN (CHECKOUT)
Route::get('/dat-hang', [CheckoutController::class, 'index'])
    ->name('checkout.index'); // Trang điền thông tin giao hàng và xác nhận đơn

Route::post('/dat-hang', [CheckoutController::class, 'store'])
    ->name('checkout.store'); // Xử lý lưu đơn hàng và trừ tồn kho vào DB

Route::get('/dat-hang/thanh-cong/{order}', [CheckoutController::class, 'success'])
    ->name('checkout.success'); // Trang thông báo đặt hàng thành công hoàn tất

// THANH TOÁN GIẢ LẬP (DEMO PAYMENT GATEWAY)
Route::get('/thanh-toan-demo/{order}', [PaymentController::class, 'show'])
    ->name('payment.demo'); // Trang hiển thị thông tin chuyển khoản demo

Route::post('/thanh-toan-demo/{order}', [PaymentController::class, 'confirm'])
    ->name('payment.confirm'); // Xác nhận thanh toán giả lập thành công


/*
|--------------------------------------------------------------------------
| XÁC THỰC TÀI KHOẢN (AUTH ROUTES)
|--------------------------------------------------------------------------
|
| Các định tuyến chỉ dành cho khách vãng lai chưa đăng nhập (guest middleware).
| Giúp thực hiện đăng nhập, đăng ký và lấy lại mật khẩu.
|
*/

Route::middleware('guest')->group(function (): void {

    // ĐĂNG NHẬP (LOGIN)
    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login'); // Trang giao diện đăng nhập

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit'); // Xử lý gửi thông tin đăng nhập

    // ĐĂNG KÝ (REGISTER)
    Route::get('/register', [AuthController::class, 'showRegister'])
        ->name('register'); // Trang giao diện đăng ký tài khoản mới

    Route::post('/register', [AuthController::class, 'register'])
        ->name('register.submit'); // Xử lý gửi thông tin đăng ký mới

    // QUÊN MẬT KHẨU (FORGOT PASSWORD)
    Route::get('/forgetpassword', [AuthController::class, 'showForgetPassword'])
        ->name('password.request'); // Trang nhập email để khôi phục mật khẩu

    Route::post('/forgetpassword', [AuthController::class, 'forgetPassword'])
        ->name('password.update.fake'); // Xử lý giả lập cấp lại mật khẩu
});


/*
|--------------------------------------------------------------------------
| ĐĂNG XUẤT & LỊCH SỬ ĐƠN HÀNG (LOGOUT & ORDER HISTORY)
|--------------------------------------------------------------------------
|
| Các định tuyến yêu cầu người dùng phải đăng nhập hệ thống (auth middleware).
|
*/

Route::middleware('auth')->group(function (): void {

    // ĐĂNG XUẤT HỆ THỐNG
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    // LỊCH SỬ ĐƠN ĐẶT HÀNG (ORDER HISTORY)
    Route::get('/don-hang', [OrderHistoryController::class, 'index'])
        ->name('orders.index'); // Danh sách đơn hàng đã mua

    Route::get('/don-hang/{order}', [OrderHistoryController::class, 'show'])
        ->name('orders.show'); // Chi tiết tiến trình của một đơn hàng cụ thể

    Route::patch('/don-hang/{order}/huy', [OrderHistoryController::class, 'cancel'])
        ->name('orders.cancel'); // Khách hàng yêu cầu hủy đơn hàng (khi chưa giao)

});

/*
|--------------------------------------------------------------------------
| THÔNG TIN CÁ NHÂN (USER PROFILE)
|--------------------------------------------------------------------------
|
| Quản lý cập nhật hồ sơ, đổi mật khẩu và xóa tài khoản của người dùng.
|
*/

Route::middleware('auth')->group(function () {

    // Xem thông tin cá nhân
    Route::get('/profile',
        [ProfileUserController::class, 'index']
    )->name('profile');

    // Cập nhật thông tin cá nhân (Họ tên, SĐT, Địa chỉ, Ảnh đại diện)
    Route::post('/profile/update',
        [ProfileUserController::class, 'update']
    )->name('profile.update');

    // Trang đổi mật khẩu
    Route::get('/changepassword',
        [ProfileUserController::class, 'showChangePassword']
    )->name('change.password');

    // Xử lý cập nhật mật khẩu mới
    Route::post('/changepassword',
        [ProfileUserController::class, 'changePassword']
    )->name('password.update');
	
	// Xóa tài khoản người dùng
	Route::delete('/deleteaccount',
        [ProfileUserController::class, 'deleteAccount']
    )->name('profile.delete');

});

/*
|--------------------------------------------------------------------------
| TRANG TRẠNG THÁI HỒ SƠ ADMIN (ADMIN PROFILE)
|--------------------------------------------------------------------------
*/

Route::get('/profile-admin', function () {
    return view('admin.profile-admin');
})->middleware('auth')->name('profile.admin');


/*
|--------------------------------------------------------------------------
| TRANG TIỆN ÍCH HỒ SƠ KHÁCH HÀNG (CUSTOMER PROFILE VIEW)
|--------------------------------------------------------------------------
*/
Route::get('/profile-user', function () {
    return view('user.profile-user');
})->name('profile.user');

// Cài đặt hệ thống
Route::get('/settings', function () {
    return view('settings.setting');
})->name('settings');



/*
|--------------------------------------------------------------------------
| TRANG CHỦ ĐIỀU HƯỚNG NGƯỜI DÙNG (DASHBOARD USER / HOME REDIRECT)
|--------------------------------------------------------------------------
*/

Route::get('/home', function () {
    return view('admin.dashboard');
})->middleware('auth')->name('user.home');

/*
|--------------------------------------------------------------------------
| PHÂN HỆ QUẢN TRỊ VIÊN (ADMIN ROUTES)
|--------------------------------------------------------------------------
|
| Nhóm định tuyến quản trị hệ thống, yêu cầu đăng nhập và có vai trò phù hợp
| (Middleware CheckRole:5 đại diện cho quyền Admin/Quản lý).
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', CheckRole::class . ':5'])
    ->group(function (): void {

        // BẢNG ĐIỀU KHIỂN CHÍNH (DASHBOARD)
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // THỐNG KÊ CƠ BẢN (STATISTICS)
        Route::get('/statistics', [AdminController::class, 'statistics'])
            ->name('statistics');

        // BÁO CÁO & BIỂU ĐỒ (REPORTS)
        Route::prefix('reports')->name('reports.')->group(function (): void {
            // Báo cáo doanh thu động (7 ngày gần nhất, khoảng ngày)
            Route::get('/revenue', [AdminController::class, 'revenueReport'])
                ->name('revenue');

            // Báo cáo xếp hạng sản phẩm bán chạy
            Route::get('/products', [AdminController::class, 'productSalesReport'])
                ->name('products');
        });

        // QUẢN LÝ NHÀ CUNG CẤP (SUPPLIERS)
        Route::get('/suppliers', [SupplierController::class, 'index'])
            ->name('suppliers.index'); // Danh sách nhà cung cấp

        Route::post('/suppliers', [SupplierController::class, 'store'])
            ->name('suppliers.store'); // Thêm mới nhà cung cấp

        Route::delete('/suppliers/{id}', [SupplierController::class, 'destroy'])
            ->name('suppliers.destroy'); // Xóa nhà cung cấp

        Route::put('/suppliers/{id}', [SupplierController::class, 'update'])
            ->name('suppliers.update'); // Cập nhật nhà cung cấp

        // QUẢN LÝ DANH MỤC SẢN PHẨM (CATEGORIES CRUD)
        Route::resource('categories', CategoryController::class)
            ->except(['show', 'create', 'edit']);

        // QUẢN LÝ DANH SÁCH SẢN PHẨM (PRODUCTS CRUD)
        Route::resource('products', ProductController::class)
            ->except(['show', 'create', 'edit']);

        // QUẢN LÝ MÃ KHUYẾN MÃI (PROMOTIONS CRUD)
        Route::resource('promotions', PromotionController::class)
            ->except(['show', 'create', 'edit']);

        // QUẢN LÝ ĐÁNH GIÁ SẢN PHẨM (PRODUCT REVIEWS)
        Route::resource('reviews', ProductReviewController::class)
            ->only(['index', 'update', 'destroy']);

        // QUẢN LÝ ĐƠN HÀNG (ORDERS SYSTEM)
        Route::get('/orders', [OrderController::class, 'index'])
            ->name('orders.index'); // Xem danh sách đơn hàng toàn hệ thống

        Route::get('/orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show'); // Chi tiết thông tin và tiến trình đơn hàng

        Route::patch('/orders/{order}', [OrderController::class, 'update'])
            ->name('orders.update'); // Cập nhật trạng thái đơn hàng (đang giao, đã hoàn thành...)

        // QUẢN LÝ THÀNH VIÊN (USERS SYSTEM)
        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index'); // Xem danh sách tài khoản thành viên

        Route::patch('/users/{user}', [UserController::class, 'update'])
            ->name('users.update'); // Cập nhật trạng thái tài khoản (khóa/mở khóa)

        // THIẾT LẬP PHÂN QUYỀN VAI TRÒ (ROLE PERMISSIONS)
        Route::get('/permissions', [AdminController::class, 'permissions'])
            ->name('permissions'); // Bảng phân quyền chi tiết

        Route::post('/permissions', [AdminController::class, 'updatePermissions'])
            ->name('permissions.update'); // Cập nhật thay đổi phân quyền hệ thống

        // NHẬT KÝ HOẠT ĐỘNG HỆ THỐNG (SYSTEM LOGS)
        Route::get('/logs', [AdminController::class, 'logs'])
            ->name('logs');

        // PHÂN HỆ QUẢN LÝ KHO HÀNG (WAREHOUSE MANAGEMENT)
        Route::prefix('warehouse')->name('warehouse.')->group(function () {
            
            // 1. Quản lý Phiếu Nhập Kho (Warehouse Receipts)
            Route::get('/receipts', [\App\Http\Controllers\Admin\WarehouseController::class, 'receipts'])->name('receipts');
            Route::get('/receipts/create', [\App\Http\Controllers\Admin\WarehouseController::class, 'createReceipt'])->name('receipts.create');
            Route::post('/receipts', [\App\Http\Controllers\Admin\WarehouseController::class, 'storeReceipt'])->name('receipts.store');
            Route::get('/receipts/{id}', [\App\Http\Controllers\Admin\WarehouseController::class, 'showReceipt'])->name('receipts.show');
            
            // 2. Thẻ Kho & Xem Tồn Kho (Stock Card & Inventory History)
            Route::get('/inventory', [\App\Http\Controllers\Admin\WarehouseController::class, 'inventory'])->name('inventory');
            Route::get('/inventory/{id}/history', [\App\Http\Controllers\Admin\WarehouseController::class, 'stockHistory'])->name('inventory.history');
            
            // 3. Quản lý Phiếu Xuất Kho Hủy (Warehouse Issues)
            Route::get('/issues', [\App\Http\Controllers\Admin\WarehouseController::class, 'issues'])->name('issues');
            Route::get('/issues/create', [\App\Http\Controllers\Admin\WarehouseController::class, 'createIssue'])->name('issues.create');
            Route::post('/issues', [\App\Http\Controllers\Admin\WarehouseController::class, 'storeIssue'])->name('issues.store');
            Route::get('/issues/{id}', [\App\Http\Controllers\Admin\WarehouseController::class, 'showIssue'])->name('issues.show');
            
            // 4. Quản lý Phiếu Kiểm Kê Kho (Inventory Balance Checks)
            Route::get('/checks', [\App\Http\Controllers\Admin\WarehouseController::class, 'checks'])->name('checks');
            Route::get('/checks/create', [\App\Http\Controllers\Admin\WarehouseController::class, 'createCheck'])->name('checks.create');
            Route::post('/checks', [\App\Http\Controllers\Admin\WarehouseController::class, 'storeCheck'])->name('checks.store');
            Route::get('/checks/{id}', [\App\Http\Controllers\Admin\WarehouseController::class, 'showCheck'])->name('checks.show');
        });

        // HỖ TRỢ - TIẾP NHẬN PHẢN HỒI (CONTACTS - Task 46)
        Route::prefix('contacts')->name('contacts.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\ContactController::class, 'index'])->name('index'); // Danh sách phản hồi
            Route::get('/{id}', [\App\Http\Controllers\Admin\ContactController::class, 'show'])->name('show'); // Chi tiết liên hệ phản hồi
            Route::post('/{id}/reply', [\App\Http\Controllers\Admin\ContactController::class, 'reply'])->name('reply'); // Gửi phản hồi qua email
        });

        // TRUNG TÂM TRỢ GIÚP - CÂU HỎI THƯỜNG GẶP (FAQS - Task 47)
        Route::prefix('faqs')->name('faqs.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\FaqController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\FaqController::class, 'store'])->name('store');
            Route::post('/{id}/toggle', [\App\Http\Controllers\Admin\FaqController::class, 'toggle'])->name('toggle'); // Kích hoạt/Ẩn hiển thị FAQ
            Route::delete('/{id}', [\App\Http\Controllers\Admin\FaqController::class, 'destroy'])->name('destroy');
        });

        // QUẢN LÝ BANNER QUẢNG CÁO (BANNERS CONTENT - Task 48)
        Route::prefix('banners')->name('banners.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\BannerController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\BannerController::class, 'store'])->name('store');
            Route::post('/{id}/toggle', [\App\Http\Controllers\Admin\BannerController::class, 'toggle'])->name('toggle'); // Kích hoạt/Ẩn hiển thị Banner
            Route::delete('/{id}', [\App\Http\Controllers\Admin\BannerController::class, 'destroy'])->name('destroy');
        });
    });
	
/*
|--------------------------------------------------------------------------
| BẢO VỆ ĐƯỜNG DẪN HỒ SƠ THEO VAI TRÒ (BẢO VỆ LINK)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // HỒ SƠ KHÁCH HÀNG (Vai trò: user)
    Route::get('/profile', function () {
        return view('user.profile-user');
    })->middleware('role:user')->name('profile');

    // HỒ SƠ QUẢN TRỊ VIÊN (Vai trò: admin)
    Route::get('/profile-admin', function () {
        return view('admin.profile-admin');
    })->middleware('role:admin')->name('profile.admin');
});
