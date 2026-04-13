<?php
/*
Plugin Name: Sina Contatct-us Box
Description: Add Contatct-us box via Shortcode
Version: 1.0
Author: Sina Sotoudeh
*/
// 1. ثبت شورتکد [contact-us-product]
function contact_us_product_shortcode( $atts ) {
    // ویژگی‌های پیش‌فرض اگر لازم داشتید اینجا تعریف کنید
    $atts = shortcode_atts( array(
        'image' => plugin_dir_url(__FILE__) . 'assets/images/expert.webp',
    ), $atts, 'contact-us-product' );

    ob_start();
    ?>
    <div class="contact-us-product">
        <h2 class="cup-title"><a href="https://foladmarket.com/%d8%aa%d9%85%d8%a7%d8%b3-%d8%a8%d8%a7-%d9%85%d8%a7/">تماس با کارشناسان فروش</a></h2>
         <div class="cup-image"> 
             <img src="<?php echo esc_url( $atts['image'] ); ?>" alt="کارشناس فروش">
         </div>
        <div class="cup-box">
            <ul class="cup-list">
                <li><strong>خط ویژه:</strong><a href="tel:+982192003255">021-92003255</a> </li>
                <li><strong>سایر خطوط</strong></li>
                <li><strong></strong><a href="tel:+982166675136">021-66675136</a></li> 
                <li><strong></strong><a href="tel:+982166675137">021-66675137</a></li>
                <li><strong></strong><a href="tel:+982166675138">021-66675138</a></li>
                <li><strong></strong><a href="tel:+982165812300">021-65812300</a></li>
                <li><strong></strong><a href="tel:+982165812400">021-65812400</a></li>
                <li><strong>داخلی‌ها</strong></li>
                <li><strong>استعلام قیمت:</strong> 407</li>
                <li><strong>صدور فاکتور:</strong> 403</li>
                <li><strong>ارسال بار:</strong> 406</li>
                <li><strong>تماس با مدیریت:</strong><a href="tel:+989122833844">09122833844</a></li>
            </ul>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'contact-us-product', 'contact_us_product_shortcode' );

// 2. بارگزاری فایل CSS مربوط به باکس تماس
function contact_us_product_enqueue_styles() {
    $dir = plugin_dir_path(__FILE__);
    $uri = plugin_dir_url(__FILE__);

    $css = $dir . 'assets/css/contact-us-product.css';
    if ( file_exists($css) ) {
        wp_enqueue_style( 
            'contact-us-product-style', 
            $uri . 'assets/css/contact-us-product.css', // ✅ مسیر درست
            array(), 
            filemtime($css) // برای کش‌بریکر
        );
    }
}
add_action( 'wp_enqueue_scripts', 'contact_us_product_enqueue_styles' );
