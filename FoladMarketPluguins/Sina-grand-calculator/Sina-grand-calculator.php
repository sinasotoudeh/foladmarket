<?php
/*
Plugin Name: Sina Grand Calculator
Description: Weight calculator for different metals and shapes, available via shortcode.
Version: 1.0
Author: Sina Sotoudeh
*/

if ( ! defined( 'ABSPATH' ) ) exit; // No direct access

// 🔹 Enqueue CSS + JS only when shortcode is used
function grand_calculator_enqueue_assets() {
    $dir = plugin_dir_path( __FILE__ );
    $uri = plugin_dir_url( __FILE__ );

    // Register CSS
    wp_register_style(
        'grand-calculator-css',
        $uri . 'assets/css/grand-calculator.css',
        [],
        filemtime( $dir . 'assets/css/grand-calculator.css' )
    );

    // Register JS
    wp_register_script(
        'grand-calculator-js',
        $uri . 'assets/js/grand-calculator.js',
        [],
        filemtime( $dir . 'assets/js/grand-calculator.js' ),
        true
    );
}
add_action( 'init', 'grand_calculator_enqueue_assets' );

// 🔹 Shortcode handler
function grand_calculator_shortcode() {
    // Enqueue assets when shortcode runs
    wp_enqueue_style( 'grand-calculator-css' );
    wp_enqueue_script( 'grand-calculator-js' );

    // Load HTML view from template file
    ob_start();
    include plugin_dir_path( __FILE__ ) . 'templates/grand-calculator-view.php';
    return ob_get_clean();
}
add_shortcode( 'grand_calculator', 'grand_calculator_shortcode' );

add_action('wp_ajax_get_wc_product_price', 'steelcenter_get_wc_product_price');
add_action('wp_ajax_nopriv_get_wc_product_price', 'steelcenter_get_wc_product_price');

function steelcenter_get_wc_product_price() {
    $product_id = intval($_GET['product_id'] ?? 0);
    if (!$product_id) {
        wp_send_json_error(['message' => 'Invalid product ID']);
    }

    $product = wc_get_product($product_id);
    if (!$product) {
        wp_send_json_error(['message' => 'Product not found']);
    }

    // اگر محصول متغیر (variable) است، قیمت پیشفرض را بگیرید (می‌توانید تغییر دهید)
    $price = floatval($product->get_price());
    $name = $product->get_name();

    // اگر می‌خواهید اطلاعات بیشتری بدهید (مثلاً SKU یا قیمت فروش)، می‌توانید اینجا اضافه کنید:
    $sku = $product->get_sku();

    wp_send_json_success([
        'price' => $price,   // توجه: اینجا همان واحدی است که ووکامرس ذخیره کرده (شما گفتید ریال)
        'name'  => $name,
        'sku'   => $sku
    ]);
}
