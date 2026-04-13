<?php
/*
Plugin Name: Sina Product Rating
Description: Adds rating system with REST API and stars UI.
Version: 1.0
Author: Sina Sotoudeh
*/
add_action('wp_enqueue_scripts', 'prt_rating_enqueue_assets');
function prt_rating_enqueue_assets() {
    if ( ! is_singular('product') ) return;

    $dir = plugin_dir_path(__FILE__);
    $uri = plugin_dir_url(__FILE__);

    if ( file_exists("{$dir}assets/css/rating-table.css") ) {
        wp_enqueue_style(
            'rating-table-style',
            "{$uri}assets/css/rating-table.css",
            [],
            filemtime("{$dir}assets/css/rating-table.css")
        );
    }

    wp_enqueue_script(
        'product-rating-js',
        "{$uri}assets/js/product-rating.js",
        [],
        null,
        true
    );
    wp_localize_script('product-rating-js','customAjax',[
        'rest_url' => get_rest_url(null, 'product-rating/v1'),
        'post_id'  => get_the_ID(),
    ]);
}


function render_page_rating() {
    $post_id = get_the_ID();
    global $wpdb;

    $total = $wpdb->get_row($wpdb->prepare(
        "SELECT COUNT(*) AS count, ROUND(SUM(rating_value)/COUNT(*),1) AS avg
         FROM {$wpdb->prefix}product_ratings WHERE post_id=%d",
        $post_id
    ), ARRAY_A);

    $count = intval($total['count']);
    $avg = floatval($total['avg']);

    ob_start();
    ?>
    <div class="page-rating-container" data-post-id="<?php echo esc_attr($post_id); ?>" data-avg-rating="<?php echo esc_attr($avg); ?>">
        <div class="stars">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <span class="star" data-value="<?php echo $i; ?>">&#9733;</span>
            <?php endfor; ?>
        </div>
        <div class="rating-summary">
            <span class="average"> <strong><?php echo $avg; ?></strong></span>
            <span class="count">(از <?php echo $count; ?> رأی)</span>
        </div>
        <div class="rating-message"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('page_rating', 'render_page_rating');
// ۱. REST API
add_action('rest_api_init', function(){
    register_rest_route('product-rating/v1', '/ratings', [
        'methods'             => 'GET',
        'callback'            => 'custom_get_ratings',
        'permission_callback' => '__return_true',
    ]);
    register_rest_route('product-rating/v1', '/rate', [
        'methods'             => 'POST',
        'callback'            => 'custom_post_rating',
        'permission_callback' => '__return_true',
    ]);
});

function custom_get_ratings(WP_REST_Request $request) {
    global $wpdb;
    $post_id = absint($request->get_param('post_id'));
    $table = $wpdb->prefix . 'product_ratings';
    $total = $wpdb->get_row(
        $wpdb->prepare("SELECT COUNT(*) AS total_count,
            ROUND(SUM(rating_value)/COUNT(*),1) AS total_avg
            FROM {$table} WHERE post_id=%d", $post_id),
        ARRAY_A
    );
    return rest_ensure_response([
        'total' => [
            'count'=>intval($total['total_count']),
            'avg'=>floatval($total['total_avg'])
            ],
    ]);
}

function custom_post_rating(WP_REST_Request $request) {
    global $wpdb;
    $data    = $request->get_json_params();
    $post_id = absint( $data['post_id'] ?? 0 );
    $rating  = absint( $data['rating'] ?? 0 );

    // اعتبارسنجی ورودی‌ها
if ( ! $post_id || $rating < 1 || $rating > 5 ) {
        return new WP_Error(
            'invalid_data',
            'پارامترها نامعتبر',
            [ 'status' => 422 ]
        );
    }

    $table = $wpdb->prefix . 'product_ratings';

    // درج رأی جدید
    $inserted = $wpdb->insert(
        $table,
        [
            'post_id'      => $post_id,
            'rating_value' => $rating,
        ],
        [ '%d', '%d' ]
    );

    if ( false === $inserted ) {
        return new WP_Error(
            'db_error',
            'خطا در ذخیره‌سازی رأی',
            [ 'status' => 500 ]
        );
    }

    // بازیابی آمار کلی صفحه
    $total_stats = $wpdb->get_row(
        $wpdb->prepare(
            "
            SELECT
              COUNT(*) AS total_count,
              ROUND(SUM(rating_value)/COUNT(*), 1) AS total_avg
            FROM {$table}
            WHERE post_id = %d
            ",
            $post_id
        ),
        ARRAY_A
    );

    // ساخت پاسخ REST
    return rest_ensure_response([
        'total' => [
            'count' => intval( $total_stats['total_count'] ),
            'avg'   => floatval( $total_stats['total_avg'] ),
        ],
    ]);
}
