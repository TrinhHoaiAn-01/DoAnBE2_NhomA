@extends('layouts.admin')

@section('title', 'Cài đặt')

@section('content')

<style>

    :root {

        --font-size-base: 16px;

    }

    body {

        font-size: var(--font-size-base);

        transition: all 0.3s ease;

    }

    /* =========================
        DARK MODE
    ========================== */

    body.dark-mode {

        background-color: #0f172a;

        color: #f8fafc;
    }

    body.dark-mode .settings-card {

        background-color: #1e293b;

        border-color: #334155;

        color: white;
    }

    body.dark-mode .form-select,
    body.dark-mode .form-check-input {

        background-color: #0f172a;

        color: white;

        border-color: #475569;
    }

    body.dark-mode .text-muted {

        color: #cbd5e1 !important;
    }

    /* =========================
        CARD
    ========================== */

    .settings-card {

        background: white;

        border-radius: 20px;

        padding: 30px;

        border: 1px solid #e2e8f0;

        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    }

    .setting-item {

        padding: 22px 0;

        border-bottom: 1px solid #e2e8f0;
    }

    .setting-item:last-child {

        border-bottom: none;
    }

</style>

<div class="container-fluid">

    <div class="settings-card">

        <!-- TITLE -->
        <div class="mb-4">

            <h2 class="fw-bold">

                <i class="bi bi-gear me-2"></i>

                Cài đặt hệ thống

            </h2>

            <p class="text-muted mb-0">

                Tùy chỉnh giao diện và trải nghiệm người dùng

            </p>

        </div>

        <!-- DARK MODE -->
        <div class="setting-item d-flex justify-content-between align-items-center">

            <div>

                <div class="fw-semibold fs-5">

                    <i class="bi bi-moon-stars me-2"></i>

                    Dark Mode

                </div>

                <div class="text-muted small">

                    Bật giao diện tối cho toàn hệ thống

                </div>

            </div>

            <div class="form-check form-switch">

                <input class="form-check-input"
                       type="checkbox"
                       id="darkModeToggle">

            </div>

        </div>

        <!-- FONT SIZE -->
        <div class="setting-item d-flex justify-content-between align-items-center">

            <div>

                <div class="fw-semibold fs-5">

                    <i class="bi bi-fonts me-2"></i>

                    Font Size

                </div>

                <div class="text-muted small">

                    Thay đổi kích thước chữ

                </div>

            </div>

            <div style="width:220px;">

                <select class="form-select"
                        id="fontSizeSelect">

                    <option value="14px">
                        Nhỏ
                    </option>

                    <option value="16px" selected>
                        Vừa
                    </option>

                    <option value="18px">
                        Lớn
                    </option>

                    <option value="20px">
                        Rất lớn
                    </option>

                </select>

            </div>

        </div>

        <!-- LANGUAGE -->
        <div class="setting-item d-flex justify-content-between align-items-center">

            <div>

                <div class="fw-semibold fs-5">

                    <i class="bi bi-translate me-2"></i>

                    Ngôn ngữ

                </div>

                <div class="text-muted small">

                    Đổi ngôn ngữ hiển thị

                </div>

            </div>

            <div style="width:220px;">

                <select class="form-select"
                        id="languageSelect">

                    <option value="vi">
                        Tiếng Việt
                    </option>

                    <option value="en">
                        English
                    </option>

                    <option value="jp">
                        日本語
                    </option>

                </select>

            </div>

        </div>

    </div>

</div>

<script>

    // =========================
    // DARK MODE
    // =========================

    const darkModeToggle =
        document.getElementById('darkModeToggle');

    if (localStorage.getItem('dark-mode') === 'true') {

        document.body.classList.add('dark-mode');

        darkModeToggle.checked = true;
    }

    darkModeToggle.addEventListener('change', function () {

        if (this.checked) {

            document.body.classList.add('dark-mode');

            localStorage.setItem('dark-mode', true);

        } else {

            document.body.classList.remove('dark-mode');

            localStorage.setItem('dark-mode', false);
        }

    });

    // =========================
    // FONT SIZE
    // =========================

    const fontSizeSelect =
        document.getElementById('fontSizeSelect');

    const savedFontSize =
        localStorage.getItem('font-size');

    if (savedFontSize) {

        document.documentElement.style.setProperty(
            '--font-size-base',
            savedFontSize
        );

        fontSizeSelect.value = savedFontSize;
    }

    fontSizeSelect.addEventListener('change', function () {

        document.documentElement.style.setProperty(
            '--font-size-base',
            this.value
        );

        localStorage.setItem(
            'font-size',
            this.value
        );

    });

    // =========================
    // LANGUAGE
    // =========================

    const languageSelect =
        document.getElementById('languageSelect');

    const savedLanguage =
        localStorage.getItem('language');

    if (savedLanguage) {

        languageSelect.value = savedLanguage;
    }

    languageSelect.addEventListener('change', function () {

        localStorage.setItem(
            'language',
            this.value
        );

        location.reload();

    });

</script>

@endsection