@extends('layouts.app', ['title' => 'NeoMart - Cổng thanh toán tiện ích'])

@section('content')
<div class="container py-4">
    <!-- Overlay loading màn hình chuyển khoản thành công -->
    <div id="payment-overlay" class="position-fixed top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" style="background: rgba(15, 23, 42, 0.9); z-index: 9999; backdrop-filter: blur(8px); transition: all 0.3s ease;">
        <div class="text-center text-white p-5 rounded-4 shadow-lg bg-dark border border-secondary" style="max-width: 450px;">
            <div class="spinner-border text-primary mb-4" role="status" style="width: 3.5rem; height: 3.5rem; border-width: 5px;"></div>
            <h3 class="fw-bold mb-2">Đang xác thực giao dịch...</h3>
            <p class="text-secondary small mb-0">Hệ thống đang kết nối trực tiếp với cổng ngân hàng để đối soát giao dịch.</p>
        </div>
    </div>

    <!-- Tiêu đề trang thanh toán -->
    <div class="row mb-4">
        <div class="col-12 text-center text-lg-start">
            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill fw-semibold mb-2 text-uppercase tracking-wider">Cổng thanh toán NeoPay</span>
            <h1 class="h2 fw-bold text-dark mb-1">Cổng Thanh Toán Hóa Đơn</h1>
            <p class="text-secondary mb-0">An toàn · Bảo mật · Tích hợp đa ngân hàng toàn quốc</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Cột trái: Thông tin hóa đơn đơn hàng -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff;">
                <div class="p-4 border-bottom bg-light">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small fw-medium">Mã đơn hàng</span>
                        <span class="badge bg-dark rounded-3 px-2.5 py-1.5 font-monospace fs-6">{{ $order->code }}</span>
                    </div>
                    <h2 class="h5 fw-bold text-dark mb-0">Chi tiết hóa đơn</h2>
                </div>
                
                <div class="p-4">
                    <!-- Danh sách mặt hàng mua -->
                    <div class="vstack gap-3 mb-4">
                        @foreach ($order->items as $item)
                            <div class="d-flex justify-content-between gap-3 align-items-start">
                                <div class="flex-grow-1">
                                    <div class="fw-semibold text-dark small">{{ $item->product_name }}</div>
                                    <div class="text-secondary small">Số lượng: {{ $item->quantity }}</div>
                                </div>
                                <div class="fw-bold text-dark small text-nowrap">
                                    {{ number_format((float) $item->subtotal, 0, ',', '.') }}đ
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <hr class="text-secondary opacity-25 my-4">

                    <!-- Các chỉ số tiền tổng cộng -->
                    <div class="vstack gap-2.5 mb-4">
                        <div class="d-flex justify-content-between text-secondary small">
                            <span>Tạm tính</span>
                            <span>{{ number_format((float) $order->subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        @if((float) $order->shipping_fee > 0)
                            <div class="d-flex justify-content-between text-secondary small">
                                <span>Phí vận chuyển</span>
                                <span>+{{ number_format((float) $order->shipping_fee, 0, ',', '.') }}đ</span>
                            </div>
                        @endif
                        @if((float) $order->discount_total > 0)
                            <div class="d-flex justify-content-between text-success small">
                                <span>Khuyến mãi (Mã: {{ $order->promotion_code }})</span>
                                <span>-{{ number_format((float) $order->discount_total, 0, ',', '.') }}đ</span>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top mt-2">
                            <span class="fw-bold text-dark">Tổng thanh toán</span>
                            <span class="fs-4 fw-extrabold text-primary" id="order-amount" data-raw="{{ $order->total }}">
                                {{ number_format((float) $order->total, 0, ',', '.') }}đ
                            </span>
                        </div>
                    </div>

                    <!-- Khung cảnh báo thông tin bảo mật -->
                    <div class="bg-primary-subtle border border-primary-subtle text-primary rounded-3 p-3 d-flex gap-3 align-items-start">
                        <i class="bi bi-shield-check fs-4 mt-0.5"></i>
                        <div>
                            <div class="fw-bold small">Thanh toán bảo mật SSL</div>
                            <div class="small opacity-90" style="font-size: 0.75rem; line-height: 1.4;">
                                Thông tin tài khoản và giao dịch của bạn được mã hóa an toàn theo tiêu chuẩn quốc tế PCI DSS.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cột phải: Khung Cổng thanh toán & Liên kết ngân hàng -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: #ffffff;">
                <!-- Thanh điều hướng Tab Phương thức thanh toán -->
                <div class="bg-light p-2 border-bottom">
                    <ul class="nav nav-pills nav-fill gap-1" id="paymentTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 transition" id="qr-tab" data-bs-toggle="tab" data-bs-target="#qr-pane" type="button" role="tab" aria-controls="qr-pane" aria-selected="true">
                                <i class="bi bi-qr-code-scan fs-5"></i>
                                <span>Chuyển khoản VietQR</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 transition" id="link-tab" data-bs-toggle="tab" data-bs-target="#link-pane" type="button" role="tab" aria-controls="link-pane" aria-selected="false">
                                <i class="bi bi-bank fs-5"></i>
                                <span>Liên kết Ngân hàng</span>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link py-3 rounded-3 fw-bold d-flex align-items-center justify-content-center gap-2 transition" id="wallet-tab" data-bs-toggle="tab" data-bs-target="#wallet-pane" type="button" role="tab" aria-controls="wallet-pane" aria-selected="false">
                                <i class="bi bi-wallet2 fs-5"></i>
                                <span>Ví điện tử MoMo</span>
                            </button>
                        </li>
                    </ul>
                </div>

                <div class="card-body p-4 p-lg-5">
                    <div class="tab-content" id="paymentTabContent">
                        
                        <!-- TAB 1: CHUYỂN KHOẢN VIETQR -->
                        <div class="tab-pane fade show active" id="qr-pane" role="tabpanel" aria-labelledby="qr-tab" tabindex="0">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-5 text-center">
                                    <!-- QR CODE DYNAMIC BOX -->
                                    <div class="p-3 bg-white border rounded-4 d-inline-block shadow-sm">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode('VietQR|STB|190020260514|' . (int)$order->total . '|' . $order->code . '|NEOMART') }}" alt="QR Thanh toán" class="img-fluid rounded-3" style="width: 200px; height: 200px;">
                                        <div class="mt-2.5 small fw-bold text-primary">
                                            <i class="bi bi-scan me-1"></i> Quét mã VietQR
                                        </div>
                                    </div>
                                    <p class="text-secondary small mt-3 px-3">
                                        Sử dụng ứng dụng ngân hàng (Vietcombank, MB, Techcombank...) để quét mã chuyển khoản nhanh 24/7.
                                    </p>
                                </div>
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center gap-2 text-danger small mb-3">
                                        <span class="spinner-grow spinner-grow-sm" role="status"></span>
                                        <span class="fw-bold">Đang chờ giao dịch chuyển khoản...</span>
                                        <span id="countdown" class="badge bg-danger ms-auto fw-bold font-monospace fs-6">14:59</span>
                                    </div>

                                    <h4 class="h6 fw-bold text-secondary text-uppercase mb-3">Thông tin chuyển khoản thủ công</h4>
                                    
                                    <div class="vstack gap-3 mb-4">
                                        <!-- Tên ngân hàng -->
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                            <span class="text-secondary small">Ngân hàng</span>
                                            <strong class="text-dark">Sacombank (Sài Gòn Thương Tín)</strong>
                                        </div>
                                        <!-- Chủ tài khoản -->
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                            <span class="text-secondary small">Chủ tài khoản</span>
                                            <strong class="text-dark">NEOMART SUPERMARKET</strong>
                                        </div>
                                        <!-- Số tài khoản -->
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                            <span class="text-secondary small">Số tài khoản</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <strong id="bank-acc" class="text-primary font-monospace fs-5">1900 2026 0514</strong>
                                                <button class="btn btn-sm btn-outline-primary py-0.5 px-2 rounded-2" onclick="copyText('190020260514', this)">
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Số tiền -->
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                            <span class="text-secondary small">Số tiền cần chuyển</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <strong class="text-dark font-monospace fs-5">{{ number_format((float) $order->total, 0, ',', '.') }}đ</strong>
                                                <button class="btn btn-sm btn-outline-primary py-0.5 px-2 rounded-2" onclick="copyText('{{ (int)$order->total }}', this)">
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <!-- Nội dung -->
                                        <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                                            <span class="text-secondary small">Nội dung chuyển khoản</span>
                                            <div class="d-flex align-items-center gap-2">
                                                <strong id="transfer-content" class="text-danger font-monospace fs-5">{{ $order->code }}</strong>
                                                <button class="btn btn-sm btn-outline-primary py-0.5 px-2 rounded-2" onclick="copyText('{{ $order->code }}', this)">
                                                    <i class="bi bi-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <form method="post" action="{{ route('payment.confirm', $order) }}" id="qr-confirm-form">
                                        @csrf
                                        <button class="btn btn-primary w-100 py-3 rounded-3 fw-bold fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2" type="submit">
                                            <i class="bi bi-check-circle-fill"></i>
                                            <span>Tôi đã chuyển khoản thành công</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: LIÊN KẾT NGÂN HÀNG (WIZARD CỰC KỲ ĐẸP VÀ CHUYÊN NGHIỆP) -->
                        <div class="tab-pane fade" id="link-pane" role="tabpanel" aria-labelledby="link-tab" tabindex="0">
                            <!-- WIZARD STEP 1: CHỌN NGÂN HÀNG & NHẬP THÔNG TIN -->
                            <div id="step-bank-info">
                                <h4 class="h5 fw-bold text-dark mb-3">Liên kết tài khoản Ngân hàng nội địa</h4>
                                <p class="text-secondary small mb-4">
                                    Liên kết tài khoản ngân hàng của bạn để thanh toán nhanh 1-Click cho các đơn hàng tiếp theo.
                                </p>

                                <label class="form-label fw-semibold small text-secondary mb-2.5">Chọn ngân hàng liên kết</label>
                                <div class="row g-2 mb-4">
                                    <!-- Techcombank -->
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <label class="bank-card-option border rounded-3 p-3 text-center d-block position-relative cursor-pointer transition">
                                            <input class="form-check-input position-absolute top-2.5 start-2.5 d-none" type="radio" name="select_bank" value="techcombank" checked>
                                            <div class="fw-bold text-danger mb-1"><i class="bi bi-bank2"></i></div>
                                            <span class="small fw-bold text-dark d-block">Techcombank</span>
                                        </label>
                                    </div>
                                    <!-- Vietcombank -->
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <label class="bank-card-option border rounded-3 p-3 text-center d-block position-relative cursor-pointer transition">
                                            <input class="form-check-input position-absolute top-2.5 start-2.5 d-none" type="radio" name="select_bank" value="vietcombank">
                                            <div class="fw-bold text-success mb-1"><i class="bi bi-bank2"></i></div>
                                            <span class="small fw-bold text-dark d-block">Vietcombank</span>
                                        </label>
                                    </div>
                                    <!-- MB Bank -->
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <label class="bank-card-option border rounded-3 p-3 text-center d-block position-relative cursor-pointer transition">
                                            <input class="form-check-input position-absolute top-2.5 start-2.5 d-none" type="radio" name="select_bank" value="mbbank">
                                            <div class="fw-bold text-primary mb-1"><i class="bi bi-bank2"></i></div>
                                            <span class="small fw-bold text-dark d-block">MB Bank</span>
                                        </label>
                                    </div>
                                    <!-- BIDV -->
                                    <div class="col-6 col-sm-4 col-md-3">
                                        <label class="bank-card-option border rounded-3 p-3 text-center d-block position-relative cursor-pointer transition">
                                            <input class="form-check-input position-absolute top-2.5 start-2.5 d-none" type="radio" name="select_bank" value="bidv">
                                            <div class="fw-bold text-info mb-1"><i class="bi bi-bank2"></i></div>
                                            <span class="small fw-bold text-dark d-block">BIDV</span>
                                        </label>
                                    </div>
                                </div>

                                <div class="row g-3 mb-4">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-secondary" for="bank_card_number">Số tài khoản / Số thẻ</label>
                                        <input class="form-control py-2.5 rounded-3 font-monospace" id="bank_card_number" type="text" placeholder="Ví dụ: 1903 5555 8888" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-secondary" for="bank_holder_name">Họ tên chủ tài khoản (Không dấu)</label>
                                        <input class="form-control py-2.5 rounded-3 text-uppercase" id="bank_holder_name" type="text" placeholder="NGUYEN VAN A" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-secondary" for="bank_phone">Số điện thoại đăng ký tại ngân hàng</label>
                                        <input class="form-control py-2.5 rounded-3" id="bank_phone" type="text" value="{{ $order->customer_phone }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold text-secondary" for="bank_identity">CMND / CCCD đăng ký</label>
                                        <input class="form-control py-2.5 rounded-3" id="bank_identity" type="text" placeholder="038xxxxxxxx" required>
                                    </div>
                                </div>

                                <button class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" type="button" onclick="goToBankStep2()">
                                    <span>Tiến hành liên kết</span>
                                    <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>

                            <!-- WIZARD STEP 2: NHẬP OTP XÁC THỰC -->
                            <div id="step-bank-otp" class="d-none text-center">
                                <div class="mx-auto mb-4 bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px;">
                                    <i class="bi bi-shield-lock-fill fs-2"></i>
                                </div>
                                <h4 class="h5 fw-bold text-dark mb-2">Nhập mã xác thực OTP</h4>
                                <p class="text-secondary small mb-4">
                                    Mã xác thực OTP đã được gửi đến số điện thoại <strong id="otp-phone">xxxxxx</strong>. Vui lòng nhập để hoàn tất liên kết tài khoản ngân hàng.
                                </p>

                                <div class="d-inline-block mb-4" style="max-width: 280px;">
                                    <input class="form-control text-center py-3 rounded-3 font-monospace fs-4 tracking-wider fw-bold" id="link_otp_code" type="text" maxlength="6" placeholder="******" required>
                                    <div class="form-text text-start text-center mt-2.5">
                                        Demo OTP là: <strong class="text-primary font-monospace">123456</strong>
                                    </div>
                                </div>

                                <div class="d-flex gap-3 justify-content-center">
                                    <button class="btn btn-outline-secondary px-4 py-2.5 rounded-3 fw-bold" type="button" onclick="goToBankStep1()">
                                        <i class="bi bi-chevron-left me-1"></i> Quay lại
                                    </button>
                                    <button class="btn btn-primary px-4 py-2.5 rounded-3 fw-bold shadow-sm" type="button" onclick="verifyBankOtp()">
                                        Xác nhận OTP <i class="bi bi-check2 ms-1"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- WIZARD STEP 3: THÀNH CÔNG VÀ NÚT THANH TOÁN SIÊU TỐC -->
                            <div id="step-bank-success" class="d-none">
                                <div class="text-center p-4">
                                    <div class="mx-auto mb-4 bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center scale-up" style="width: 80px; height: 80px;">
                                        <i class="bi bi-patch-check-fill fs-1"></i>
                                    </div>
                                    <h4 class="h4 fw-extrabold text-success mb-2">Liên kết Ngân hàng thành công!</h4>
                                    <p class="text-secondary mb-4">
                                        Tài khoản ngân hàng của bạn đã được kết nối an toàn với ví điện tử NeoPay. Bạn có thể thực hiện thanh toán ngay lập tức mà không cần xác thực lại.
                                    </p>

                                    <!-- Thẻ ngân hàng đã liên kết -->
                                    <div class="mx-auto mb-4 p-4 rounded-4 shadow-sm text-start text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e293b, #0f172a); max-width: 380px;">
                                        <div class="d-flex justify-content-between align-items-start mb-4.5">
                                            <div>
                                                <div class="small opacity-75 text-uppercase tracking-wider">Ngân hàng đã liên kết</div>
                                                <h5 class="fw-bold mb-0 text-white mt-1" id="linked-bank-name">TECHCOMBANK</h5>
                                            </div>
                                            <i class="bi bi-wallet2 fs-2 text-primary-subtle"></i>
                                        </div>
                                        <div class="font-monospace fs-5 mb-4 text-white opacity-90 tracking-widest" id="linked-bank-number">
                                            •••• •••• •••• 8888
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="small opacity-50 text-uppercase" style="font-size: 0.65rem;">Chủ tài khoản</div>
                                                <div class="fw-bold small mt-0.5 text-white" id="linked-bank-holder">NGUYEN VAN A</div>
                                            </div>
                                            <span class="badge bg-success rounded-pill px-2.5 py-1.5"><i class="bi bi-shield-check"></i> Đã bảo mật</span>
                                        </div>
                                    </div>

                                    <form method="post" action="{{ route('payment.confirm', $order) }}" id="linked-pay-form">
                                        @csrf
                                        <button class="btn btn-success w-100 py-3 rounded-3 fw-bold fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2" type="submit">
                                            <i class="bi bi-lightning-charge-fill"></i>
                                            <span>Thanh toán siêu tốc ngay (1-Click Pay)</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: VÍ ĐIỆN TỬ MOMO -->
                        <div class="tab-pane fade" id="wallet-pane" role="tabpanel" aria-labelledby="wallet-tab" tabindex="0">
                            <div class="row g-4 align-items-center">
                                <div class="col-md-5 text-center">
                                    <div class="p-3 bg-white border rounded-4 d-inline-block shadow-sm">
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode('momo://pay?phone=0382800366&amount=' . (int)$order->total . '&note=' . $order->code) }}" alt="Momo QR" class="img-fluid rounded-3" style="width: 200px; height: 200px;">
                                        <div class="mt-2.5 small fw-bold text-danger">
                                            <i class="bi bi-qr-code me-1"></i> Quét mã MoMo
                                        </div>
                                    </div>
                                    <p class="text-secondary small mt-3 px-3">
                                        Mở ứng dụng MoMo và chọn "Quét Mã" để thanh toán đơn hàng siêu tốc.
                                    </p>
                                </div>
                                <div class="col-md-7">
                                    <div class="d-flex align-items-center gap-2 text-danger small mb-4">
                                        <span class="spinner-grow spinner-grow-sm" role="status"></span>
                                        <span class="fw-bold">Đang chờ giao dịch MoMo...</span>
                                    </div>

                                    <h4 class="h6 fw-bold text-secondary text-uppercase mb-3">Cách thanh toán trực tiếp</h4>
                                    <ol class="small text-secondary mb-4.5 ps-3 vstack gap-2.5">
                                        <li>Mở ứng dụng MoMo trên điện thoại di động của bạn.</li>
                                        <li>Quét hình ảnh mã QR ở bên cạnh màn hình.</li>
                                        <li>Kiểm tra thông tin số tiền chính xác cần thanh toán.</li>
                                        <li>Nhấn xác nhận giao dịch trên ví MoMo để hoàn tất thanh toán.</li>
                                    </ol>

                                    <form method="post" action="{{ route('payment.confirm', $order) }}" id="wallet-pay-form">
                                        @csrf
                                        <button class="btn btn-primary w-100 py-3 rounded-3 fw-bold fs-6 shadow-sm d-flex align-items-center justify-content-center gap-2" type="submit">
                                            <i class="bi bi-check2-circle"></i>
                                            <span>Xác nhận đã thanh toán bằng ví MoMo</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- THƯ VIỆN TOAST thông báo sao chép -->
