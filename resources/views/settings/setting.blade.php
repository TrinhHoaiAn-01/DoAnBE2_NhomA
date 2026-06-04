@extends('layouts.app', ['title' => 'Settings - NeoMart'])

@push('styles')
<style>
    .settings-page {
        max-width: 920px;
        margin: 0 auto;
    }

    .settings-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.75rem;
        box-shadow: 0 12px 30px rgba(15, 23, 42, 0.06);
    }

    .settings-title {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        color: var(--text-primary);
        font-weight: 800;
        margin-bottom: 0.35rem;
    }

    .setting-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 1.5rem;
        padding: 1.35rem 0;
        border-bottom: 1px solid var(--border);
    }

    .setting-item:last-child {
        border-bottom: 0;
        padding-bottom: 0;
    }

    .setting-label {
        display: flex;
        align-items: center;
        gap: 0.65rem;
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .setting-label i {
        color: var(--primary);
    }

    .setting-help {
        color: var(--text-muted);
        margin: 0.25rem 0 0;
        font-size: 0.9rem;
    }

    .setting-control {
        width: min(240px, 100%);
        flex-shrink: 0;
    }

    .settings-note {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
        color: var(--text-muted);
        font-size: 0.9rem;
    }

    @media (max-width: 575.98px) {
        .settings-card {
            padding: 1.25rem;
        }

        .setting-item {
            align-items: flex-start;
            flex-direction: column;
            gap: 0.75rem;
        }

        .setting-control {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<div class="settings-page">
    <div class="settings-card">
        <div class="mb-4">
            <h1 class="settings-title">
                <i class="bi bi-gear"></i>
                Cài đặt hệ thống
            </h1>
            <p class="setting-help mb-0">Tùy chỉnh giao diện và trải nghiệm người dùng</p>
        </div>

        <div class="setting-item">
            <div>
                <div class="setting-label">
                    <i class="bi bi-circle-half"></i>
                    Giao diện
                </div>
                <p class="setting-help">Chọn giao diện sáng hoặc tối cho toàn bộ website</p>
            </div>
            <div class="setting-control">
                <select class="form-select" id="themeSelect">
                    <option value="light">Sáng</option>
                    <option value="dark">Tối</option>
                </select>
            </div>
        </div>

        <div class="setting-item">
            <div>
                <div class="setting-label">
                    <i class="bi bi-fonts"></i>
                    Kích thước chữ
                </div>
                <p class="setting-help">Thay đổi kích thước chữ trên toàn bộ website</p>
            </div>
            <div class="setting-control">
                <select class="form-select" id="fontSizeSelect">
                    <option value="14px">Nhỏ</option>
                    <option value="16px">Vừa</option>
                    <option value="18px">Lớn</option>
                    <option value="20px">Rất lớn</option>
                </select>
            </div>
        </div>

        <div class="setting-item">
            <div>
                <div class="setting-label">
                    <i class="bi bi-translate"></i>
                    Ngôn ngữ
                </div>
                <p class="setting-help">Đổi ngôn ngữ hiển thị</p>
            </div>
            <div class="setting-control">
                <select class="form-select" id="languageSelect">
                    <option value="vi">Tiếng Việt</option>
                    <option value="en">English</option>
                </select>
            </div>
        </div>

        <div class="settings-note">
            <i class="bi bi-check2-circle text-success"></i>
            <span>Lưu tự động</span>
            <span class="d-none d-sm-inline">-</span>
            <span>Các thay đổi được lưu trên trình duyệt của bạn và áp dụng cho mọi trang.</span>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        const themeSelect = document.getElementById('themeSelect');
        const fontSizeSelect = document.getElementById('fontSizeSelect');
        const languageSelect = document.getElementById('languageSelect');

        function syncControls() {
            const preferences = window.NeoMartPreferences?.getPreferences?.() || {
                darkMode: localStorage.getItem('dark-mode') === 'true',
                fontSize: localStorage.getItem('font-size') || '16px',
                language: localStorage.getItem('language') === 'en' ? 'en' : 'vi',
            };

            themeSelect.value = preferences.darkMode ? 'dark' : 'light';
            fontSizeSelect.value = preferences.fontSize;
            languageSelect.value = preferences.language;
        }

        syncControls();

        themeSelect.addEventListener('change', function () {
            window.NeoMartPreferences?.setPreference('darkMode', this.value === 'dark');
            syncControls();
        });

        fontSizeSelect.addEventListener('change', function () {
            window.NeoMartPreferences?.setPreference('fontSize', this.value);
        });

        languageSelect.addEventListener('change', function () {
            window.NeoMartPreferences?.setPreference('language', this.value);
            window.location.reload();
        });
    })();
</script>
@endpush
