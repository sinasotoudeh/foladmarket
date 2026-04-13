<?php
/**
 * کلاس مدیریت شورت‌کدهای سبد خرید
 * 
 * مسئولیت: رندر صفحات سبد خرید، چک‌اوت و نمایش تعداد آیتم‌ها
 * 
 * @package Sina_Custom_Cart
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit('Direct access forbidden.');
}

class Sina_Cart_Shortcodes {

    /**
     * مقداردهی اولیه و ثبت شورت‌کدها
     */
    public static function init() {
        add_shortcode('sina_cart_page', [__CLASS__, 'render_cart_page']);
        add_shortcode('sina_checkout_page', [__CLASS__, 'render_checkout_page']);
        add_shortcode('sina_cart_count', [__CLASS__, 'render_cart_count']);
        add_shortcode('sina_mini_cart', [__CLASS__, 'render_mini_cart']);
    }

    /**
     * رندر صفحه سبد خرید
     * 
     * @param array $atts آتریبیوت‌های شورت‌کد
     * @return string
     */
    public static function render_cart_page($atts) {
        // دریافت نمونه سبد خرید
        $cart = Sina_Cart::get_instance();
        
        // دریافت آیتم‌ها (با اجبار refresh برای اطمینان از داده‌های تازه)
        $items = $cart->get_items(true);

        // اجرای Action برای توسعه‌دهندگان
        do_action('sina_cart_page_before_render', $items);

        ob_start();
        ?>
        <div class="sina-cart-wrapper" data-cart-session="<?php echo esc_attr($cart->get_session_id()); ?>">
            
            <?php if (empty($items)) : ?>
                
                <!-- سبد خالی -->
                <div class="sina-cart-empty">
                    <svg class="empty-cart-icon" width="80" height="80" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path d="M9 2L7.17 4H3C1.9 4 1 4.9 1 6V18C1 19.1 1.9 20 3 20H21C22.1 20 23 19.1 23 18V6C23 4.9 22.1 4 21 4H16.83L15 2H9Z" 
                              fill="currentColor" opacity="0.3"/>
                    </svg>
                    <h2><?php esc_html_e('فهرست استعلام خالی است', 'sina-custom-cart'); ?></h2>
                    <p><?php esc_html_e('هنوز هیچ محصولی را برای استعلام قیمت اضافه نکرده‌اید.', 'sina-custom-cart'); ?></p>
                    <a href="<?php echo esc_url(home_url('/products/')); ?>" class="sina-btn sina-btn-primary">
                        <?php esc_html_e('مشاهده محصولات فولادمارکت', 'sina-custom-cart'); ?>
                    </a>
                </div>

            <?php else : ?>

                <!-- آیتم‌های سبد -->
                <div class="sina-cart-content">
                    
                    <div class="sina-cart-header">
                        <h2><?php esc_html_e('فهرست محصولات مورد نظر شما برای استعلام قیمت', 'sina-custom-cart'); ?></h2>
                        <span class="sina-cart-count-badge">
                            <?php 
                            printf(
                                esc_html(_n('%s محصول', '%s محصول', count($items), 'sina-custom-cart')),
                                number_format_i18n(count($items))
                            ); 
                            ?>
                        </span>
                    </div>

                    <!-- Loading Overlay برای AJAX -->
                    <div class="sina-cart-loading" style="display: none;">
                        <div class="loading-spinner"></div>
                    </div>

                    <div class="sina-cart-table-wrapper">
                        <table class="sina-cart-table" role="table">
                            <thead>
                                <tr role="row">
                                    <th class="col-image" scope="col"><?php esc_html_e('تصویر', 'sina-custom-cart'); ?></th>
                                    <th class="col-product" scope="col"><?php esc_html_e('محصول', 'sina-custom-cart'); ?></th>
                                    <th class="col-specs" scope="col"><?php esc_html_e('مشخصات', 'sina-custom-cart'); ?></th>
                                    <th class="col-price" scope="col"><?php esc_html_e('قیمت هر کیلو(حدودی)', 'sina-custom-cart'); ?></th>
                                   <!-- <th class="col-quantity" scope="col"><?php esc_html_e('وزن (کیلوگرم)', 'sina-custom-cart'); ?></th> -->
                                  <!--  <th class="col-total" scope="col"><?php esc_html_e('(حدودی)قیمت کل', 'sina-custom-cart'); ?></th> -->
                                    <th class="col-remove" scope="col"><span class="screen-reader-text"><?php esc_html_e('حذف', 'sina-custom-cart'); ?></span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item) : 
                                    $item_total = floatval($item['price']) * intval($item['quantity']);
                                    $product_name = !empty($item['product_name']) ? $item['product_name'] : $item['product_title'];
                                ?>
                                    <tr class="sina-cart-item" 
                                        data-item-id="<?php echo esc_attr($item['id']); ?>"
                                        data-post-id="<?php echo esc_attr($item['post_id']); ?>"
                                        data-row-index="<?php echo esc_attr($item['row_index']); ?>"
                                        role="row">
                                        
                                        <!-- تصویر -->
                                        <td class="sina-cart-item-image" data-label="<?php esc_attr_e('تصویر', 'sina-custom-cart'); ?>">
                                            <?php if (!empty($item['product_thumbnail'])) : ?>
                                                <img src="<?php echo esc_url($item['product_thumbnail']); ?>" 
                                                     alt="<?php echo esc_attr($product_name); ?>" 
                                                     loading="lazy"
                                                     width="80"
                                                     height="80" />
                                            <?php else : ?>
                                                <div class="no-image-placeholder" role="img" aria-label="<?php esc_attr_e('بدون تصویر', 'sina-custom-cart'); ?>">
                                                    <svg width="40" height="40" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                        <path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/>
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <!-- نام محصول -->
                                        <td class="sina-cart-item-name" data-label="<?php esc_attr_e('محصول', 'sina-custom-cart'); ?>">
                                            <div class="product-name-wrapper">
                                                <strong><?php echo esc_html($item['product_title']); ?></strong>
                                                <?php if (!empty($item['product_name'])) : ?>
                                                    <span class="product-subtitle"><?php echo esc_html($item['product_name']); ?></span>
                                                <?php endif; ?>
                                                <?php if (!empty($item['product_code'])) : ?>
                                                    <span class="product-sku">
                                                        <?php 
                                                        printf(
                                                            esc_html__('کد: %s', 'sina-custom-cart'),
                                                            esc_html($item['product_code'])
                                                        ); 
                                                        ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                        </td>

                                        <!-- مشخصات -->
                                        <td class="sina-cart-item-specs" data-label="<?php esc_attr_e('مشخصات', 'sina-custom-cart'); ?>">
                                            <div class="specs-list">
                                                <?php 
                                                $specs = [
                                                    __('سایز', 'sina-custom-cart')     => $item['product_size'] ?? '',
                                                    __('ضخامت', 'sina-custom-cart')   => $item['product_thickness'] ?? '',
                                                    __('آلیاژ', 'sina-custom-cart')    => $item['product_grade'] ?? '',
                                                    __('کارخانه', 'sina-custom-cart') => $item['product_manufacturer'] ?? '',
                                                ];
                                                
                                                // فیلتر برای اضافه کردن یا حذف مشخصات
                                                $specs = apply_filters('sina_cart_item_specs', $specs, $item);
                                                
                                                foreach ($specs as $label => $value) :
                                                    if (!empty($value)) :
                                                ?>
                                                    <div class="spec-item">
                                                        <span class="spec-label"><?php echo esc_html($label); ?>:</span>
                                                        <span class="spec-value"><?php echo esc_html($value); ?></span>
                                                    </div>
                                                <?php 
                                                    endif;
                                                endforeach; 
                                                ?>
                                            </div>
                                        </td>

                                        <!-- قیمت واحد -->
                                        <td class="sina-cart-item-price" data-label="<?php esc_attr_e('قیمت واحد', 'sina-custom-cart'); ?>">
                                            <div class="price-wrapper">
                                                <span class="price-amount"><?php echo number_format_i18n($item['price'], 0); ?></span>
                                                <span class="price-currency"><?php esc_html_e('ریال', 'sina-custom-cart'); ?></span>
                                            </div>
                                        </td>
                                        <!-- دکمه حذف -->
                                        <td class="sina-cart-item-remove" data-label="<?php esc_attr_e('حذف', 'sina-custom-cart'); ?>">
                                            <button type="button" 
                                                    class="sina-remove-cart-item" 
                                                    data-item-id="<?php echo esc_attr($item['id']); ?>"
                                                    title="<?php esc_attr_e('حذف از سبد', 'sina-custom-cart'); ?>"
                                                    aria-label="<?php 
                                                        printf(
                                                            esc_attr__('حذف %s از سبد', 'sina-custom-cart'),
                                                            esc_attr($product_name)
                                                        ); 
                                                    ?>">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                                    <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/>
                                                </svg>
                                                <span class="screen-reader-text"><?php esc_html_e('حذف', 'sina-custom-cart'); ?></span>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- خلاصه سبد -->
                    <div class="sina-cart-summary">
                        <div class="sina-cart-totals">
                            <h3><?php esc_html_e('خلاصه استعلام ', 'sina-custom-cart'); ?></h3>
                            
                            <table class="totals-table">
                                <tbody>
                                    <tr>
                                        <td><?php esc_html_e('تعداد آیتم‌ها:', 'sina-custom-cart'); ?></td>
                                        <td>
                                            <strong class="cart-item-count">
                                                <?php echo number_format_i18n($cart->get_item_count()); ?>
                                            </strong>
                                        </td>
                                    </tr>
                                <!--    <tr class="total-row">
                                        <td><?php esc_html_e('(حدودی)جمع کل:', 'sina-custom-cart'); ?></td>
                                        <td class="cart-total-amount">
                                            <strong>
                                                <?php echo number_format_i18n($cart->get_total(), 0); ?> 
                                                <?php esc_html_e('ریال', 'sina-custom-cart'); ?>
                                            </strong>
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                            
                            <?php do_action('sina_cart_totals_after', $cart); ?>
                            
                            <div class="sina-cart-actions">
                                <a href="<?php echo esc_url(home_url('/')); ?>" 
                                   class="sina-btn sina-btn-secondary">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                     <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>   
                                    </svg>
                                    <?php esc_html_e('بازگشت به صفحه اصلی', 'sina-custom-cart'); ?>
                                </a>
                                <a href="<?php echo esc_url(home_url('/checkout/')); ?>" 
                                   class="sina-btn sina-btn-primary">
                                    <?php esc_html_e('ارسال درخواست استعلام', 'sina-custom-cart'); ?>
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>

                </div>

            <?php endif; ?>

        </div>
        <?php
        
        $output = ob_get_clean();
        
        // اجرای Filter برای تغییر خروجی
        return apply_filters('sina_cart_page_output', $output, $items);
    }

    /**
     * رندر صفحه چک‌اوت
     * 
     * @param array $atts آتریبیوت‌های شورت‌کد
     * @return string
     */
