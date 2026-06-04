(function () {
    const DEFAULTS = {
        darkMode: false,
        fontSize: '16px',
        language: 'vi',
    };

    const TEXT_TRANSLATIONS = {
        'Trang chủ': 'Home',
        'Sản phẩm': 'Products',
        'Danh sách sản phẩm': 'Product list',
        'Giỏ hàng': 'Cart',
        'Xem giỏ hàng': 'View cart',
        'Tiến hành thanh toán': 'Checkout',
        'Hồ sơ người dùng': 'User profile',
        'Đăng xuất': 'Log out',
        'Đăng nhập': 'Log in',
        'Đăng ký': 'Register',
        'Cài đặt': 'Settings',
        'Cài đặt hệ thống': 'Website settings',
        'Tùy chỉnh giao diện và trải nghiệm người dùng': 'Customize your website display and browsing experience',
        'Giao diện': 'Theme',
        'Chọn giao diện sáng hoặc tối cho toàn bộ website': 'Choose light or dark mode for the whole website',
        'Sáng': 'Light',
        'Tối': 'Dark',
        'Kích thước chữ': 'Font size',
        'Thay đổi kích thước chữ trên toàn bộ website': 'Change the text size across the whole website',
        'Nhỏ': 'Small',
        'Vừa': 'Medium',
        'Lớn': 'Large',
        'Rất lớn': 'Very large',
        'Ngôn ngữ': 'Language',
        'Đổi ngôn ngữ hiển thị': 'Change display language',
        'Tiếng Việt': 'Vietnamese',
        'Lưu tự động': 'Auto-saved',
        'Các thay đổi được lưu trên trình duyệt của bạn và áp dụng cho mọi trang.': 'Changes are saved in your browser and applied to every page.',
        'Đi chợ Online - Giao hàng siêu tốc': 'Online grocery - Fast delivery',
        'Thịt rau tươi sống': 'Fresh groceries',
        'Giá rẻ mỗi ngày': 'Low prices every day',
        'Hàng ngàn mặt hàng tươi sống, nhu yếu phẩm chất lượng. Giao hàng siêu tốc trong 2 giờ. Mua sắm dễ dàng, an tâm tuyệt đối cùng NeoMart.': 'Thousands of fresh groceries and daily essentials. Fast delivery within 2 hours. Shop easily and confidently with NeoMart.',
        'Mua sắm ngay': 'Shop now',
        'Khuyến mãi hot': 'Hot deals',
        'SẢN PHẨM': 'PRODUCTS',
        'KHÁCH HÀNG': 'CUSTOMERS',
        'ĐÁNH GIÁ': 'RATING',
        'GIAO HÀNG': 'DELIVERY',
        'FLASH SALE': 'FLASH SALE',
        'Kết thúc sau:': 'Ends in:',
        'Xem tất cả': 'View all',
        'Sản phẩm gợi ý cho bạn': 'Recommended products',
        'Danh mục sản phẩm': 'Product categories',
        'Mới nhất': 'Newest',
        'Mới': 'New',
        'Mua ngay': 'Buy now',
        'Khám phá ngay': 'Explore now',
        'THÊM VÀO GIỎ': 'ADD TO CART',
        'HẾT HÀNG': 'OUT OF STOCK',
        'Hết hàng': 'Out of stock',
        'Còn hàng': 'In stock',
        'Sắp hết hàng': 'Low stock',
        'Yêu thích': 'Wishlist',
        'Giao hàng 2 giờ': '2-hour delivery',
        'Nội thành TP.HCM & Hà Nội': 'Inner HCMC & Hanoi',
        'Bảo hành chính hãng': 'Official warranty',
        'Đổi trả trong 30 ngày': '30-day returns',
        'Giá tốt nhất': 'Best price',
        'Cam kết hoàn tiền chênh lệch': 'Price difference refund',
        'Hỗ trợ 24/7': '24/7 support',
        'Tư vấn miễn phí mọi lúc': 'Free advice anytime',
        'Hỗ trợ': 'Support',
        'Hướng dẫn mua hàng': 'Buying guide',
        'Chính sách bảo hành': 'Warranty policy',
        'Vận chuyển & Đổi hàng': 'Shipping & returns',
        'Hỏi đáp (FAQ)': 'FAQ',
        'Liên hệ chúng tôi': 'Contact us',
        'Liên hệ': 'Contact',
        'Hỗ trợ 24/7 - Thứ 2 đến Chủ nhật': '24/7 support - Monday to Sunday',
        'Giỏ hàng của bạn': 'Your cart',
        'Sản phẩm trong giỏ': 'Cart items',
        'Tạm tính': 'Subtotal',
        'Tổng tiền': 'Total',
        'Thanh toán': 'Checkout',
        'Tiếp tục mua sắm': 'Continue shopping',
        'Giỏ hàng trống': 'Your cart is empty',
        'Xóa': 'Remove',
        'Cập nhật': 'Update',
        'Đã bao gồm VAT nếu có': 'VAT included where applicable',
        'Đặt hàng': 'Place order',
        'Thông tin giao hàng': 'Shipping information',
        'Họ và tên': 'Full name',
        'Số điện thoại': 'Phone number',
        'Địa chỉ': 'Address',
        'Phương thức thanh toán': 'Payment method',
        'Xác nhận đặt hàng': 'Confirm order',
        'Đặt hàng thành công': 'Order placed successfully',
        'Lịch sử đơn hàng': 'Order history',
        'Theo dõi đơn hàng': 'Track order',
        'Tìm kiếm sản phẩm': 'Search products',
        'Tất cả': 'All',
        'Sắp xếp': 'Sort',
        'Giá tăng dần': 'Price: low to high',
        'Giá giảm dần': 'Price: high to low',
        'Không tìm thấy sản phẩm': 'No products found',
        'Mô tả sản phẩm': 'Product description',
        'Thông số kỹ thuật': 'Specifications',
        'Đánh giá khách hàng': 'Customer reviews',
        'Sản phẩm liên quan': 'Related products',
        'Sản phẩm đã xem': 'Recently viewed products',
        'Số lượng': 'Quantity',
        'Thêm vào giỏ': 'Add to cart',
        'Đăng nhập NeoMart': 'NeoMart login',
        'Đăng ký NeoMart': 'NeoMart register',
        'Quên mật khẩu': 'Forgot password',
        'Mật khẩu': 'Password',
        'Nhớ mật khẩu': 'Remember password',
        'Tạo tài khoản': 'Create account',
        'Đã có tài khoản?': 'Already have an account?',
        'Chưa có tài khoản?': 'Need an account?',
        'Hồ sơ': 'Profile',
        'Nhật ký hoạt động': 'Activity log',
        'Đổi mật khẩu': 'Change password',
        'Thông tin cá nhân': 'Personal information',
        'Cập nhật hồ sơ': 'Update profile',
        'Quay lại': 'Back',
        'Đóng': 'Close',
        'Đã hiểu': 'Got it',
    };

    const PLACEHOLDER_TRANSLATIONS = {
        'Bạn tìm gì...': 'What are you looking for...',
        'Bạn tìm gì hôm nay...': 'What are you looking for today...',
        'Tìm kiếm sản phẩm...': 'Search products...',
        'Nhập email của bạn': 'Enter your email',
        'Nhập mật khẩu': 'Enter password',
        'Nhập họ và tên': 'Enter full name',
        'Nhập số điện thoại': 'Enter phone number',
        'Nhập địa chỉ': 'Enter address',
    };

    const PREFIX_TRANSLATIONS = [
        ['Mới nhất:', 'Newest:'],
        ['Chỉ còn', 'Only'],
        ['sản phẩm cuối cùng trong kho.', 'items left in stock.'],
    ];

    function normalizeLanguage(language) {
        return language === 'en' ? 'en' : 'vi';
    }

    function getPreferences() {
        const savedLanguage = normalizeLanguage(localStorage.getItem('language') || DEFAULTS.language);

        if (localStorage.getItem('language') === 'jp') {
            localStorage.setItem('language', DEFAULTS.language);
        }

        return {
            darkMode: localStorage.getItem('dark-mode') === 'true',
            fontSize: localStorage.getItem('font-size') || DEFAULTS.fontSize,
            language: savedLanguage,
        };
    }

    function applyTheme(preferences) {
        const root = document.documentElement;
        root.dataset.theme = preferences.darkMode ? 'dark' : 'light';
        root.style.setProperty('--font-size-base', preferences.fontSize);
        root.lang = preferences.language;

        if (document.body) {
            document.body.classList.toggle('dark-mode', preferences.darkMode);
        }
    }

    function translateTextNode(node) {
        const original = node.nodeValue;
        const trimmed = original.replace(/\s+/g, ' ').trim();
        if (!trimmed) {
            return;
        }

        let translated = TEXT_TRANSLATIONS[trimmed];

        if (!translated) {
            for (const [from, to] of PREFIX_TRANSLATIONS) {
                if (trimmed.startsWith(from)) {
                    translated = trimmed.replace(from, to);
                    break;
                }
            }
        }

        if (!translated) {
            return;
        }

        node.nodeValue = original.replace(trimmed, translated);
    }

    function translateAttributes(element) {
        ['placeholder', 'title', 'aria-label', 'value'].forEach((attribute) => {
            const value = element.getAttribute(attribute);
            if (!value) {
                return;
            }

            const trimmed = value.replace(/\s+/g, ' ').trim();
            const translated = PLACEHOLDER_TRANSLATIONS[trimmed] || TEXT_TRANSLATIONS[trimmed];
            if (translated) {
                element.setAttribute(attribute, value.replace(trimmed, translated));
            }
        });
    }

    function applyEnglishTranslations() {
        const excluded = new Set(['SCRIPT', 'STYLE', 'NOSCRIPT', 'TEXTAREA']);
        const walker = document.createTreeWalker(document.body, NodeFilter.SHOW_TEXT, {
            acceptNode(node) {
                const parent = node.parentElement;
                return parent && !excluded.has(parent.tagName)
                    ? NodeFilter.FILTER_ACCEPT
                    : NodeFilter.FILTER_REJECT;
            },
        });

        const nodes = [];
        while (walker.nextNode()) {
            nodes.push(walker.currentNode);
        }

        nodes.forEach(translateTextNode);
        document.querySelectorAll('[placeholder], [title], [aria-label], input[value], button[value]').forEach(translateAttributes);
    }

    function applyPreferences() {
        const preferences = getPreferences();
        applyTheme(preferences);

        if (preferences.language === 'en' && document.body) {
            applyEnglishTranslations();
        }

        window.dispatchEvent(new CustomEvent('neomart:preferences-applied', { detail: preferences }));
        return preferences;
    }

    function setPreference(key, value) {
        if (key === 'darkMode') {
            localStorage.setItem('dark-mode', value ? 'true' : 'false');
        }

        if (key === 'fontSize') {
            localStorage.setItem('font-size', value);
        }

        if (key === 'language') {
            localStorage.setItem('language', normalizeLanguage(value));
        }

        return applyPreferences();
    }

    const defaultFontStyle = document.createElement('style');
    defaultFontStyle.textContent = 'body, input, button, select, textarea { font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }';
    document.head.appendChild(defaultFontStyle);

    window.NeoMartPreferences = {
        getPreferences,
        setPreference,
        applyPreferences,
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyPreferences);
    } else {
        applyPreferences();
    }
})();
