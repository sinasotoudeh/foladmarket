<?php
/**
 * Plugin Name: Sina Custom Cart
 * Description: سیستم سبد خرید سفارشی Session-Based برای محصولات بدون ووکامرس
 * Version: 1.2.0
 * Author: Sina Sotoudeh
 * Author URI: sinasotoudeh.ir
 * Text Domain: sina-custom-cart
 * Domain Path: /languages
 * Requires at least: 5.8
 * Requires PHP: 7.4
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// جلوگیری از دسترسی مستقیم
if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

// ✅ تعریف ثابت‌های افزونه
define('SINA_CART_VERSION', '1.2.0');
define('SINA_CART_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('SINA_CART_PLUGIN_URL', plugin_dir_url(__FILE__));
define('SINA_CART_BASENAME', plugin_basename(__FILE__));

// ✅ ثابت‌های مدیریت Session
define('SINA_CART_COOKIE_NAME', 'sina_cart_session_id');
define('SINA_CART_COOKIE_EXPIRY', 365 * DAY_IN_SECONDS); // 1 سال

/**
 * ✅ مدیریت Session در اولین فرصت ممکن
 *
 * Priority: -9999 برای اطمینان از اجرای قبل از هر کلاس و هوک دیگر
 */
add_action('init', 'sina_cart_init_session', -9999);
function sina_cart_init_session() {
    // ✅ اگر Session از قبل در Cookie وجود دارد
    if (isset($_COOKIE[SINA_CART_COOKIE_NAME])) {
        $session_id = sanitize_text_field($_COOKIE[SINA_CART_COOKIE_NAME]);

        // ✅ اعتبارسنجی دقیق فرمت Session ID
        if (preg_match('/^sina_[a-f0-9]{32}$/', $session_id)) {
            define('SINA_CART_SESSION_ID', $session_id);

            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('✅ Sina Cart: Valid Session Found - ' . substr($session_id, 0, 20) . '...');
            }
            return; // ✅ از ادامه جلوگیری می‌کنیم
        } else {
            // ⚠️ Cookie نامعتبر - حذف و ساخت جدید
            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log('⚠️ Sina Cart: Invalid Session Format - Creating New Session');
            }
        }
    }

    // ✅ ایجاد Session جدید (فقط در صورت عدم وجود Session معتبر)
    $session_id = 'sina_' . md5(
        uniqid('', true) .
        ($_SERVER['REMOTE_ADDR'] ?? '') .
        ($_SERVER['HTTP_USER_AGENT'] ?? '') .
        microtime()
    );

    // ✅ تنظیم Cookie با امنیت بالا
    $cookie_options = [
        'expires'  => time() + SINA_CART_COOKIE_EXPIRY,
        'path'     => '/',
        'domain'   => '',
        'secure'   => is_ssl(),
        'httponly' => true,
        'samesite' => 'Lax'
    ];

    // ✅ برای PHP < 7.3 از روش قدیمی استفاده می‌کنیم
    if (PHP_VERSION_ID >= 70300) {
        setcookie(SINA_CART_COOKIE_NAME, $session_id, $cookie_options);
    } else {
        setcookie(
            SINA_CART_COOKIE_NAME,
            $session_id,
            $cookie_options['expires'],
            $cookie_options['path'],
            $cookie_options['domain'],
            $cookie_options['secure'],
            $cookie_options['httponly']
        );
    }

    // ✅ تعریف Constant سراسری
    define('SINA_CART_SESSION_ID', $session_id);

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('🆕 Sina Cart: New Session Created - ' . substr($session_id, 0, 20) . '...');
    }
}

/**
 * کلاس اصلی افزونه - Singleton Pattern
 */
final class Sina_Custom_Cart {

