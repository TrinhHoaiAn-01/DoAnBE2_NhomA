@extends('layouts.admin', ['title' => 'Chi tiết Liên hệ'])

@section('content')
    <div class="d-flex align-items-center mb-4">
        <a href="{{ route('admin.contacts.index') }}" class="btn btn-outline-secondary me-3"><i class="bi bi-arrow-left"></i> Trở về</a>
        <div>
            <h1 class="h3 fw-bold mb-0">Chi tiết Liên hệ #{{ $contact->id }}</h1>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="surface p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Nội dung yêu cầu</h5>
                    @if($contact->status == 'pending')
                        <span class="badge bg-warning text-dark px-3 py-2">Chờ xử lý</span>
                    @else
                        <span class="badge bg-success px-3 py-2">Đã giải quyết</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Người gửi:</label>
                    <div class="fw-bold fs-5">{{ $contact->name }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-6">
                        <label class="text-muted small">Email:</label>
                        <div><a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a></div>
                    </div>
                    <div class="col-6">
                        <label class="text-muted small">Số điện thoại:</label>
                        <div>{{ $contact->phone ?? 'Không cung cấp' }}</div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Chủ đề:</label>
                    <div class="fw-medium"><span class="badge bg-secondary">{{ $contact->subject }}</span></div>
                </div>
                <div class="mb-3">
                    <label class="text-muted small">Nội dung chi tiết:</label>
                    <div class="bg-light p-3 rounded border">
                        {!! nl2br(e($contact->message)) !!}
                    </div>
                </div>
                <div class="text-muted small text-end">
                    Gửi lúc: {{ $contact->created_at->format('d/m/Y H:i:s') }}
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="surface p-4 h-100">
                <h5 class="fw-bold mb-4">Phản hồi khách hàng</h5>
                
                @if($contact->status == 'resolved')
                    <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-25">
                        <h6 class="alert-heading fw-bold"><i class="bi bi-envelope-check"></i> Đã gửi phản hồi</h6>
                        <hr>
                        <p class="mb-0">{!! nl2br(e($contact->reply_message)) !!}</p>
                        <div class="text-muted small mt-2">Cập nhật lúc: {{ $contact->updated_at->format('d/m/Y H:i:s') }}</div>
                    </div>
                @else
                    <form action="{{ route('admin.contacts.reply', $contact->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0 fw-semibold">Nội dung email trả lời <span class="text-danger">*</span></label>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bi bi-lightning-charge-fill text-warning"></i> Mẫu trả lời nhanh
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                        <li><a class="dropdown-item quick-reply-template" href="#" data-text="Xin chào {{ $contact->name }},\n\nCảm ơn bạn đã liên hệ với NeoMart. Chúng tôi đã ghi nhận đóng góp ý kiến của bạn về chủ đề '{{ $contact->subject }}' và đang tiến hành xử lý.\n\nNếu có thêm bất kỳ câu hỏi nào, vui lòng phản hồi trực tiếp qua email này.\n\nTrân trọng,\nĐội ngũ CSKH NeoMart.">Cảm ơn & Ghi nhận ý kiến</a></li>
                                        <li><a class="dropdown-item quick-reply-template" href="#" data-text="Xin chào {{ $contact->name }},\n\nYêu cầu hỗ trợ của bạn liên quan đến '{{ $contact->subject }}' đã được chúng tôi giải quyết thành công trên hệ thống.\n\nBạn vui lòng kiểm tra lại dịch vụ của mình. Nếu có vấn đề phát sinh, hãy liên hệ lại để chúng tôi kịp thời xử lý.\n\nTrân trọng,\nĐội ngũ kỹ thuật NeoMart.">Xử lý thành công / Đã giải quyết</a></li>
                                        <li><a class="dropdown-item quick-reply-template" href="#" data-text="Xin chào {{ $contact->name }},\n\nĐể hỗ trợ bạn tốt nhất về vấn đề '{{ $contact->subject }}', bạn vui lòng cung cấp thêm cho chúng tôi một số thông tin chi tiết (Ví dụ: Mã đơn hàng, Số điện thoại đăng ký hoặc Ảnh chụp màn hình lỗi nếu có).\n\nRất mong nhận được phản hồi sớm từ bạn.\n\nTrân trọng,\nĐội ngũ CSKH NeoMart.">Yêu cầu thêm thông tin</a></li>
                                    </ul>
                                </div>
                            </div>
                            <textarea id="reply_message" name="reply_message" class="form-control" rows="8" placeholder="Nhập nội dung phản hồi. Hệ thống sẽ tự động gửi email cho khách..." required></textarea>
                            <div class="form-text text-muted">Thư sẽ được gửi tới hòm thư: <strong>{{ $contact->email }}</strong></div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2.5 fw-semibold shadow-sm"><i class="bi bi-send me-1"></i> Gửi phản hồi & Đóng yêu cầu</button>
                    </form>

                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            document.querySelectorAll('.quick-reply-template').forEach(item => {
                                item.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const text = this.getAttribute('data-text').split('\\n').join('\n');
                                    document.getElementById('reply_message').value = text;
                                });
                            });
                        });
                    </script>
                @endif
            </div>
        </div>
    </div>
@endsection