public static function render_checkout_page($atts) {
        $cart = Sina_Cart::get_instance();
        
        // بررسی سبد خالی
        if ($cart->is_empty()) {
            ob_start();
            ?>
            <div class="sina-checkout-empty">
                <svg class="empty-cart-icon" width="80" height="80" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M9 2L7.17 4H3C1.9 4 1 4.9 1 6V18C1 19.1 1.9 20 3 20H21C22.1 20 23 19.1 23 18V6C23 4.9 22.1 4 21 4H16.83L15 2H9Z" 
                          fill="currentColor" opacity="0.3"/>
                </svg>
                <p><?php esc_html_e('هیچ محصولی برای استعلام قیمت انتخاب نکرده اید.', 'sina-custom-cart'); ?></p>
                <a href="<?php echo esc_url(home_url('/')); ?>" class="sina-btn sina-btn-primary">
                    <?php esc_html_e('بازگشت به صفحه اصلی', 'sina-custom-cart'); ?>
                </a>
            </div>
            <?php
            return ob_get_clean();
        }

        $items = $cart->get_items();
        $current_user = wp_get_current_user();
        
        // اجرای Action برای توسعه‌دهندگان
        do_action('sina_checkout_page_before_render', $items);

        ob_start();
        ?>
        <div class="sina-checkout-wrapper" data-cart-session="<?php echo esc_attr($cart->get_session_id()); ?>">
            
            <form id="sina-checkout-form" method="post" action="" novalidate>
                <?php wp_nonce_field('sina_checkout_nonce', 'checkout_nonce'); ?>
                
                <div class="sina-checkout-content">
                    
                    <!-- بخش اطلاعات مشتری -->
                    <div class="sina-checkout-billing">
                        <h3><?php esc_html_e('اطلاعات تماس و آدرس', 'sina-custom-cart'); ?></h3>
                        
                        <div class="sina-form-grid">
                            <div class="sina-form-row">
                                <label for="customer_name">
                                    <?php esc_html_e('نام و نام خانوادگی', 'sina-custom-cart'); ?> 
                                    <span class="required" aria-label="<?php esc_attr_e('الزامی', 'sina-custom-cart'); ?>">*</span>
                                </label>
                                <input type="text" 
                                       id="customer_name" 
                                       name="customer_name" 
                                       value="<?php echo esc_attr($current_user->display_name); ?>"
                                       required 
                                       aria-required="true"
                                       autocomplete="name" />
                            </div>
                            
                            <div class="sina-form-row">
                                <label for="customer_phone">
                                    <?php esc_html_e('شماره تماس', 'sina-custom-cart'); ?> 
                                    <span class="required" aria-label="<?php esc_attr_e('الزامی', 'sina-custom-cart'); ?>">*</span>
                                </label>
                                <input type="tel" 
                                       id="customer_phone" 
                                       name="customer_phone" 
                                       pattern="[0-9]{11}"
                                       placeholder="<?php esc_attr_e('09123456789', 'sina-custom-cart'); ?>"
                                       required 
                                       aria-required="true"
                                       autocomplete="tel" />
                            </div>
                        </div>
                        
                        <div class="sina-form-row">
                            <label for="customer_email"><?php esc_html_e('ایمیل', 'sina-custom-cart'); ?></label>
                            <input type="email" 
                                   id="customer_email" 
                                   name="customer_email" 
                                   value="<?php echo esc_attr($current_user->user_email); ?>"
                                   autocomplete="email" />
                        </div>
                        
                        <div class="sina-form-row">
                            <label for="customer_company"><?php esc_html_e('نام شرکت', 'sina-custom-cart'); ?></label>
                            <input type="text" 
                                   id="customer_company" 
                                   name="customer_company" 
                                   autocomplete="organization" />
                        </div>
                        
                        <div class="sina-form-row">
                            <label for="customer_address"><?php esc_html_e('آدرس کامل', 'sina-custom-cart'); ?></label>
                            <textarea id="customer_address" 
                                      name="customer_address" 
                                      rows="3"
                                      placeholder="<?php esc_attr_e('استان، شهر، خیابان، پلاک...', 'sina-custom-cart'); ?>"
                                      autocomplete="street-address"></textarea>
                        </div>
                        
                        <div class="sina-form-row">
                            <label for="customer_notes"><?php esc_html_e('توضیحات استعلام', 'sina-custom-cart'); ?></label>
                            <textarea id="customer_notes" 
                                      name="customer_notes" 
                                      rows="4" 
                                      placeholder="<?php esc_attr_e('توضیحات اضافی درباره استعلام خود را اینجا بنویسید...', 'sina-custom-cart'); ?>"></textarea>
                        </div>
                        
                        <?php do_action('sina_checkout_billing_after_fields'); ?>
                    </div>

                    <!-- بخش خلاصه سفارش -->
                    <div class="sina-checkout-summary">
                        <h3><?php esc_html_e('خلاصه استعلام ها', 'sina-custom-cart'); ?></h3>
                        
                        <div class="order-items">
                            <?php foreach ($items as $item) : 
                                $item_total = floatval($item['price']) * intval($item['quantity']);
                                $product_name = !empty($item['product_name']) ? $item['product_name'] : $item['product_title'];
                            ?>
                                <div class="order-item">
                                    <?php if (!empty($item['product_thumbnail'])) : ?>
                                        <div class="item-image">
                                            <img src="<?php echo esc_url($item['product_thumbnail']); ?>" 
                                                 alt="<?php echo esc_attr($product_name); ?>" 
                                                 loading="lazy"
                                                 width="60"
                                                 height="60" />
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="item-details">
                                        <div class="item-name">
                                            <strong><?php echo esc_html($product_name); ?></strong>
                                            <?php if (!empty($item['product_code'])) : ?>
                                                <small>
                                                    <?php 
                                                    printf(
                                                        esc_html__('کد: %s', 'sina-custom-cart'),
                                                        esc_html($item['product_code'])
                                                    ); 
                                                    ?>
                                                </small>
                                            <?php endif; ?>
                                        </div>
                                        <div class="item-meta">
                                            <span class="item-quantity">× <?php echo number_format_i18n($item['quantity']); ?></span>
                                            <span class="item-price">
                                                <?php echo number_format_i18n($item_total, 0); ?> 
                                                <?php esc_html_e('ریال', 'sina-custom-cart'); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="order-summary">
                            <div class="summary-row">
                                <span><?php esc_html_e('تعداد آیتم‌ها:', 'sina-custom-cart'); ?></span>
                                <strong class="cart-item-count"><?php echo number_format_i18n($cart->get_item_count()); ?></strong>
                            </div>
                            
                            <?php do_action('sina_checkout_summary_before_total', $cart); ?>
                            
                         <!--   <div class="summary-row total-row">
                                <strong><?php esc_html_e('(حدودی)جمع کل:', 'sina-custom-cart'); ?></strong>
                                <strong class="total-amount">
                                    <?php echo number_format_i18n($cart->get_total(), 0); ?> 
                                    <?php esc_html_e('ریال', 'sina-custom-cart'); ?>
                                </strong>
                            </div>-->
                        </div> 

                        <div class="sina-checkout-actions">
                            <button type="submit" 
                                    class="sina-btn sina-btn-primary sina-btn-large" 
                                    id="sina-checkout-submit">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                    <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                                </svg>
                                <span class="button-text"><?php esc_html_e('ثبت درخواست استعلام', 'sina-custom-cart'); ?></span>
                                <span class="button-loading" style="display: none;">
                                    <?php esc_html_e('در حال ثبت...', 'sina-custom-cart'); ?>
                                </span>
                            </button>
                        </div>
                        
                        <div class="checkout-note">
                            <small>
                                <?php 
                                printf(
                                    wp_kses(
                                        __('با ثبت سفارش، شما <a href="%s">قوانین و مقررات</a> سایت را می‌پذیرید.', 'sina-custom-cart'),
                                        ['a' => ['href' => []]]
                                    ),
                                    esc_url(home_url('/terms/'))
                                ); 
                                ?>
                            </small>
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <?php
        
        $output = ob_get_clean();
        
        // اجرای Filter برای تغییر خروجی
        return apply_filters('sina_checkout_page_output', $output, $items);
    }


    /**
     * شورت‌کد نمایش تعداد آیتم‌های سبد
     * 
     * @param array $atts آتریبیوت‌های شورت‌کد
     * @return string
     */
    public static function render_cart_count($atts) {
        $cart = Sina_Cart::get_instance();
        $count = $cart->get_item_count();
        
        $atts = shortcode_atts([
            'show_zero' => 'yes', // نمایش صفر یا مخفی کردن
            'wrapper_class' => 'sina-cart-count',
        ], $atts);
        
        if ($count === 0 && $atts['show_zero'] === 'no') {
            return '';
        }
        
        return sprintf(
            '<span class="%s" data-count="%d" aria-label="%s">%d</span>',
            esc_attr($atts['wrapper_class']),
            esc_attr($count),
            esc_attr(sprintf(__('%d محصول در سبد خرید', 'sina-custom-cart'), $count)),
            esc_html($count)
        );
    }

    /**
     * شورت‌کد mini cart (برای نوار منو)
     * 
     * استفاده: [sina_mini_cart]
     * 
     * @param array $atts آتریبیوت‌های شورت‌کد
     * @return string
     */
    public static function render_mini_cart($atts) {
        $cart = Sina_Cart::get_instance();
        $count = $cart->get_item_count();
        $total = $cart->get_total();
        
        $atts = shortcode_atts([
            'show_total' => 'yes',
            'show_icon' => 'yes',
            'icon_color' => '',
        ], $atts);
        
        ob_start();
        ?>
        <div class="sina-mini-cart" role="navigation" aria-label="<?php esc_attr_e('سبد خرید', 'sina-custom-cart'); ?>">
            <a href="<?php echo esc_url(home_url('/price/')); ?>" 
               class="sina-mini-cart-link"
               aria-label="<?php 
                   printf(
                       esc_attr__('مشاهده فهرست استعلام - %d محصول', 'sina-custom-cart'),
                       $count
                   ); 
               ?>">
                
                <?php if ($atts['show_icon'] === 'yes') : ?>
                    <svg class="mini-cart-icon" 
                         width="24" 
                         height="24" 
                         viewBox="0 0 24 24" 
                         fill="currentColor"
                         aria-hidden="true"
                         <?php if (!empty($atts['icon_color'])) : ?>
                             style="color: <?php echo esc_attr($atts['icon_color']); ?>;"
                         <?php endif; ?>>
                        <path d="M16 6l2.29 2.29-4.88 4.88-4-4L2 16.59 3.41 18l6-6 4 4 6.3-6.29L22 12V6z"/>
                    </svg>
                <?php endif; ?>
                
                <?php if ($count > 0) : ?>
                    <span class="cart-count-badge" 
                          role="status" 
                          aria-live="polite">
                        <?php echo esc_html($count); ?>
                    </span>
                <?php endif; ?>
                

                    <span class="cart-total-amount">
                        استعلام قیمت
                    </span>

            </a>
        </div>
        <?php
        
        $output = ob_get_clean();
        
        return apply_filters('sina_mini_cart_output', $output, $cart);
    }
}

// راه‌اندازی شورت‌کدها
Sina_Cart_Shortcodes::init();

