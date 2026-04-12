<?php
/**
 * کلاس مدیریت دیتابیس سبد خرید
 *
 * مسئولیت: ساخت و مدیریت جداول wp_sina_cart و wp_sina_orders
 *
 * @package Sina_Custom_Cart
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class Sina_Cart_Database {

    /**
     * نام جدول سبد خرید موقت
     *
     * @var string
     */
    private static $cart_table = 'sina_cart';

    /**
     * نام جدول سفارشات نهایی
     *
     * @var string
     */
    private static $orders_table = 'sina_orders';

    /**
     * نسخه فعلی ساختار دیتابیس
     *
     * @var string
     */
    private static $db_version = '1.0.1';

    /**
     * مقداردهی اولیه - ثبت هوک‌های لازم
     */
    public static function init() {
        // ثبت Cron Job برای پاکسازی خودکار
        if (!wp_next_scheduled('sina_cart_cleanup_cron')) {
            wp_schedule_event(time(), 'daily', 'sina_cart_cleanup_cron');
        }

        add_action('sina_cart_cleanup_cron', [__CLASS__, 'cleanup_old_cart_items']);
        
        // بررسی نسخه دیتابیس و آپدیت در صورت نیاز
        add_action('plugins_loaded', [__CLASS__, 'check_db_version'], 5);
    }

    /**
     * بررسی و آپدیت نسخه دیتابیس
     */
    public static function check_db_version() {
        $current_version = get_option('sina_cart_db_version', '0.0.0');
        
        if (version_compare($current_version, self::$db_version, '<')) {
            self::create_tables();
        }
    }

    /**
     * دریافت نام کامل جدول سبد خرید
     *
     * @return string
     */
    public static function get_cart_table() {
        global $wpdb;
        return $wpdb->prefix . self::$cart_table;
    }

    /**
     * دریافت نام کامل جدول سفارشات
     *
     * @return string
     */
    public static function get_orders_table() {
        global $wpdb;
        return $wpdb->prefix . self::$orders_table;
    }

    /**
     * ساخت جداول مورد نیاز
     *
     * این متد در هنگام فعال‌سازی افزونه اجرا می‌شود
     */
    public static function create_tables() {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();

        // =====================================================
        // جدول سبد خرید موقت (wp_sina_cart)
        // =====================================================
        $cart_table = self::get_cart_table();

        $cart_sql = "CREATE TABLE {$cart_table} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            session_id VARCHAR(255) NOT NULL,
            user_id BIGINT(20) UNSIGNED DEFAULT 0,
            post_id BIGINT(20) UNSIGNED NOT NULL,
            row_index INT(11) NOT NULL,
            product_title TEXT NOT NULL,
            product_thumbnail VARCHAR(500) DEFAULT NULL,
            product_code VARCHAR(100) DEFAULT NULL,
            product_name VARCHAR(255) DEFAULT NULL,
            product_size VARCHAR(100) DEFAULT NULL,
            product_thickness VARCHAR(100) DEFAULT NULL,
            product_grade VARCHAR(100) DEFAULT NULL,
            product_trim VARCHAR(100) DEFAULT NULL,
            product_weight VARCHAR(100) DEFAULT NULL,
            product_manufacturer VARCHAR(255) DEFAULT NULL,
            measurement_unit VARCHAR(50) DEFAULT NULL,
            loading_location VARCHAR(255) DEFAULT NULL,
            manufacture_country VARCHAR(100) DEFAULT NULL,
            additional_info_1 TEXT DEFAULT NULL,
            additional_info_2 TEXT DEFAULT NULL,
            additional_info_3 TEXT DEFAULT NULL,
            additional_info_4 TEXT DEFAULT NULL,
            additional_info_5 TEXT DEFAULT NULL,
            price DECIMAL(15,2) NOT NULL,
            quantity INT(11) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            KEY session_id (session_id),
            KEY user_id (user_id),
            KEY post_id (post_id),
            KEY post_row_index (post_id, row_index),
            KEY created_at (created_at),
            UNIQUE KEY unique_cart_item (session_id, post_id, row_index)
        ) {$charset_collate};";

        // =====================================================
        // جدول سفارشات نهایی (wp_sina_orders)
        // =====================================================
        $orders_table = self::get_orders_table();

        $orders_sql = "CREATE TABLE {$orders_table} (
            id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            order_number VARCHAR(50) NOT NULL,
            user_id BIGINT(20) UNSIGNED DEFAULT 0,
            session_id VARCHAR(255) DEFAULT NULL,
            customer_name VARCHAR(255) NOT NULL,
            customer_phone VARCHAR(50) NOT NULL,
            customer_email VARCHAR(255) DEFAULT NULL,
            customer_company VARCHAR(255) DEFAULT NULL,
            customer_address TEXT DEFAULT NULL,
            customer_notes TEXT DEFAULT NULL,
            order_items LONGTEXT NOT NULL,
            total_amount DECIMAL(15,2) NOT NULL,
            status VARCHAR(50) NOT NULL DEFAULT 'pending',
            admin_notes TEXT DEFAULT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY order_number (order_number),
            KEY user_id (user_id),
            KEY session_id (session_id),
            KEY status (status),
            KEY created_at (created_at)
        ) {$charset_collate};";

        // اجرای Query‌ها با استفاده از dbDelta
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        dbDelta($cart_sql);
        dbDelta($orders_sql);

        // ذخیره نسخه دیتابیس
        update_option('sina_cart_db_version', self::$db_version);

        // Log موفقیت‌آمیز بودن
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Sina Cart: Database tables created/updated successfully (v' . self::$db_version . ')');
        }
    }

    /**
     * پاکسازی خودکار آیتم‌های قدیمی سبد خرید
     *
     * - سبدهای مهمان (user_id=0): بعد از 30 روز
     * - سبدهای کاربران ثبت‌نام شده: بعد از 90 روز
     *
     * این متد توسط Cron Job روزانه اجرا می‌شود
     */
    public static function cleanup_old_cart_items() {
        global $wpdb;

        $table_name = self::get_cart_table();

        // حذف سبدهای مهمان بیش از 30 روز
        $guest_deleted = $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$table_name}
                WHERE user_id = 0
                AND created_at < DATE_SUB(NOW(), INTERVAL %d DAY)",
                30
            )
        );

        // حذف سبدهای کاربران ثبت‌نام شده بیش از 90 روز
        $user_deleted = $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$table_name}
                WHERE user_id > 0
                AND created_at < DATE_SUB(NOW(), INTERVAL %d DAY)",
                90
            )
        );

        // Log تعداد آیتم‌های حذف شده
        if (($guest_deleted || $user_deleted) && defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf(
                'Sina Cart Cleanup: %d guest items and %d user items deleted',
                $guest_deleted,
                $user_deleted
            ));
        }

        return [
            'guest_deleted' => $guest_deleted,
            'user_deleted'  => $user_deleted,
            'timestamp'     => current_time('mysql')
        ];
    }

    /**
     * تولید شماره سفارش یکتا
     *
     * فرمت: SINA-YYYYMMDD-XXXX
     * مثال: SINA-20251207-0001
     *
     * @return string
     */
    public static function generate_order_number() {
        global $wpdb;

        $table_name = self::get_orders_table();
        $max_attempts = 10;
        $attempt = 0;

        do {
            // تولید شماره سفارش
            $order_number = sprintf(
                'SINA-%s-%04d',
                date('Ymd'),
                wp_rand(1, 9999)
            );

            // بررسی یکتا بودن
            $exists = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$table_name} WHERE order_number = %s",
                    $order_number
                )
            );

            $attempt++;

            // جلوگیری از حلقه بی‌نهایت
            if ($attempt >= $max_attempts) {
                // افزودن timestamp برای یکتایی مطمئن
                $order_number .= '-' . time();
                break;
            }

        } while ($exists > 0);

        return $order_number;
    }

    /**
     * بررسی وجود جداول
     *
     * @return bool
     */
    public static function tables_exist() {
        global $wpdb;

        $cart_table = self::get_cart_table();
        $orders_table = self::get_orders_table();

        $cart_exists = $wpdb->get_var("SHOW TABLES LIKE '{$cart_table}'") === $cart_table;
        $orders_exists = $wpdb->get_var("SHOW TABLES LIKE '{$orders_table}'") === $orders_table;

        return $cart_exists && $orders_exists;
    }

    /**
     * دریافت آمار دیتابیس
     *
     * @return array
     */
    public static function get_stats() {
        global $wpdb;

        $cart_table = self::get_cart_table();
        $orders_table = self::get_orders_table();

        return [
            'total_cart_items'   => (int) $wpdb->get_var("SELECT COUNT(*) FROM {$cart_table}"),
            'active_sessions'    => (int) $wpdb->get_var("SELECT COUNT(DISTINCT session_id) FROM {$cart_table}"),
            'active_users'       => (int) $wpdb->get_var("SELECT COUNT(DISTINCT user_id) FROM {$cart_table} WHERE user_id > 0"),
            'total_orders'       => (int) $wpdb->get_var("SELECT COUNT(*) FROM {$orders_table}"),
            'pending_orders'     => (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$orders_table} WHERE status = %s",
                    'pending'
                )
            ),
            'completed_orders'   => (int) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$orders_table} WHERE status = %s",
                    'completed'
                )
            ),
            'total_revenue'      => (float) $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COALESCE(SUM(total_amount), 0) FROM {$orders_table} WHERE status = %s",
                    'completed'
                )
            ),
            'db_version'         => get_option('sina_cart_db_version', '0.0.0')
        ];
    }

    /**
     * حذف کامل جداول (برای uninstall)
     *
     * ⚠️ این عملیات برگشت‌ناپذیر است
     */
    public static function drop_tables() {
        global $wpdb;

        $cart_table = self::get_cart_table();
        $orders_table = self::get_orders_table();

        // حذف Cron Job
        wp_clear_scheduled_hook('sina_cart_cleanup_cron');

        // حذف جداول
        $wpdb->query("DROP TABLE IF EXISTS {$cart_table}");
        $wpdb->query("DROP TABLE IF EXISTS {$orders_table}");

        // حذف تنظیمات
        delete_option('sina_cart_db_version');
        delete_option('sina_cart_version');

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Sina Cart: Database tables dropped successfully');
        }
    }

    /**
     * بازسازی جداول (برای troubleshooting)
     */
    public static function rebuild_tables() {
        self::drop_tables();
        self::create_tables();

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Sina Cart: Database tables rebuilt successfully');
        }
    }

    /**
     * دریافت نسخه دیتابیس
     *
     * @return string
     */
    public static function get_db_version() {
        return self::$db_version;
    }
}
