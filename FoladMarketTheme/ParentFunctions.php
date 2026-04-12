<?php 
function add_current_date_to_yoast_seo_title( $title ) {
    // Exclude the homepage and specific pages by their percent-encoded slugs.
    if ( is_front_page() || is_page( array( '%d8%af%d8%b1%d8%a8%d8%a7%d8%b1%d9%87-%d9%85%d8%a7', '%d8%aa%d9%85%d8%a7%d8%b3-%d8%a8%d8%a7-%d9%85%d8%a7' ) ) ) {
        return $title;
    }

    $current_date = date_i18n('(j F)', current_time('timestamp'));
    
    $pos = strrpos( $title, ' - ' );
    if ( $pos !== false ) {
        $before = substr( $title, 0, $pos );
        $after  = substr( $title, $pos ); // includes " - "
        $new_title = $before . ' ' . $current_date . $after;
        return $new_title;
    } else {
        return $title . ' ' . $current_date;
    }
}
add_filter( 'wpseo_title', 'add_current_date_to_yoast_seo_title', 100 );

/**
 * ——————————————
 * 1. ثبت Custom Post Type «محصول»
 * ——————————————
 */
add_action( 'init', function() {
    $labels = [
        'name'               => 'محصولات',
        'singular_name'      => 'محصول',
        'add_new'            => 'افزودن محصول',
        'add_new_item'       => 'افزودن محصول جدید',
        'edit_item'          => 'ویرایش محصول',
        'all_items'          => 'همه محصولات',
        'menu_name'          => 'محصولات',
        'name_admin_bar'     => 'محصول',
        'search_items'       => 'جستجوی محصول',
        'not_found'          => 'محصولی یافت نشد',
        'not_found_in_trash' => 'هیچ محصولی در زباله‌دان نیست',
    ];

  register_post_type( 'product', [
        'labels'       => $labels,
        'public'       => true,
        'show_in_rest' => true,
        'has_archive'  => false,
        'rewrite'      => true,
        'supports'     => [ 'title','editor','thumbnail','custom-fields','excerpt' ],
        'taxonomies'   => [ 'product_group', 'category', 'post_tag' ],
        'menu_icon'    => 'dashicons-cart',
    ] );
}, 1 );

add_action( 'init', function() {
    $labels = [
        'name'          => 'گروه‌های محصول',
        'singular_name' => 'گروه محصول',
        'menu_name'     => 'گروه‌های محصول',
    ];
    register_taxonomy( 'product_group', ['product'], [
        'labels'            => $labels,
        'public'            => true,
        'hierarchical'      => false,
        'show_in_nav_menus' => true,
        'rewrite'           => false,
        'show_in_rest'      => true,
    ] );
}, 0 );
add_action( 'init', function(){
    $terms = get_terms([
        'taxonomy'   => 'product_group',
        'hide_empty' => false,
    ]);

    foreach( $terms as $term ){
        // escape کردن slug برای regex
        $slug = preg_quote( $term->slug, '/' );

        add_rewrite_rule(
            // با (?i) الگو را حساس به بزرگ/کوچک بودن نمی‌کنیم
            "(?i)^([^/]+)/{$slug}/?$",
            // نامک محصول در matches[1]
            'index.php?product=$matches[1]&product_group=' . $term->slug,
            'top'
        );

    }
}, 5 );

add_filter( 'post_type_link', function( $permalink, $post ){
    if ( $post->post_type !== 'product' ) {
        return $permalink;
    }

    $terms = get_the_terms( $post->ID, 'product_group' );
    if ( $terms && ! is_wp_error( $terms ) ) {
        $term_slug   = current( $terms )->slug;
        $product_slug = $post->post_name;
        // معکوس: /محصول/گروه/
        return home_url( "/{$product_slug}/{$term_slug}/" );
    }

    return $permalink;
}, 10, 2 );

function add_categories_to_products() {
  register_taxonomy_for_object_type( 'category', 'product' );
}
add_action( 'init', 'add_categories_to_products' );

