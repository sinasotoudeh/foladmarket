<?php
/**
 * کلاس مدیریت سبد خرید - Session-Based (بدون کش و بدون Fallback)
 *
 * @package Sina_Custom_Cart
 * @version 1.2.0
 */

if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class Sina_Cart {

    private static $instance = null;
    private $table_name;
    private $session_id;

    /**
     * دریافت نمونه یکتای کلاس (Singleton)
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        global $wpdb;

        $this->table_name = $wpdb->prefix . 'sina_cart';

        // ✅ استفاده مستقیم از Cookie (بدون Fallback)
        $this->session_id = $this->get_session_from_cookie();

        // ✅ لاگ برای Debug
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf(
                '🛒 Sina Cart Instance - Session: %s | Source: %s',
                substr($this->session_id, 0, 20) . '...',
                defined('SINA_CART_SESSION_ID') ? 'Constant' : 'Cookie'
            ));
        }
    }

    /**
     * ✅ دریافت Session ID از Cookie (بدون Fallback)
     *
     * @return string Session ID معتبر
     */
    private function get_session_from_cookie() {
        // ✅ اولویت اول: Constant از init
        if (defined('SINA_CART_SESSION_ID')) {
            return SINA_CART_SESSION_ID;
        }

        // ✅ اولویت دوم: خواندن مستقیم از Cookie
        if (isset($_COOKIE[SINA_CART_COOKIE_NAME])) {
            $session_id = sanitize_text_field($_COOKIE[SINA_CART_COOKIE_NAME]);

            // اعتبارسنجی فرمت
            if (preg_match('/^sina_[a-f0-9]{32}$/', $session_id)) {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('✅ Cart: Session from Cookie - ' . substr($session_id, 0, 20));
                }
                return $session_id;
            } else {
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('⚠️ Cart: Invalid Session Format in Cookie');
                }
            }
        }

        // 🔴 CRITICAL: اگر Session وجود ندارد، خطا بده
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('❌ CRITICAL: No valid session available in Cart constructor');
        }

        // ✅ بازگشت به یک session خالی (برای جلوگیری از Fatal Error)
        // این session ذخیره نمی‌شود و فقط برای جلوگیری از کرش است
        return 'sina_empty_session_' . md5(microtime());
    }

    /**
     * افزودن محصول به سبد (بدون کش)
     */
    public function add_item($item_data) {
        global $wpdb;

        // اعتبارسنجی
        if (empty($item_data['post_id']) || !isset($item_data['row_index'])) {
            error_log('❌ Sina Cart: Missing required fields');
            return false;
        }

        $post_id   = absint($item_data['post_id']);
        $row_index = absint($item_data['row_index']);
        $quantity  = absint($item_data['quantity'] ?? 1);
        $price     = floatval($item_data['price'] ?? 0);

        if ($quantity < 1 || $price < 0) {
            error_log('❌ Sina Cart: Invalid quantity or price');
            return false;
        }

        // بررسی وجود محصول
        $existing_item = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT id, quantity FROM {$this->table_name}
                WHERE session_id = %s AND post_id = %d AND row_index = %d",
                $this->session_id,
                $post_id,
                $row_index
            )
        );

        // اگر قبلاً وجود دارد، فقط تعداد را افزایش بده
        if ($existing_item) {
            $new_quantity = $existing_item->quantity + $quantity;

            $updated = $wpdb->update(
                $this->table_name,
                [
                    'quantity' => $new_quantity,
                    'updated_at' => current_time('mysql')
                ],
                ['id' => $existing_item->id],
                ['%d', '%s'],
                ['%d']
            );

            if (defined('WP_DEBUG') && WP_DEBUG) {
                error_log(sprintf(
                    '🔄 Item Updated - ID: %d | Qty: %d | Session: %s',
                    $existing_item->id,
                    $new_quantity,
                    substr($this->session_id, 0, 20)
                ));
            }

            do_action('sina_cart_item_updated', $existing_item->id, $new_quantity);

            return $updated !== false ? $existing_item->id : false;
        }

        // درج محصول جدید
        $insert_data = [
            'session_id'           => $this->session_id,
            'user_id'              => get_current_user_id(),
            'post_id'              => $post_id,
            'row_index'            => $row_index,
            'product_title'        => sanitize_text_field($item_data['product_title'] ?? ''),
            'product_thumbnail'    => esc_url_raw($item_data['product_thumbnail'] ?? ''),
            'product_code'         => sanitize_text_field($item_data['product_code'] ?? ''),
            'product_name'         => sanitize_text_field($item_data['product_name'] ?? ''),
            'product_size'         => sanitize_text_field($item_data['product_size'] ?? ''),
            'product_thickness'    => sanitize_text_field($item_data['product_thickness'] ?? ''),
            'product_grade'        => sanitize_text_field($item_data['product_grade'] ?? ''),
            'product_trim'         => sanitize_text_field($item_data['product_trim'] ?? ''),
            'product_weight'       => sanitize_text_field($item_data['product_weight'] ?? ''),
            'product_manufacturer' => sanitize_text_field($item_data['product_manufacturer'] ?? ''),
            'measurement_unit'     => sanitize_text_field($item_data['measurement_unit'] ?? ''),
            'loading_location'     => sanitize_text_field($item_data['loading_location'] ?? ''),
            'manufacture_country'  => sanitize_text_field($item_data['manufacture_country'] ?? ''),
            'additional_info_1'    => sanitize_textarea_field($item_data['additional_info_1'] ?? ''),
            'additional_info_2'    => sanitize_textarea_field($item_data['additional_info_2'] ?? ''),
            'additional_info_3'    => sanitize_textarea_field($item_data['additional_info_3'] ?? ''),
            'additional_info_4'    => sanitize_textarea_field($item_data['additional_info_4'] ?? ''),
            'additional_info_5'    => sanitize_textarea_field($item_data['additional_info_5'] ?? ''),
            'price'                => $price,
            'quantity'             => $quantity,
            'created_at'           => current_time('mysql'),
        ];

        $result = $wpdb->insert($this->table_name, $insert_data);

        if ($result === false) {
            error_log('❌ Sina Cart: Insert Failed - ' . $wpdb->last_error);
            return false;
        }

        $insert_id = $wpdb->insert_id;

        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log(sprintf(
                '✅ Item Added - ID: %d | Post: %d | Row: %d | Session: %s',
                $insert_id,
                $post_id,
                $row_index,
                substr($this->session_id, 0, 20)
            ));
        }

        do_action('sina_cart_item_added', $insert_id, $insert_data);

        return $insert_id;
    }

    /**
     * دریافت آیتم‌های سبد (بدون کش - مستقیم از دیتابیس)
     */
    public function get_items() {
        global $wpdb;

        $items = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM {$this->table_name}
                WHERE session_id = %s
                ORDER BY created_at DESC",
                $this->session_id
            ),
            ARRAY_A
        );

        return $items ?: [];
    }

    /**
     * دریافت یک آیتم خاص
     */
    public function get_item($item_id) {
        global $wpdb;

        return $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table_name}
                WHERE id = %d AND session_id = %s",
                absint($item_id),
                $this->session_id
            ),
            ARRAY_A
        );
    }

    /**
     * به‌روزرسانی تعداد
     */
    public function update_quantity($item_id, $quantity) {
        global $wpdb;

        $quantity = absint($quantity);

        // اگر تعداد 0 یا منفی شد، حذف کن
        if ($quantity < 1) {
            return $this->remove_item($item_id);
        }

        $result = $wpdb->update(
            $this->table_name,
            [
                'quantity' => $quantity,
                'updated_at' => current_time('mysql')
            ],
            [
                'id' => absint($item_id),
                'session_id' => $this->session_id
            ],
            ['%d', '%s'],
            ['%d', '%s']
        );

        if ($result !== false) {
            do_action('sina_cart_quantity_updated', $item_id, $quantity);
        }

        return $result !== false;
    }

    /**
     * حذف محصول
     */
    public function remove_item($item_id) {
        global $wpdb;

        $result = $wpdb->delete(
            $this->table_name,
            [
                'id' => absint($item_id),
                'session_id' => $this->session_id
            ],
            ['%d', '%s']
        );

        if ($result !== false) {
            do_action('sina_cart_item_removed', $item_id);
        }

        return $result !== false;
    }

    /**
     * خالی کردن سبد
     */
    public function clear_cart() {
        global $wpdb;

        $result = $wpdb->delete(
            $this->table_name,
            ['session_id' => $this->session_id],
            ['%s']
        );

        if ($result !== false) {
            do_action('sina_cart_cleared', $this->session_id);
        }

        return $result !== false;
    }

    /**
     * تعداد کل آیتم‌ها (مجموع quantity)
     */
    public function get_item_count() {
        global $wpdb;

        return absint($wpdb->get_var(
            $wpdb->prepare(
                "SELECT COALESCE(SUM(quantity), 0) FROM {$this->table_name} WHERE session_id = %s",
                $this->session_id
            )
        ));
    }

    /**
     * تعداد محصولات منحصر به فرد
     */
    public function get_unique_item_count() {
        global $wpdb;

        return absint($wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM {$this->table_name} WHERE session_id = %s",
                $this->session_id
            )
        ));
    }

    /**
     * محاسبه جمع کل
     */
    public function get_total() {
        global $wpdb;

        return floatval($wpdb->get_var(
            $wpdb->prepare(
                "SELECT COALESCE(SUM(price * quantity), 0) FROM {$this->table_name} WHERE session_id = %s",
                $this->session_id
            )
        ));
    }

    /**
     * بررسی خالی بودن سبد
     */
    public function is_empty() {
        return $this->get_item_count() === 0;
    }

    /**
     * دریافت Session ID
     */
    public function get_session_id() {
        return $this->session_id;
    }