    private static $instance = null;

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->init_hooks();
    }

    private function init_hooks() {
        // هوک‌های فعال‌سازی و غیرفعال‌سازی
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        // هوک‌های اصلی وردپرس
        add_action('plugins_loaded', [$this, 'init'], 10);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
    }

    /**
     * مقداردهی اولیه افزونه
     */
    public function init() {
        // بارگذاری کلاس‌ها
        $this->load_dependencies();

        // بارگذاری فایل‌های ترجمه
        load_plugin_textdomain(
            'sina-custom-cart',
            false,
            dirname(SINA_CART_BASENAME) . '/languages'
        );

        // راه‌اندازی کلاس‌ها
        $this->init_classes();
    }

    /**
     * بارگذاری کلاس‌های وابسته با بررسی وجود فایل
     */
    private function load_dependencies() {
        $required_files = [
            'includes/class-database.php',
            'includes/class-cart.php',
            'includes/class-ajax-handler.php',
            'public/class-shortcodes.php'
        ];

        foreach ($required_files as $file) {
            $file_path = SINA_CART_PLUGIN_DIR . $file;

            if (!file_exists($file_path)) {
                error_log(sprintf(
                    '❌ Sina Cart Error: Required file missing - %s',
                    $file
                ));

                add_action('admin_notices', function() use ($file) {
                    printf(
                        '<div class="notice notice-error"><p><strong>خطای افزونه Sina Cart:</strong> فایل %s یافت نشد!</p></div>',
                        esc_html($file)
                    );
                });

                continue;
            }

            require_once $file_path;
        }
    }

    /**
     * راه‌اندازی کلاس‌ها
     */
    private function init_classes() {
        if (class_exists('Sina_Cart_Database')) {
            Sina_Cart_Database::init();
        }

        if (class_exists('Sina_Cart_Ajax_Handler')) {
            Sina_Cart_Ajax_Handler::init();
        }

        if (class_exists('Sina_Cart_Shortcodes')) {
            Sina_Cart_Shortcodes::init();
        }
    }

    /**
     * ثبت اسکریپت‌ها و استایل‌ها
     */
    public function enqueue_assets() {
        if (is_admin()) {
            return;
        }

        // CSS
        $css_path = SINA_CART_PLUGIN_DIR . 'assets/css/cart-style.css';
        if (file_exists($css_path)) {
            wp_enqueue_style(
                'sina-cart-style',
                SINA_CART_PLUGIN_URL . 'assets/css/cart-style.css',
                [],
                SINA_CART_VERSION
            );
        }

        // JavaScript
        $js_path = SINA_CART_PLUGIN_DIR . 'assets/js/cart-script.js';
        if (file_exists($js_path)) {
            wp_enqueue_script(
                'sina-cart-script',
                SINA_CART_PLUGIN_URL . 'assets/js/cart-script.js',
                ['jquery'],
                SINA_CART_VERSION,
                true
            );

            // ✅ تنظیمات AJAX با Session ID از Cookie
            wp_localize_script('sina-cart-script', 'sinaCartVars', [
                'ajax_url'     => admin_url('admin-ajax.php'),
                'nonce'        => wp_create_nonce('sina_cart_nonce'),
                'sessionId'    => defined('SINA_CART_SESSION_ID') ? SINA_CART_SESSION_ID : '',
                'cart_url'     => home_url('/price/'),
                'checkout_url' => home_url('/checkout/'),
                'debug_mode'   => defined('WP_DEBUG') && WP_DEBUG,
                'messages'     => [
                    'adding'         => __('در حال ثبت درخواست...', 'sina-custom-cart'),
                    'added'          => __('محصول به فهرست استعلام اضافه شد', 'sina-custom-cart'),
                    'error'          => __('خطا در عملیات', 'sina-custom-cart'),
                    'network_error'  => __('خطای ارتباط با سرور', 'sina-custom-cart'),
                    'removing'       => __('در حال حذف...', 'sina-custom-cart'),
                    'removed'        => __('محصول حذف شد', 'sina-custom-cart'),
                    'updating'       => __('در حال به‌روزرسانی...', 'sina-custom-cart'),
                    'updated'        => __('فهرست استعلام به‌روزرسانی شد', 'sina-custom-cart'),
                    'invalid_qty'    => __('تعداد باید عدد مثبت باشد', 'sina-custom-cart'),
                    'confirm_remove' => __('آیا از حذف این محصول اطمینان دارید؟', 'sina-custom-cart'),
                ]
            ]);
        }
    }

    /**
     * فعال‌سازی افزونه
     */
    public function activate() {
        require_once SINA_CART_PLUGIN_DIR . 'includes/class-database.php';

        // ایجاد جداول دیتابیس
        Sina_Cart_Database::create_tables();

        // ایجاد صفحات پیش‌فرض
        $this->create_default_pages();

        // ذخیره اطلاعات نصب
        update_option('sina_cart_version', SINA_CART_VERSION);
        update_option('sina_cart_activated_time', current_time('mysql'));

        flush_rewrite_rules();

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('✅ Sina Cart: Plugin activated successfully (v' . SINA_CART_VERSION . ')');
        }
    }

    /**
     * غیرفعال‌سازی افزونه
     */
    public function deactivate() {
        wp_clear_scheduled_hook('sina_cart_cleanup_cron');
        wp_cache_flush();
        flush_rewrite_rules();

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('⏸️ Sina Cart: Plugin deactivated');
        }
    }

    /**
     * ایجاد صفحات پیش‌فرض (سبد خرید و تسویه حساب)
     */
    private function create_default_pages() {
        $pages = [
            'cart' => [
                'title'   => __('سبد خرید', 'sina-custom-cart'),
                'content' => '[sina_cart_page]',
                'slug'    => 'price'
            ],
            'checkout' => [
                'title'   => __('تسویه حساب', 'sina-custom-cart'),
                'content' => '[sina_checkout_page]',
                'slug'    => 'checkout'
            ]
        ];

        foreach ($pages as $key => $page) {
            $existing_page = get_page_by_path($page['slug']);

            if (!$existing_page) {
                $page_id = wp_insert_post([
                    'post_title'     => $page['title'],
                    'post_content'   => $page['content'],
                    'post_name'      => $page['slug'],
                    'post_status'    => 'publish',
                    'post_type'      => 'page',
                    'post_author'    => 1,
                    'comment_status' => 'closed',
                    'ping_status'    => 'closed',
                ]);

                if ($page_id && !is_wp_error($page_id)) {
                    update_option("sina_cart_{$key}_page_id", $page_id);

                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        error_log(sprintf(
                            'Sina Cart: Created %s page (ID: %d)',
                            $key,
                            $page_id
                        ));
                    }
                }
            } else {
                update_option("sina_cart_{$key}_page_id", $existing_page->ID);
            }
        }
    }

    /**
     * دریافت نسخه افزونه
     */
    public static function get_version() {
        return SINA_CART_VERSION;
    }

    /**
     * بررسی سلامت افزونه
     */
    public static function health_check() {
        $status = [
            'version'     => SINA_CART_VERSION,
            'session_id'  => defined('SINA_CART_SESSION_ID') ? substr(SINA_CART_SESSION_ID, 0, 25) . '...' : 'Not Set',
            'cookie_set'  => isset($_COOKIE[SINA_CART_COOKIE_NAME]) ? 'Yes' : 'No',
            'database'    => Sina_Cart_Database::tables_exist(),
            'classes'     => [
                'database'   => class_exists('Sina_Cart_Database'),
                'cart'       => class_exists('Sina_Cart'),
                'ajax'       => class_exists('Sina_Cart_Ajax_Handler'),
                'shortcodes' => class_exists('Sina_Cart_Shortcodes'),
            ]
        ];

        return $status;
    }

    private function __clone() {}

    public function __wakeup() {
        throw new \Exception('Cannot unserialize singleton');
    }
}

