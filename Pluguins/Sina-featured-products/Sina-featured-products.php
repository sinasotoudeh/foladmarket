<?php
/*
Plugin Name: Sina Featured Products
Description: Adds a featured product price tables slider via shortcode
Version: 1.0
Author: Sina Sotoudeh
*/

// 1) ثبت assetهای مربوط به اسلایدر و (اختیاری) ثبت product assets اگر در قالب موجود است
add_action('init', 'fm_register_product_tables_wrapper_assets');
function fm_register_product_tables_wrapper_assets(){
    // Base paths for THIS plugin (the slider wrapper)
    $dir = plugin_dir_path(__FILE__);
    $uri = plugin_dir_url(__FILE__);

    // ✅ Register slider assets (they live in THIS plugin)
    if ( file_exists("{$dir}assets/css/fm-product-tables-slider.css") ) {
        wp_register_style(
            'fm-product-tables-slider-style',
            "{$uri}assets/css/fm-product-tables-slider.css",
            [],
            filemtime("{$dir}assets/css/fm-product-tables-slider.css")
        );
    }
     if ( file_exists("{$dir}assets/css/product-table.css") ) {
        wp_register_style(
            'product-table-style',
            "{$uri}assets/css/product-table.css",
            [],
            filemtime("{$dir}assets/css/product-table.css")
        );
    }
    if ( file_exists("{$dir}assets/js/fm-product-tables-slider.js") ) {
        wp_register_script(
            'fm-product-tables-slider-script',
            "{$uri}assets/js/fm-product-tables-slider.js",
            [],
            filemtime("{$dir}assets/js/fm-product-tables-slider.js"),
            true
        );
    }

    // 🚫 REMOVE this part from here
    // Product Table + Rating assets should be registered in THEIR plugin, not here
    // If this plugin tries to re-register them, you risk duplication or wrong paths
}

// 2) شورتکد wrapper: [fm_products_tables ids="12,34,56" posts="10" rotation="3000"]
// شورتکد wrapper: [fm_products_tables ids="12,34,56" posts="10" rotation="3000"]
add_shortcode('fm_products_tables', 'fm_products_tables_shortcode');
function fm_products_tables_shortcode($atts) {
    $atts = shortcode_atts(array(
        'ids'      => '',
        'posts'    => 10,
        'rotation' => 3000,
    ), $atts, 'fm_products_tables');

    // enqueue assetها
    if ( wp_style_is('fm-product-tables-slider-style', 'registered') ) wp_enqueue_style('fm-product-tables-slider-style');
    if ( wp_script_is('fm-product-tables-slider-script', 'registered') ) wp_enqueue_script('fm-product-tables-slider-script');

    if ( wp_style_is('product-table-style', 'registered') ) wp_enqueue_style('product-table-style');
    if ( wp_style_is('rating-table-style', 'registered') ) wp_enqueue_style('rating-table-style');

    if ( wp_script_is('product-rating-js', 'registered') ) {
        wp_enqueue_script('product-rating-js');
        wp_localize_script('product-rating-js', 'customAjax', array(
            'rest_url' => get_rest_url(null, 'custom/v1'),
            'post_id'  => 0
        ));
    }

    // دریافت محصولات
    $ids = array_filter(array_map('absint', array_map('trim', explode(',', $atts['ids']))));
    if ( empty($ids) ) {
        $q = new WP_Query(array(
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'posts_per_page' => intval($atts['posts'])
        ));
        $posts = $q->posts;
    } else {
        $posts = get_posts(array(
            'post_type' => 'product',
            'post__in'  => $ids,
            'orderby'   => 'post__in',
            'posts_per_page' => count($ids)
        ));
    }

    if ( empty($posts) ) return '<!-- fm_products_tables: no products -->';

    // HTML
    $uid = 'fm-products-' . uniqid();
    $out  = '<div id="'.esc_attr($uid).'" class="fm-featured-products-slider" data-rotation="'.intval($atts['rotation']).'">';
    
    // فلش قبلی
    $out .= '<button class="fm-nav-prev" type="button">&#10094;</button>';
    
    // رپ جدول‌ها
    $out .= '<div class="fm-slider-wrapper">';

    foreach ($posts as $i => $p) {
        $GLOBALS['post'] = $p;
        setup_postdata($p);
        $table = do_shortcode('[product_price_table]');
        wp_reset_postdata();

        $product_link = get_permalink($p);
        $product_title = get_the_title($p);

        $out .= '<div class="fm-slide'.($i===0 ? ' fm-active' : '').'" data-index="'.$i.'">';
        $out .= '<a class="fm-title-btn" href="'.esc_url($product_link).'">جدول قیمت '.$product_title.'</a>';
        $out .= '<div class="fm-price-wrapper" data-post-id="'.intval($p->ID).'">';
        $out .= '<a href="'.esc_url($product_link).'">'.$table.'</a>';
        $out .= '</div></div>';
    }

    $out .= '</div>'; // .fm-slider-wrapper

    // فلش بعدی
    $out .= '<button class="fm-nav-next" type="button">&#10095;</button>';

    // pagination
    $out .= '<div class="fm-pagination">';
    for ($i = 0; $i < count($posts); $i++) {
        $out .= '<span class="fm-dot'.($i===0 ? ' fm-active' : '').'" data-index="'.$i.'"></span>';
    }
    $out .= '</div>';

    $out .= '</div>'; // main container

    return $out;
}