/**
 * ایجاد سفارش نهایی (در انتهای کلاس Sina_Cart قبل از __clone)
 */
public function create_order($customer_data) {
    global $wpdb;

    // اعتبارسنجی داده‌های مشتری
    if (empty($customer_data['customer_name']) || empty($customer_data['customer_phone'])) {
        return [
            'success' => false,
            'message' => 'نام و شماره تماس الزامی است'
        ];
    }

    // بررسی خالی نبودن سبد
    $items = $this->get_items();
    if (empty($items)) {
        return [
            'success' => false,
            'message' => 'سبد خرید خالی است'
        ];
    }

    $total = $this->get_total();

    // تولید شماره سفارش یکتا
    if (!class_exists('Sina_Cart_Database')) {
        return [
            'success' => false,
            'message' => 'خطای سیستمی'
        ];
    }

    $order_number = Sina_Cart_Database::generate_order_number();
    $orders_table = Sina_Cart_Database::get_orders_table();

    // آماده‌سازی داده‌های سفارش
    $order_data = [
        'order_number'     => $order_number,
        'user_id'          => get_current_user_id(),
        'session_id'       => $this->session_id,
        'customer_name'    => sanitize_text_field($customer_data['customer_name']),
        'customer_phone'   => sanitize_text_field($customer_data['customer_phone']),
        'customer_email'   => sanitize_email($customer_data['customer_email'] ?? ''),
        'customer_company' => sanitize_text_field($customer_data['customer_company'] ?? ''),
        'customer_address' => sanitize_textarea_field($customer_data['customer_address'] ?? ''),
        'customer_notes'   => sanitize_textarea_field($customer_data['customer_notes'] ?? ''),
        'order_items'      => wp_json_encode($items, JSON_UNESCAPED_UNICODE),
        'total_amount'     => $total,
        'status'           => 'pending',
        'created_at'       => current_time('mysql'),
    ];

    // درج سفارش
$result = $wpdb->insert(
    $orders_table,
    $order_data,
    [
        '%s', // order_number
        '%d', // user_id
        '%s', // session_id
        '%s', // customer_name
        '%s', // customer_phone
        '%s', // customer_email
        '%s', // customer_company
        '%s', // customer_address
        '%s', // customer_notes
        '%s', // order_items
        '%f', // total_amount
        '%s', // status
        '%s', // created_at
    ]
);


    if ($result === false) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('❌ Sina Cart: Order Insert Failed - ' . $wpdb->last_error);
        }
        return [
            'success' => false,
            'message' => 'خطا در ثبت سفارش'
        ];
    }

    $order_id = $wpdb->insert_id;

    // ✅ خالی کردن سبد خرید
    $this->clear_cart();

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log(sprintf(
            '✅ Order Created - ID: %d | Number: %s | Total: %s | Session: %s',
            $order_id,
            $order_number,
            $total,
            substr($this->session_id, 0, 20)
        ));
    }

    // اجرای Hook برای اقدامات بعدی (ایمیل، SMS و...)
    do_action('sina_cart_order_created', $order_id, $order_data);

    return [
        'success'      => true,
        'message'      => 'سفارش شما با موفقیت ثبت شد',
        'order_id'     => $order_id,
        'order_number' => $order_number,
    ];
}

    private function __clone() {}

    public function __wakeup() {
        throw new \Exception('Cannot unserialize singleton');
    }
}
