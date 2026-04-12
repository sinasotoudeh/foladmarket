<?php
/**
 * کلاس مدیریت AJAX - با Enforce Session از Cookie
 *
 * @package Sina_Custom_Cart
 * @version 1.2.0
 */

if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class Sina_Cart_Ajax_Handler {

    public static function init() {
        // ✅ Enforce Session قبل از هر AJAX
        add_action('wp_ajax_sina_add_to_cart', [__CLASS__, 'enforce_session_before_ajax'], 5);
        add_action('wp_ajax_nopriv_sina_add_to_cart', [__CLASS__, 'enforce_session_before_ajax'], 5);

        add_action('wp_ajax_sina_remove_cart_item', [__CLASS__, 'enforce_session_before_ajax'], 5);
        add_action('wp_ajax_nopriv_sina_remove_cart_item', [__CLASS__, 'enforce_session_before_ajax'], 5);

        add_action('wp_ajax_sina_update_cart_quantity', [__CLASS__, 'enforce_session_before_ajax'], 5);
        add_action('wp_ajax_nopriv_sina_update_cart_quantity', [__CLASS__, 'enforce_session_before_ajax'], 5);

        add_action('wp_ajax_sina_clear_cart', [__CLASS__, 'enforce_session_before_ajax'], 5);
        add_action('wp_ajax_nopriv_sina_clear_cart', [__CLASS__, 'enforce_session_before_ajax'], 5);

        add_action('wp_ajax_sina_get_cart_info', [__CLASS__, 'enforce_session_before_ajax'], 5);
        add_action('wp_ajax_nopriv_sina_get_cart_info', [__CLASS__, 'enforce_session_before_ajax'], 5);

        add_action('wp_ajax_sina_submit_order', [__CLASS__, 'enforce_session_before_ajax'], 5);
        add_action('wp_ajax_nopriv_sina_submit_order', [__CLASS__, 'enforce_session_before_ajax'], 5);

        // ✅ AJAX Handlers
        add_action('wp_ajax_sina_add_to_cart', [__CLASS__, 'add_to_cart'], 10);
        add_action('wp_ajax_nopriv_sina_add_to_cart', [__CLASS__, 'add_to_cart'], 10);

        add_action('wp_ajax_sina_remove_cart_item', [__CLASS__, 'remove_item'], 10);
        add_action('wp_ajax_nopriv_sina_remove_cart_item', [__CLASS__, 'remove_item'], 10);

        add_action('wp_ajax_sina_update_cart_quantity', [__CLASS__, 'update_quantity'], 10);
        add_action('wp_ajax_nopriv_sina_update_cart_quantity', [__CLASS__, 'update_quantity'], 10);

        add_action('wp_ajax_sina_clear_cart', [__CLASS__, 'clear_cart'], 10);
        add_action('wp_ajax_nopriv_sina_clear_cart', [__CLASS__, 'clear_cart'], 10);

        add_action('wp_ajax_sina_get_cart_info', [__CLASS__, 'get_cart_info'], 10);
        add_action('wp_ajax_nopriv_sina_get_cart_info', [__CLASS__, 'get_cart_info'], 10);
        // ✅ Checkout Handler

        add_action('wp_ajax_sina_submit_order', [__CLASS__, 'submit_order'], 10);
        add_action('wp_ajax_nopriv_sina_submit_order', [__CLASS__, 'submit_order'], 10);

    }

    /**
     * ✅ Enforce Session از Cookie قبل از هر AJAX
     */
    public static function enforce_session_before_ajax() {
        // اگر Constant تعریف نشده
        if (!defined('SINA_CART_SESSION_ID')) {
            // خواندن مستقیم از Cookie
            if (isset($_COOKIE[SINA_CART_COOKIE_NAME])) {
                $session_id = sanitize_text_field($_COOKIE[SINA_CART_COOKIE_NAME]);

                // اعتبارسنجی فرمت
                if (preg_match('/^sina_[a-f0-9]{32}$/', $session_id)) {
                    define('SINA_CART_SESSION_ID', $session_id);

                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        error_log('✅ AJAX: Session Enforced from Cookie - ' . substr($session_id, 0, 20));
                    }
                } else {
                    if (defined('WP_DEBUG') && WP_DEBUG) {
                        error_log('⚠️ AJAX: Invalid Session Format in Cookie');
                    }
                }
            } else {
                // 🔴 CRITICAL: هیچ Session موجود نیست
                if (defined('WP_DEBUG') && WP_DEBUG) {
                    error_log('❌ AJAX: No Session Cookie Found');
                }
            }
        }
    }

    /**
     * افزودن به سبد - با فرمت Response استاندارد
     */
    public static function add_to_cart() {
        if (!check_ajax_referer('sina_cart_nonce', 'nonce', false)) {
            wp_send_json_error(['message' => 'خطای امنیتی'], 403);
        }

        $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
        $row_index = isset($_POST['row_index']) ? absint($_POST['row_index']) : 0;
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

        if (!$post_id || $quantity < 1) {
            wp_send_json_error(['message' => 'داده‌های نامعتبر']);
        }

        if (!function_exists('get_field')) {
            wp_send_json_error(['message' => 'ACF فعال نیست']);
        }

        $rows = get_field('product_rows', $post_id);

        if (empty($rows) || !isset($rows[$row_index])) {
            wp_send_json_error(['message' => 'محصول یافت نشد']);
        }

        $row = $rows[$row_index];
        $price = floatval($row['product_price'] ?? 0);

        if ($price <= 0) {
            wp_send_json_error(['message' => 'قیمت نامعتبر']);
        }

        $cart_item = [
            'post_id'              => $post_id,
            'row_index'            => $row_index,
            'product_title'        => get_the_title($post_id),
            'product_thumbnail'    => get_the_post_thumbnail_url($post_id, 'thumbnail') ?: '',
            'product_code'         => sanitize_text_field($row['product_code'] ?? ''),
            'product_name'         => sanitize_text_field($row['product_name'] ?? ''),
            'product_size'         => sanitize_text_field($row['product_size'] ?? ''),
            'product_thickness'    => sanitize_text_field($row['product_thickness'] ?? ''),
            'product_grade'        => sanitize_text_field($row['product_grade'] ?? ''),
            'product_trim'         => sanitize_text_field($row['product_trim'] ?? ''),
            'product_weight'       => sanitize_text_field($row['product_weight'] ?? ''),
            'product_manufacturer' => sanitize_text_field($row['product_manufacturer'] ?? ''),
            'measurement_unit'     => sanitize_text_field($row['measurement_unit'] ?? ''),
            'loading_location'     => sanitize_text_field($row['loading_location'] ?? ''),
            'manufacture_country'  => sanitize_text_field($row['manufacture_country'] ?? ''),
            'price'                => $price,
            'quantity'             => $quantity,
        ];

        $cart = Sina_Cart::get_instance();
        $result = $cart->add_item($cart_item);

        if ($result) {
            // ✅ فرمت Response استاندارد
            wp_send_json_success([
                'message' => 'محصول به سبد اضافه شد',
                'cart_count' => $cart->get_item_count(),
                'cart_total' => $cart->get_total(),
                'cart_total_formatted' => number_format($cart->get_total(), 0, '', ',') . ' تومان',
                'unique_count' => $cart->get_unique_item_count(),
            ]);
        } else {
            wp_send_json_error(['message' => 'خطا در افزودن محصول']);
        }
    }

    /**
     * حذف محصول - با اطلاعات کامل
     */
    public static function remove_item() {
        if (!check_ajax_referer('sina_cart_nonce', 'nonce', false)) {
            wp_send_json_error(['message' => 'خطای امنیتی'], 403);
        }

        $item_id = isset($_POST['item_id']) ? absint($_POST['item_id']) : 0;

        if (!$item_id) {
            wp_send_json_error(['message' => 'شناسه نامعتبر']);
        }

        $cart = Sina_Cart::get_instance();
        $result = $cart->remove_item($item_id);

        if ($result) {
            // ✅ افزودن اطلاعات سبد
            wp_send_json_success([
                'message' => 'محصول حذف شد',
                'cart_count' => $cart->get_item_count(),
                'cart_total' => $cart->get_total(),
                'cart_total_formatted' => number_format($cart->get_total(), 0, '', ',') . ' تومان',
                'is_empty' => $cart->is_empty(),
            ]);
        } else {
            wp_send_json_error(['message' => 'خطا در حذف محصول']);
        }
    }

    /**
     * به‌روزرسانی تعداد - با قیمت آیتم
     */
    public static function update_quantity() {
        if (!check_ajax_referer('sina_cart_nonce', 'nonce', false)) {
            wp_send_json_error(['message' => 'خطای امنیتی'], 403);
        }

        $item_id = isset($_POST['item_id']) ? absint($_POST['item_id']) : 0;
        $quantity = isset($_POST['quantity']) ? absint($_POST['quantity']) : 1;

        if (!$item_id || $quantity < 0) {
            wp_send_json_error(['message' => 'داده‌های نامعتبر']);
        }

        $cart = Sina_Cart::get_instance();
        $result = $cart->update_quantity($item_id, $quantity);

        if ($result) {
            // ✅ محاسبه قیمت آیتم
            $item = $cart->get_item($item_id);
            $item_total = $item ? ($item['price'] * $item['quantity']) : 0;

            wp_send_json_success([
                'message' => 'تعداد به‌روز شد',
                'cart_count' => $cart->get_item_count(),
                'cart_total' => $cart->get_total(),
                'cart_total_formatted' => number_format($cart->get_total(), 0, '', ',') . ' تومان',
                'item_total_formatted' => number_format($item_total, 0, '', ',') . ' تومان',
            ]);
        } else {
            wp_send_json_error(['message' => 'خطا در به‌روزرسانی']);
        }
    }

    /**
     * خالی کردن سبد
     */
    public static function clear_cart() {
        if (!check_ajax_referer('sina_cart_nonce', 'nonce', false)) {
            wp_send_json_error(['message' => 'خطای امنیتی'], 403);
        }

        $cart = Sina_Cart::get_instance();
        $result = $cart->clear_cart();

        if ($result) {
            wp_send_json_success([
                'message' => 'سبد خالی شد',
                'cart_count' => 0,
                'cart_total' => 0,
                'cart_total_formatted' => '0 تومان',
            ]);
        } else {
            wp_send_json_error(['message' => 'خطا در خالی کردن سبد']);
        }
    }

    /**
     * دریافت اطلاعات سبد
     */
    public static function get_cart_info() {
        if (!check_ajax_referer('sina_cart_nonce', 'nonce', false)) {
            wp_send_json_error(['message' => 'خطای امنیتی'], 403);
        }

        $cart = Sina_Cart::get_instance();

        wp_send_json_success([
            'items' => $cart->get_items(),
            'cart_count' => $cart->get_item_count(),
            'unique_count' => $cart->get_unique_item_count(),
            'cart_total' => $cart->get_total(),
            'cart_total_formatted' => number_format($cart->get_total(), 0, '', ',') . ' تومان',
            'is_empty' => $cart->is_empty(),
        ]);
    }
    /**
 * ثبت سفارش نهایی
 */