// به کوئری آرشیو دسته‌بندی اجازه می‌دهد محصولات را هم بیاورد
function include_products_in_category_archives( $query ) {
    if ( is_admin() || ! $query->is_main_query() ) {
        return;
    }

    if ( $query->is_category() ) {
        // انواع پست را روی نوشته و محصول تنظیم می‌کند
        $query->set( 'post_type', array( 'post', 'product' ) );
    }
}
add_action( 'pre_get_posts', 'include_products_in_category_archives' );

// حذف پیشوند پیش‌فرض عنوان آرشیو (مثل "دسته بندی:")
add_filter( 'get_the_archive_title', function( $title ) {
    if ( is_category() ) {
        $title = single_cat_title( '', false );
    } elseif ( is_tag() ) {
        $title = single_tag_title( '', false );
    } elseif ( is_author() ) {
        $title = '<span class="vcard">' . get_the_author() . '</span>';
    }
    return $title;
});


// 404 شدن صفحات کوئری مشکوک
add_action('template_redirect', function () {
    if (is_front_page() && !empty($_SERVER['QUERY_STRING'])) {
        if (preg_match('/(?:_bd_prev_page|_bdsid|_gl|_ga)=/i', $_SERVER['QUERY_STRING'])) {
            global $wp_query;
            $wp_query->set_404();          // علامت‌گذاری صفحه به عنوان 404
            status_header(404);            // هدر HTTP 404
            nocache_headers();             // جلوگیری از کش شدن
        }
    }
});
// canonical شدن صفحات کوئری مشکوک
add_action('wp_head', function () {
    if (is_404() && !empty($_SERVER['QUERY_STRING'])) {
        $query = $_SERVER['QUERY_STRING'];
        if (preg_match('/(?:_bd_prev_page|_bdsid|_gl|_ga)=/i', $query)) {
            echo '<link rel="canonical" href="' . esc_url(home_url('/')) . '" />' . "\n";
        }
    }
}, 1);
// canonical صفحات فایل مشکوک
add_action('wp_head', function () {
    if (is_404()) {
        $request_uri = $_SERVER['REQUEST_URI'];
        $path = parse_url($request_uri, PHP_URL_PATH);
        if (preg_match('#^/\d+-\d+\.html$#', $path)) {
            echo '<link rel="canonical" href="' . esc_url(home_url('/')) . '" />' . "\n";
        }
    }
}, 1);

function enable_comments_for_products() {
    add_post_type_support('product', 'comments');
}
add_action('init', 'enable_comments_for_products');

function default_open_comments_for_products( $data , $postarr ) {
    if ( $data['post_type'] === 'product' && $data['comment_status'] === 'closed' ) {
        $data['comment_status'] = 'open';
    }
    return $data;
}
add_filter('post_link', function($permalink, $post) {
    if ($post->post_type === 'post') {
        $terms = wp_get_post_terms($post->ID, 'special_article_category');
        if ($terms && !is_wp_error($terms)) {
            $category_slug = $terms[0]->slug; // اولین دسته‌بندی ویژه
            return home_url("/articles/$category_slug/$post->post_name/");
        }
    }
    return $permalink; // پست‌های دیگر بدون تغییر
}, 10, 2);

add_action('init', function() {
    // آرشیو دسته‌بندی ویژه
    add_rewrite_rule('^articles/([^/]+)/?$', 'index.php?special_article_category=$matches[1]', 'top');
    
    // پست‌های داخل دسته‌بندی ویژه
    add_rewrite_rule('^articles/([^/]+)/([^/]+)/?$', 'index.php?name=$matches[2]', 'top');
});

add_action('init', function() {
    register_taxonomy('special_article_category', ['post'], [
        'labels' => [
            'name'          => 'دسته‌بندی ویژه نوشته ها',
            'singular_name' => 'آرشیو',
        ],
        'public'       => true,
        'hierarchical' => true,
        'show_ui'      => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'articles', 'hierarchical' => true],
    ]);
});
add_theme_support('post-thumbnails');
add_filter( 'request', function ( $vars ) {

    // فقط اگر name و product_group داریم
    if (
        isset( $vars['name'], $vars['product_group'] ) &&
        $vars['name'] === 'فولاد-spk' &&
        $vars['product_group'] === 'فولاد-ابزار'
    ) {
        return [
            'p'         => 33675,
            'post_type' => 'product',
        ];
    }

    return $vars;
}, 0 );

