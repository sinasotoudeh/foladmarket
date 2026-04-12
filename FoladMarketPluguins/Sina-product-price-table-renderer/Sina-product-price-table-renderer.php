<?php
/*
Plugin Name: Sina Product Price Table Renderer
Description: Displays non-WooCommerce product prices in a table layout via shortcode.
Version: 1.1
Author: Sina Sotoudeh
*/

// ۲. enqueue CSS & JS
add_action('wp_enqueue_scripts', 'prt_table_enqueue_assets');
function prt_table_enqueue_assets() {
    if ( ! is_singular('product') ) return;

    $dir = plugin_dir_path(__FILE__);
    $uri = plugin_dir_url(__FILE__);

    if ( file_exists("{$dir}assets/css/product-table.css") ) {
        wp_enqueue_style(
            'product-table-style',
            "{$uri}assets/css/product-table.css",
            [],
            filemtime("{$dir}assets/css/product-table.css")
        );
    }
}

/**
 * Render product HTML table and JSON-LD schema for a single product page.
 * Features: dynamic columns, SEO-friendly rating, optimized tooltip, JSON-LD with AggregateRating.
 */
function render_product_table() {
    global $wpdb;
    $post_id   = get_the_ID();
    
    // 1. Enqueue Styles
    if ( ! wp_style_is('product-table-style', 'enqueued') ) {
        wp_enqueue_style('product-table-style');
    }
    if ( ! wp_style_is('rating-table-style', 'enqueued') ) {
        wp_enqueue_style('rating-table-style');
    }

    // 2. Load ACF Fields
    $unstable_price = (bool) get_field('unstable_price', $post_id);
    $rows      = get_field('product_rows', $post_id) ?: [];
    $sg        = get_field('custom_additional_info', $post_id) ?: [];
    $faq_items = get_field('faq_items', $post_id) ?: [];
    $total = $wpdb->get_row($wpdb->prepare(
        "SELECT COUNT(*) AS count, ROUND(SUM(rating_value)/COUNT(*),1) AS avg
         FROM {$wpdb->prefix}product_ratings WHERE post_id=%d",
        $post_id
    ), ARRAY_A);
    $page_total = [
        'count' => intval($total['count']),
        'avg'   => floatval($total['avg']),
    ];

    // 3. گرفتن عنوان و تصویر محصول (برای سبد خرید)
    $product_title = get_the_title($post_id);
    $product_thumbnail = get_the_post_thumbnail_url($post_id, 'thumbnail');
    if (!$product_thumbnail) {
        $product_thumbnail = get_template_directory_uri() . '/assets/images/steel-op.webp';
    }

    // 4. Define All Possible Columns
    $columns = [
        'product_code'         => 'کد',
        'product_name'         => 'نام',
        'product_grade'        => 'آلیاژ',
        'product_trim'         => 'حالت',
        'product_thickness'    => 'ضخامت',
        'product_size'         => 'سایز',
        'additional_info_1'    => $sg['custom_additional_info1'],
        'additional_info_2'    => $sg['custom_additional_info2'],
        'additional_info_3'    => $sg['custom_additional_info3'],
        'additional_info_4'    => $sg['custom_additional_info4'],
        'additional_info_5'    => $sg['custom_additional_info5'],
        'product_weight'       => 'وزن',
        'loading_location'     => 'انبار',
        'manufacture_country'  => 'کشور',
        'product_manufacturer' => 'کارخانه',
        'measurement_unit'     => 'واحد',
        'product_price'        => 'قیمت',
    ];
    
    $columns_with_data = array_filter(
        $columns,
        function($label, $key) use ($rows) {
            foreach ($rows as $row) {
                if ( trim($row[$key] ?? '') !== '' ) return true;
            }
            return false;
        },
        ARRAY_FILTER_USE_BOTH
    );

    // 5. Fetch mobile-hide and custom classes
    $mobile_hide    = get_field('hide_on_mobile', $post_id) ?: [];
    $column_classes = get_field('custom_column_classes', $post_id) ?: [];

    // 6. Begin Output Buffer
    ob_start(); ?>
    <div class="price-table-wrapper">
        <table class="custom-product-table" role="table">
            <thead><tr>
                <?php foreach ($columns_with_data as $key => $label): ?>
                    <th scope="col" class="<?php echo esc_attr(checkCls($key, $mobile_hide, $column_classes)); ?>">
                        <?php echo esc_html($label); ?>
                    </th>
                <?php endforeach; ?>
                <!-- ستون جدید برای دکمه خرید -->
                <th scope="col" class="cart-action-col">عملیات</th>
            </tr></thead>
            <tbody>
                <?php foreach ($rows as $i => $row): ?>
                    <tr data-row-index="<?php echo esc_attr($i); ?>">
                        <?php foreach ($columns_with_data as $key => $label): ?>
                            <td class="<?php echo esc_attr(checkCls($key, $mobile_hide, $column_classes)); ?>">
                                <?php
if ( $key === 'product_price' ) {

    // ✅ قیمت نوسانی
    if ( ! empty($unstable_price) ) {

        $contact_page = get_page_by_path('contact-us');
        $contact_url  = $contact_page
            ? get_permalink($contact_page->ID)
            : home_url('/تماس-با-ما/');

        echo '<span class="unstable-price">'
           . '<a href="' . esc_url($contact_url) . '" class="price-contact">تماس بگیرید</a>'
           . '</span>';

    } else {

        $raw = $row[$key] ?? '';
        $contact_page = get_page_by_path('contact-us');
        $contact_url  = $contact_page
            ? get_permalink($contact_page->ID)
            : home_url('/تماس-با-ما/');

        if ( ! is_numeric( $raw ) || floatval( $raw ) <= 0 ) {

            echo '<a href="' . esc_url( $contact_url ) . '" class="price-contact">تماس بگیرید</a>';

        } else {

            $price     = floatval( $raw );
            $today     = floatval( get_option('daily_dollar_today_rate', 0) );
            $yesterday = floatval( get_option('daily_dollar_yesterday_rate', 0) );
            $fixed     = 7500;

            $delta = $today > $yesterday
                ?  $fixed
                : ( $today < $yesterday ? -$fixed : 0 );

            $color = $delta > 0 ? 'red' : ( $delta < 0 ? 'green' : 'blue' );

            echo '<span class="product-price-display" style="color:' . esc_attr( $color ) . '">'
               . number_format( $price, 0, ',', ',' )
               . '</span>';
        }
    }

} else {

    echo esc_html( $row[$key] ?? '' );

}

                                ?>
                            </td>
                        <?php endforeach; ?>
                        
                        <!-- سلول دکمه خرید -->
                        <td class="cart-action-col">
                            <?php
                            $raw_price = $row['product_price'] ?? '';
                            // فقط اگر قیمت معتبر باشد، دکمه نمایش داده شود
                            if ( is_numeric($raw_price) && floatval($raw_price) > 0 ):
                            ?>
                                <button 
                                    class="sina-add-to-cart-btn"
                                    data-post-id="<?php echo esc_attr($post_id); ?>"
                                    data-row-index="<?php echo esc_attr($i); ?>"
                                    data-product-title="<?php echo esc_attr($product_title); ?>"
                                    data-product-thumbnail="<?php echo esc_attr($product_thumbnail); ?>"
                                    data-product-code="<?php echo esc_attr($row['product_code'] ?? ''); ?>"
                                    data-product-name="<?php echo esc_attr($row['product_name'] ?? ''); ?>"
                                    data-product-size="<?php echo esc_attr($row['product_size'] ?? ''); ?>"
                                    data-product-thickness="<?php echo esc_attr($row['product_thickness'] ?? ''); ?>"
                                    data-product-grade="<?php echo esc_attr($row['product_grade'] ?? ''); ?>"
                                    data-product-trim="<?php echo esc_attr($row['product_trim'] ?? ''); ?>"
                                    data-product-weight="<?php echo esc_attr($row['product_weight'] ?? ''); ?>"
                                    data-product-manufacturer="<?php echo esc_attr($row['product_manufacturer'] ?? ''); ?>"
                                    data-measurement-unit="<?php echo esc_attr($row['measurement_unit'] ?? ''); ?>"
                                    data-product-price="<?php echo esc_attr($raw_price); ?>"
                                >
                                    <span class="btn-text">استعلام قیمت</span>
                                    <span class="btn-loading" style="display:none;">✅</span>
                                </button>
                            <?php else: ?>
                                <span class="no-cart-action">—</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <?php
    // ادامه کد JSON-LD (بدون تغییر)
    $post_id = get_the_ID();
    $product_rows = get_field('product_rows', $post_id);
    if (!empty($product_rows) && isset($product_rows[0])) {
        $first_row = $product_rows[0];
        $product_price = $first_row['product_price'] ?? '';  
        $product_sku = $first_row['product_code'] ?? '';
        $product_brand = (!empty($first_row['product_manufacturer'])) ? $first_row['product_manufacturer'] : "فولاد مارکت";
    }

    $product_name = get_the_title();
    $product_description = get_the_excerpt();
    $product_url = get_permalink();
    $content = get_post_field('post_content', get_the_ID());
    preg_match('/<img[^>]+src="([^">]+)"/i', $content, $match);
    $product_image = $match[1] ?? '';
    if (!$product_image) {
        $product_image = get_template_directory_uri() . '/assets/images/steel-op.webp';
    }
    $product_currency = 'IRR';
    $product_in_stock = true;
    $availability = "https://schema.org/InStock";
    $product_rating_value = number_format($page_total['avg'], 1, '.', '');
    $product_review_count = $page_total['count'];

    $schema = [
      "@context" => "https://schema.org",
      "@type" => "Product",
      "name" => $product_name,
      "description" => $product_description,
      "sku" => $product_sku,
      "image" => $product_image,
      "url" => $product_url,
      "brand" => [
        "@type" => "Brand",
        "name" => $product_brand
      ],
      "offers" => [
        "@type" => "Offer",
        "price" => $product_price,
        "priceCurrency" => $product_currency,
        "availability" => $availability,
        "priceValidUntil" => date('Y-m-d', strtotime('+1 week')),
        "shippingDetails" => [
          "@type" => "OfferShippingDetails",
          "shippingRate" => [
            "@type" => "MonetaryAmount",
            "value" => 0,
            "currency" => "IRR"
          ],
          "shippingDestination" => [
            "@type" => "DefinedRegion",
            "addressCountry" => "IR"
          ],
          "deliveryTime" => [
            "@type" => "ShippingDeliveryTime",
            "handlingTime" => [
              "@type" => "QuantitativeValue",
              "minValue" => 2,
              "maxValue" => 3,
              "unitCode" => "DAY"
            ],
            "transitTime" => [
              "@type" => "QuantitativeValue",
              "minValue" => 2,
              "maxValue" => 3,
              "unitCode" => "DAY"
            ]
          ]
        ],
        "hasMerchantReturnPolicy" => [
          "@type" => "MerchantReturnPolicy",
          "applicableCountry" => "IR",
          "returnPolicyCategory" => "https://schema.org/MerchantReturnNotPermitted"
        ],
        "url" => $product_url,
        "sku" => $product_sku
      ],
      "aggregateRating" => [
        "@type" => "AggregateRating",
        "ratingValue" => $product_rating_value,
        "reviewCount" => $product_review_count
      ]
    ];

    echo '<script type="application/ld+json">' . PHP_EOL;
    echo wp_json_encode($schema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    echo '</script>';
    
    return ob_get_clean();
}

// Hook or shortcode registration
add_shortcode('product_price_table', 'render_product_table');

function checkCls($key, $mobile_hide, $column_classes = []) {
    $classes = [];
    if (!empty($mobile_hide['hide_on_mobile_' . $key])) {
        $classes[] = 'hide-mobile';
    }
    if (!empty($column_classes['class_' . $key])) {
        $classes[] = sanitize_html_class($column_classes['class_' . $key]);
    }
    return esc_attr(implode(' ', $classes));
}

add_shortcode('product_first_price', function () {
    $post_id = get_the_ID();
    if ( ! $post_id ) return '';
$unstable_price = (bool) get_field('unstable_price', $post_id);

    $rows = get_field('product_rows', $post_id);
    if ( empty($rows) || empty($rows[0]) ) return '';

    $row = $rows[0];
    $raw = $row['product_price'] ?? '';

    $contact_page = get_page_by_path('contact-us');
    $contact_url  = $contact_page
                    ? get_permalink($contact_page->ID)
                    : home_url('/تماس-با-ما/');
if ( $unstable_price ) {
    $contact_page = get_page_by_path('contact-us');
    $contact_url  = $contact_page
                    ? get_permalink($contact_page->ID)
                    : home_url('/تماس-با-ما/');

    return '<span class="unstable-price">'
         . '<a href="' . esc_url($contact_url) . '" class="price-contact">تماس بگیرید</a>'
         . '</span>';
}

    if ( ! is_numeric($raw) || floatval($raw) <= 0 ) {
        return '<a href="' . esc_url($contact_url) . '" class="price-contact">تماس بگیرید</a>';
    }

    $price     = floatval($raw);
    $today     = floatval(get_option('daily_dollar_today_rate', 0));
    $yesterday = floatval(get_option('daily_dollar_yesterday_rate', 0));
    $fixed     = 7500;

    $delta = $today > $yesterday
               ?  $fixed
               : ($today < $yesterday
                  ? -$fixed
                  : 0);

    $color = $delta > 0 ? 'red' : ($delta < 0 ? 'green' : 'blue');

    return '<span class="product-first-price" style="color:' . esc_attr($color) . '">'
           . number_format($price, 0, ',', ',')
           . '</span>';
});