public static function submit_order() {
    // بررسی امنیت
    if (!check_ajax_referer('sina_cart_nonce', 'nonce', false)) {
        wp_send_json_error(['message' => 'خطای امنیتی'], 403);
    }

    // دریافت داده‌های فرم
    $customer_data = [
        'customer_name'    => isset($_POST['customer_name']) ? sanitize_text_field($_POST['customer_name']) : '',
        'customer_phone'   => isset($_POST['customer_phone']) ? sanitize_text_field($_POST['customer_phone']) : '',
        'customer_email'   => isset($_POST['customer_email']) ? sanitize_email($_POST['customer_email']) : '',
        'customer_company' => isset($_POST['customer_company']) ? sanitize_text_field($_POST['customer_company']) : '',
        'customer_address' => isset($_POST['customer_address']) ? sanitize_textarea_field($_POST['customer_address']) : '',
        'customer_notes'   => isset($_POST['customer_notes']) ? sanitize_textarea_field($_POST['customer_notes']) : '',
    ];

    // اعتبارسنجی اولیه
    if (empty($customer_data['customer_name'])) {
        wp_send_json_error(['message' => 'نام و نام خانوادگی الزامی است']);
    }

    if (empty($customer_data['customer_phone'])) {
        wp_send_json_error(['message' => 'شماره تماس الزامی است']);
    }

    // اعتبارسنجی شماره موبایل
    $phone = preg_replace('/[^0-9]/', '', $customer_data['customer_phone']);
    if (strlen($phone) !== 11 || !preg_match('/^09\d{9}$/', $phone)) {
        wp_send_json_error(['message' => 'شماره موبایل نامعتبر است (مثال: 09123456789)']);
    }

    // ایجاد سفارش
    $cart = Sina_Cart::get_instance();
    $result = $cart->create_order($customer_data);

    if ($result['success']) {
        wp_send_json_success([
            'message'      => $result['message'],
            'order_id'     => $result['order_id'],
            'order_number' => $result['order_number'],
            'redirect_url' => home_url('/order-confirmation/?order=' . $result['order_number']),
        ]);
    } else {
        wp_send_json_error(['message' => $result['message']]);
    }
}

}
