@extends('layouts.admin', ['title' => 'Trung tâm trợ giúp (FAQ)'])

@section('content')
    <div class="d-flex flex-wrap justify-content-between gap-3 align-items-start mb-4">
        <div>
            <p class="text-uppercase text-secondary small fw-semibold mb-2">Hỗ trợ & Nội dung</p>
            <h1 class="h2 fw-bold mb-1">Trung tâm trợ giúp (FAQ)</h1>
            <p class="text-secondary mb-0">Quản lý các câu hỏi thường gặp của khách hàng.</p>
        </div>
        <div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#addFaqModal">
                <i class="bi bi-plus-circle me-1"></i> Thêm câu hỏi mới
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle me-1"></i> {{ session('success') }}</div>
    @endif

    <div class="surface p-4">
        <!-- Bộ lọc và tìm kiếm nhanh -->
        <div class="row g-3 mb-4 align-items-center">
            <div class="col-12 col-md-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0 text-muted"><i class="bi bi-search"></i></span>
                    <input type="text" id="faqSearchInput" class="form-control border-start-0" placeholder="Tìm kiếm nhanh câu hỏi, câu trả lời...">
                </div>
            </div>
            <div class="col-12 col-md-7 d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
                <span class="small text-secondary fw-semibold"><i class="bi bi-funnel"></i> Lọc danh mục:</span>
                <button class="btn btn-sm btn-primary faq-filter-btn shadow-sm" data-filter="all">Tất cả</button>
                @foreach($faqs->pluck('category')->unique() as $cat)
                    @if($cat)
                        <button class="btn btn-sm btn-outline-secondary faq-filter-btn" data-filter="{{ Str::slug($cat) }}">{{ $cat }}</button>
                    @endif
                @endforeach
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="10%" class="text-center">Ưu tiên</th>
                        <th width="15%">Danh mục</th>
                        <th width="45%">Câu hỏi & Trả lời</th>
                        <th width="15%" class="text-center">Trạng thái</th>
                        <th width="15%"></th>
                    </tr>
                </thead>
                <tbody id="faqTableBody">
                    @forelse ($faqs as $faq)
                        <tr class="faq-row" data-category="{{ Str::slug($faq->category) }}" data-search="{{ Str::lower($faq->question) }} {{ Str::lower($faq->answer) }} {{ Str::lower($faq->category) }}">
                            <td class="text-center fw-bold text-secondary">{{ $faq->sort_order }}</td>
                            <td><span class="badge bg-primary bg-opacity-10 text-primary px-2.5 py-1.5 fw-semibold" style="font-size: 0.78rem;">{{ $faq->category }}</span></td>
                            <td>
                                <div class="fw-bold text-dark mb-1">{{ $faq->question }}</div>
                                <div class="text-muted small" style="max-height: 48px; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                    {{ $faq->answer }}
                                </div>
                            </td>
                            <td class="text-center">
                                <form action="{{ route('admin.faqs.toggle', $faq->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm {{ $faq->is_active ? 'btn-success bg-gradient' : 'btn-secondary' }} px-2.5">
                                        {!! $faq->is_active ? '<i class="bi bi-eye-fill"></i> Đang hiện' : '<i class="bi bi-eye-slash-fill"></i> Đã ẩn' !!}
                                    </button>
                                </form>
                            </td>
                            <td class="text-end">
                                <form action="{{ route('admin.faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa câu hỏi này?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="bi bi-trash-fill"></i> Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr id="faqEmptyRow">
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="bi bi-question-circle display-4 d-block mb-3 text-secondary" style="opacity: 0.4;"></i>
                                Chưa có dữ liệu câu hỏi thường gặp FAQ.
                            </td>
                        </tr>
                    @endforelse
                    
                    <!-- Dòng hiển thị khi không có kết quả tìm kiếm -->
                    <tr id="faqNoResultsRow" style="display: none;">
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-search display-4 d-block mb-3 text-secondary" style="opacity: 0.4;"></i>
                            Không tìm thấy câu hỏi FAQ nào phù hợp với bộ lọc.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('faqSearchInput');
            const filterBtns = document.querySelectorAll('.faq-filter-btn');
            const rows = document.querySelectorAll('.faq-row');
            const noResultsRow = document.getElementById('faqNoResultsRow');
            const emptyRow = document.getElementById('faqEmptyRow');
            
            let currentFilter = 'all';
            let searchQuery = '';

            function filterFaqs() {
                let visibleCount = 0;

                rows.forEach(row => {
                    const cat = row.getAttribute('data-category');
                    const searchText = row.getAttribute('data-search');
                    
                    const matchesCategory = (currentFilter === 'all' || cat === currentFilter);
                    const matchesSearch = searchText.includes(searchQuery);
                    
                    if (matchesCategory && matchesSearch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                // Hiển thị dòng báo không tìm thấy kết quả nếu không có dòng nào match
                if (rows.length > 0) {
                    if (visibleCount === 0) {
                        noResultsRow.style.display = '';
                    } else {
                        noResultsRow.style.display = 'none';
                    }
                }
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    searchQuery = this.value.toLowerCase().trim();
                    filterFaqs();
                });
            }

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    filterBtns.forEach(b => {
                        b.classList.remove('btn-primary', 'shadow-sm');
                        b.classList.add('btn-outline-secondary');
                    });
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('btn-primary', 'shadow-sm');
                    
                    currentFilter = this.getAttribute('data-filter');
                    filterFaqs();
                });
            });
        });
    </script>

    <!-- Modal Thêm FAQ -->
    <div class="modal fade" id="addFaqModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form class="modal-content" action="{{ route('admin.faqs.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Thêm Câu Hỏi Mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Danh mục (Ví dụ: Thanh toán, Giao hàng)</label>
                            <input type="text" name="category" class="form-control" required placeholder="Nhập danh mục...">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Độ ưu tiên (Số nhỏ xếp trên)</label>
                            <input type="number" name="sort_order" class="form-control" value="0" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Câu hỏi</label>
                            <input type="text" name="question" class="form-control" required placeholder="Nhập câu hỏi...">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Câu trả lời</label>
                            <textarea name="answer" class="form-control" rows="5" required placeholder="Nhập nội dung trả lời chi tiết..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Lưu câu hỏi</button>
                </div>
            </form>
        </div>
    </div>
@endsection