/**
 * توابع کمکی (Helper Functions)
 */

/**
 * دریافت نمونه اصلی افزونه
 */
function sina_cart() {
    return Sina_Custom_Cart::get_instance();
}

/**
 * بررسی فعال بودن افزونه
 */
function sina_cart_is_active() {
    return class_exists('Sina_Custom_Cart');
}

/**
 * دریافت Session ID فعلی
 */
function sina_cart_get_session_id() {
    return defined('SINA_CART_SESSION_ID') ? SINA_CART_SESSION_ID : null;
}

// ✅ شروع افزونه
sina_cart();

/**
 * ✅ حذف کامل (Uninstall)
 *
 * فقط در صورت حذف کامل افزونه اجرا می‌شود
 */
register_uninstall_hook(__FILE__, 'sina_cart_uninstall');
function sina_cart_uninstall() {
    require_once SINA_CART_PLUGIN_DIR . 'includes/class-database.php';

    if (class_exists('Sina_Cart_Database')) {
        Sina_Cart_Database::drop_tables();
    }

    // حذف تنظیمات
    delete_option('sina_cart_version');
    delete_option('sina_cart_activated_time');
    delete_option('sina_cart_cart_page_id');
    delete_option('sina_cart_checkout_page_id');

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('🗑️ Sina Cart: Plugin data removed');
    }
}
