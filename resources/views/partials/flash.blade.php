@php
    $flashMessages = collect([
        ['type' => 'success', 'icon' => 'bi-check-circle-fill', 'message' => session('status') ?? session('success')],
        ['type' => 'error', 'icon' => 'bi-exclamation-triangle-fill', 'message' => session('error')],
        ['type' => 'warning', 'icon' => 'bi-exclamation-circle-fill', 'message' => session('warning')],
        ['type' => 'info', 'icon' => 'bi-info-circle-fill', 'message' => session('info')],
    ])->filter(fn ($item) => filled($item['message']));
@endphp

@if ($flashMessages->isNotEmpty() || $errors->any())
    <div class="flash-wrapper" role="status" aria-live="polite">
        @foreach ($flashMessages as $flash)
            <div class="flash-alert {{ $flash['type'] }}-alert flash-auto-hide">
                <div class="flash-content">
                    <i class="bi {{ $flash['icon'] }}"></i>
                    <span>{{ $flash['message'] }}</span>
                </div>
                <button type="button" class="flash-close" onclick="this.parentElement.remove()" aria-label="Đóng thông báo">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endforeach

        @foreach ($errors->all() as $error)
            <div class="flash-alert error-alert flash-auto-hide">
                <div class="flash-content">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>{{ $error }}</span>
                </div>
                <button type="button" class="flash-close" onclick="this.parentElement.remove()" aria-label="Đóng thông báo">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endforeach
    </div>
@endif

<style>
    .flash-wrapper {
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        margin: 0 0 18px;
    }

    .flash-alert {
        width: fit-content;
        max-width: min(720px, 100%);
        padding: 14px 18px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        animation: fadeDown 0.25s ease;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.12);
    }

    .flash-content {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.5;
    }

    .flash-content i {
        font-size: 17px;
        flex-shrink: 0;
    }

    .flash-close {
        border: none;
        background: transparent;
        color: inherit;
        cursor: pointer;
        padding: 0;
        font-size: 14px;
        opacity: 0.75;
        transition: 0.2s;
    }

    .flash-close:hover {
        opacity: 1;
        transform: scale(1.06);
    }

    .success-alert {
        background: #dcfce7;
        border: 1px solid #86efac;
        color: #166534;
    }

    .error-alert {
        background: #fee2e2;
        border: 1px solid #fca5a5;
        color: #991b1b;
    }

    .warning-alert {
        background: #fef3c7;
        border: 1px solid #fcd34d;
        color: #92400e;
    }

    .info-alert {
        background: #dbeafe;
        border: 1px solid #93c5fd;
        color: #1e40af;
    }

    @keyframes fadeDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
    setTimeout(() => {
        document.querySelectorAll('.flash-auto-hide').forEach(el => {
            el.style.transition = '0.4s';
            el.style.opacity = '0';
            el.style.transform = 'translateY(-8px)';

            setTimeout(() => el.remove(), 400);
        });
    }, 5000);
</script>
