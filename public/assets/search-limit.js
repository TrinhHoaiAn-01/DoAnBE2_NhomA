(function () {
    const DEFAULT_LIMIT = 50;
    const DEFAULT_MESSAGE = `Từ khóa tìm kiếm chỉ được tối đa ${DEFAULT_LIMIT} ký tự. Nội dung vượt quá đã được cắt bớt.`;

    function chars(value) {
        return Array.from((value || '').normalize('NFC'));
    }

    function getLimit(input) {
        const configuredLimit = Number(input.dataset.searchLimit || DEFAULT_LIMIT);
        return Number.isFinite(configuredLimit) && configuredLimit > 0 ? configuredLimit : DEFAULT_LIMIT;
    }

    function getMessage(input, limit) {
        return input.dataset.searchLimitMessage || DEFAULT_MESSAGE.replace(String(DEFAULT_LIMIT), String(limit));
    }

    function flashTarget() {
        return document.querySelector('.main-container')
            || document.querySelector('main.page-content .container-fluid')
            || document.body;
    }

    function showInfoBar(message) {
        const target = flashTarget();
        let wrapper = document.getElementById('search-limit-flash-wrapper');

        if (!wrapper) {
            wrapper = document.createElement('div');
            wrapper.id = 'search-limit-flash-wrapper';
            wrapper.className = 'mb-3';
            target.prepend(wrapper);
        }

        wrapper.innerHTML = `
            <div class="alert alert-info d-flex align-items-center justify-content-between gap-3 py-2 px-3 mb-0" role="status" aria-live="polite">
                <span class="d-flex align-items-center gap-2">
                    <i class="bi bi-info-circle-fill"></i>
                    <span>${message}</span>
                </span>
                <button type="button" class="btn-close" aria-label="Đóng thông báo"></button>
            </div>
        `;

        wrapper.querySelector('.btn-close')?.addEventListener('click', () => wrapper.remove(), { once: true });
        window.clearTimeout(showInfoBar.timer);
        showInfoBar.timer = window.setTimeout(() => wrapper.remove(), 4500);
    }

    function enforceLimit(input, shouldNotify) {
        const limit = getLimit(input);
        const inputChars = chars(input.value);

        if (inputChars.length <= limit) {
            return false;
        }

        input.value = inputChars.slice(0, limit).join('');

        if (shouldNotify) {
            showInfoBar(getMessage(input, limit));
        }

        return true;
    }

    function bindSearchLimit(input) {
        if (input.dataset.searchLimitBound === 'true') {
            return;
        }

        input.dataset.searchLimitBound = 'true';
        enforceLimit(input, false);

        input.addEventListener('input', () => enforceLimit(input, true));
        input.form?.addEventListener('submit', () => enforceLimit(input, true));
    }

    document.addEventListener('DOMContentLoaded', () => {
        document
            .querySelectorAll('input[name="search"], input[type="search"], input[data-search-limit]')
            .forEach(bindSearchLimit);
    });
})();
