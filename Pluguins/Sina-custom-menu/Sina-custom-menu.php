<?php
/*
Plugin Name: Sina Custom Menu
Description: Automatically builds a hierarchical menu from categories.
Version: 1.0
Author: Sina Sotoudeh
*/
/*
 *منووووووو
 * @return array
 */
//  تابع پاک کننده ی کش منو
 add_action('init', function(){
  if ( current_user_can('manage_options') ) {
    delete_transient('cmenu_tree');
    error_log('[CMENU DEBUG] transient cmenu_tree deleted');
  }
});


function cmenu_build_full_tree() {
    $taxonomy = 'category';
    $exclude  = [1,54,55];  // در صورت نیاز
    $tree = [];
    // 1. دسته‌های ریشه ( parent = 0 )
    $roots = get_terms([
        'taxonomy'        => $taxonomy,
        'hide_empty'      => false,
        'parent'          => 0,
        'exclude'         => $exclude,
        'hierarchical'    => false,
    ]);
    
    if ( is_wp_error($roots) ) {
        return $tree;
    }
    
    // ۲. تعیین ترتیب دلخواه بر اساس ID
$desired_order = [33, 65, 153,67,244,71,243,245,246];

// ۳. مرتب‌سازی با usort
usort($roots, function($a, $b) use ($desired_order) {
    $posA = array_search($a->term_id, $desired_order);
    $posB = array_search($b->term_id, $desired_order);
    // اگر ID در آرایه نبود، آن را در انتها قرار می‌دهیم
    $posA = ($posA === false) ? PHP_INT_MAX : $posA;
    $posB = ($posB === false) ? PHP_INT_MAX : $posB;
    return $posA - $posB;
});

    // ذخیره ریشه‌ها
    $tree[0] = array_map(function($term){
        return [
            'id'   => $term->term_id,
            'name' => $term->name,
            'url'  => get_term_link($term),
            'type' => 'category',
        ];
    }, $roots);
    // 2. صف برای پردازش بازگشتی همهٔ دسته‌ها
    $queue = wp_list_pluck($roots, 'term_id');
    while ( $queue ) {
        $parent_id = array_shift($queue);
        // ۲.۱ زیرشاخه‌های مستقیم
        $child_terms = get_terms([
            'taxonomy'        => $taxonomy,
            'hide_empty'      => false,
            'parent'          => $parent_id,
            'hierarchical'    => false,
        ]);
        // تبدیل به آرایهٔ مناسب
        $items = [];
        if ( ! is_wp_error($child_terms) && ! empty($child_terms) ) {
            // دسته‌ها را اول می‌آوریم
            foreach ( $child_terms as $c ) {
                $items[] = [
                    'id'   => $c->term_id,
                    'name' => $c->name,
                    'url'  => get_term_link($c),
                    'type' => 'category',
                ];
                // برای عمق‌های بعدی
                $queue[] = $c->term_id;
            }
        }
        // ۲.۲ محصولات مستقیمِ این دسته (include_children=false)
        $products = get_posts([
            'post_type'      => 'product',
            'posts_per_page' => -1,
            'fields'         => 'ids',
            'tax_query'      => [[
                'taxonomy'         => $taxonomy,
                'field'            => 'term_id',
                'terms'            => $parent_id,
                'include_children' => false,
            ]],
        ]);
        if ( ! empty($products) ) {
            foreach ( $products as $pid ) {
                $items[] = [
                    'id'    => $pid,
                    'name'  => get_the_title($pid),
                    'url'   => get_permalink($pid),
                    'type'  => 'product',
                ];
            }
        }
        // ۲.۳ ذخیره در نقشه
        $tree[ $parent_id ] = $items;
    }
    return $tree;
}

// کش کامل یک روزه
function cmenu_regenerate_tree() {
    $tree = cmenu_build_full_tree();
    set_transient('cmenu_tree', $tree, DAY_IN_SECONDS);
    return $tree;
}
add_action('save_post_product',  'cmenu_regenerate_tree');
add_action('edited_category',      'cmenu_regenerate_tree');
add_action('create_category',      'cmenu_regenerate_tree');
add_action('delete_category',      'cmenu_regenerate_tree');

/**
 * شورت‌کد [cmenu_ui] برای منوی جدید با ساختار UI/UX دلخواه
 */
/**
 * شورت‌کد [cmenu_ui] با ساختار سازگار با هدر
 */
add_shortcode('cmenu_ui', function() {
    // 1) بازخوانی درخت از ترنزینت
    $tree = get_transient('cmenu_tree');
    if ( false === $tree ) {
        $tree = cmenu_regenerate_tree();
    }
    $roots = $tree[0] ?? [];

    // متغیر استاتیک برای بررسی اینکه آیا دیتا قبلا چاپ شده یا خیر
    static $data_printed = false;

    ob_start(); 
    ?>
    <!-- فقط لیست آیتم‌ها بدون nav wrapper -->
    <?php foreach ( $roots as $item ) : ?>
        <li
            class="cmenu-submenu-item <?php echo $item['type'] === 'category' ? 'cmenu-subcategory' : 'cmenu-product'; ?>"
            data-id="<?php echo esc_attr($item['id']); ?>"
            data-type="<?php echo esc_attr($item['type']); ?>"
        >
            <a href="<?php echo esc_url($item['url']); ?>">
                <?php echo esc_html($item['name']); ?>
            </a>
        </li>
    <?php endforeach; ?>
    
    <!-- چاپ دیتا فقط برای بار اول -->
    <?php if ( ! $data_printed ) : ?>
        <script id="cmenu-data" type="application/json">
        <?php echo wp_json_encode($tree, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES); ?>
        </script>
        <?php $data_printed = true; ?>
    <?php endif; ?>

    <?php
    return ob_get_clean();
});