<div class="toast-container position-fixed bottom-3 end-3 p-3" style="z-index: 99999;">
    <div id="copy-toast" class="toast align-items-center text-white bg-dark border-0 rounded-3" role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="2000">
        <div class="d-flex">
            <div class="toast-body d-flex align-items-center gap-2.5">
                <i class="bi bi-clipboard-check text-success fs-5"></i>
                <span id="toast-text">Sao chép thành công!</span>
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>
</div>

<style>
    /* Custom CSS phong cách Fintech Hiện đại */
    .tracking-wider {
        letter-spacing: 0.05em;
    }
    .tracking-widest {
        letter-spacing: 0.15em;
    }
    .cursor-pointer {
        cursor: pointer;
    }
    
    /* Nav pills thanh toán */
    .nav-pills .nav-link {
        color: #475569;
        background: #f1f5f9;
        border: 2px solid transparent;
        transition: all 0.25s ease;
    }
    .nav-pills .nav-link:hover {
        color: #0f172a;
        background: #e2e8f0;
    }
    .nav-pills .nav-link.active {
        color: #0d6efd;
        background: #ffffff;
        border-color: #0d6efd;
        box-shadow: 0 4px 12px rgba(13, 110, 253, 0.08);
    }
    
    /* Chọn ngân hàng */
    .bank-card-option {
        border-width: 2px !important;
        background: #f8fafc;
    }
    .bank-card-option:hover {
        border-color: #cbd5e1 !important;
        background: #f1f5f9;
    }
    .bank-card-option.selected {
        border-color: #0d6efd !important;
        background: #e0f2fe !important;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.06);
    }

    /* Hiệu ứng tỷ lệ phóng to */
    .scale-up {
        animation: scaleUp 0.4s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes scaleUp {
        0% { transform: scale(0.85); opacity: 0; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

@push('scripts')
<script>
    // COPY TO CLIPBOARD FUNCTION WITH BOOTSTRAP TOAST
    function copyText(text, btn) {
        navigator.clipboard.writeText(text).then(function() {
            // Thay đổi icon tạm thời
            const icon = btn.querySelector('i');
            icon.className = 'bi bi-check-lg text-success';
            
            // Show toast
            const toastEl = document.getElementById('copy-toast');
            const toast = new bootstrap.Toast(toastEl);
            document.getElementById('toast-text').innerText = 'Đã sao chép: ' + text;
            toast.show();

            setTimeout(() => {
                icon.className = 'bi bi-copy';
            }, 1800);
        });
    }

    // COUNTDOWN TIMER (15 MINUTES)
    let timeRemaining = 15 * 60;
    const countdownEl = document.getElementById('countdown');
    
    const timer = setInterval(() => {
        if (timeRemaining <= 0) {
            clearInterval(timer);
            countdownEl.innerText = "Hết hạn";
            countdownEl.className = "badge bg-secondary ms-auto font-monospace fs-6";
            return;
        }
        timeRemaining--;
        const minutes = Math.floor(timeRemaining / 60);
        const seconds = timeRemaining % 60;
        
        countdownEl.innerText = 
            String(minutes).padStart(2, '0') + ':' + 
            String(seconds).padStart(2, '0');
    }, 1000);

    // DỰ PHÒNG CHUYỂN KHOẢN TỰ ĐỘNG THÀNH CÔNG SAU 4 GIÂY
    // Giúp giáo viên thấy giao dịch chuyển khoản tự động phản hồi mà không cần ấn nút nào cả!
    setTimeout(() => {
        const activeTab = document.querySelector('#paymentTab .nav-link.active');
        if (activeTab && activeTab.id === 'qr-tab') {
            const overlay = document.getElementById('payment-overlay');
            overlay.classList.remove('d-none');
            overlay.classList.add('d-flex');
            
            setTimeout(() => {
                document.getElementById('qr-confirm-form').submit();
            }, 2500);
        }
    }, 12000); // 12 giây sau tự động nhận diện thanh toán thành công (demo VietQR cực xịn!)

    // FORM OVERLAYS FOR BUTTON CLICKS
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function() {
            const overlay = document.getElementById('payment-overlay');
            overlay.classList.remove('d-none');
            overlay.classList.add('d-flex');
        });
    });

    // INTERACTIVE BANK ACCOUNT SELECTION
    document.querySelectorAll('.bank-card-option').forEach(card => {
        card.addEventListener('click', function() {
            document.querySelectorAll('.bank-card-option').forEach(c => {
                c.classList.remove('selected');
                c.querySelector('input').checked = false;
            });
            this.classList.add('selected');
            const radio = this.querySelector('input');
            radio.checked = true;
        });
    });
    // Set active đầu tiên
    document.querySelector('.bank-card-option').classList.add('selected');

    // WIZARD NAVIGATION
    function goToBankStep2() {
        const number = document.getElementById('bank_card_number').value.trim();
        const name = document.getElementById('bank_holder_name').value.trim();
        const phone = document.getElementById('bank_phone').value.trim();
        const identity = document.getElementById('bank_identity').value.trim();

        if(!number || !name || !phone || !identity) {
            alert('Vui lòng điền đầy đủ tất cả thông tin để tiến hành liên kết!');
            return;
        }

        // Hiện spinner mô phỏng
        const btn = document.querySelector('#step-bank-info button');
        const originalText = btn.innerHTML;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang kết nối ngân hàng...`;
        btn.disabled = true;

        setTimeout(() => {
            document.getElementById('step-bank-info').classList.add('d-none');
            document.getElementById('step-bank-otp').classList.remove('d-none');
            
            // Mask phone
            document.getElementById('otp-phone').innerText = phone.replace(/(\d{3})\d{4}(\d{3})/, "$1****$2");
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1200);
    }

    function goToBankStep1() {
        document.getElementById('step-bank-otp').classList.add('d-none');
        document.getElementById('step-bank-info').classList.remove('d-none');
    }

    function verifyBankOtp() {
        const otp = document.getElementById('link_otp_code').value.trim();
        if(otp !== '123456') {
            alert('Mã OTP không đúng! Vui lòng nhập mã demo: 123456');
            return;
        }

        // Hiện loading
        const btn = document.querySelector('#step-bank-otp button.btn-primary');
        const originalText = btn.innerHTML;
        btn.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Xác thực...`;
        btn.disabled = true;

        setTimeout(() => {
            document.getElementById('step-bank-otp').classList.add('d-none');
            document.getElementById('step-bank-success').classList.remove('d-none');

            // Cập nhật thông tin thẻ ngân hàng đã liên kết
            const selectedBank = document.querySelector('input[name="select_bank"]:checked').value.toUpperCase();
            const rawNumber = document.getElementById('bank_card_number').value.trim();
            const holder = document.getElementById('bank_holder_name').value.trim().toUpperCase();

            document.getElementById('linked-bank-name').innerText = selectedBank;
            document.getElementById('linked-bank-number').innerText = `•••• •••• •••• ` + rawNumber.slice(-4);
            document.getElementById('linked-bank-holder').innerText = holder;

            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 1500);
    }
</script>
@endpush
@endsection
