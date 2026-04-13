<?php
/**
 * FoladMarket Child Theme - Functions
 * 
 * Optimized for Core Web Vitals Performance with Lazy Loading
 * 
 * @package Astra Child
 * @version 1.0.2
 * @author Sina Sotoudeh
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * ============================================================================
 * CONSTANTS DEFINITION
 * ============================================================================
 */
define( 'ASTRA_CHILD_VERSION', '1.0.2' );
define( 'ASTRA_CHILD_DIR', get_stylesheet_directory() );
define( 'ASTRA_CHILD_URI', get_stylesheet_directory_uri() );

/**
 * Helper: Check if Homepage Template (Frontend Only)
 */
function is_homepage_template() {
    return ! is_admin() && is_page_template( 'template-homepage.php' );
}

/**
 * ============================================================================
 * CRITICAL RESOURCES - Priority 1-5
 * ============================================================================
 */

/**
 * Preload Critical Fonts (Priority: 1)
 */
function foladmarket_preload_fonts() {
    if ( is_admin() ) {
        return;
    }
    ?>
    <link rel="preload" href="<?php echo esc_url( ASTRA_CHILD_URI . '/assets/fonts/PeydaWeb-Regular.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
    <link rel="preload" href="<?php echo esc_url( ASTRA_CHILD_URI . '/assets/fonts/PeydaWeb-Bold.woff2' ); ?>" as="font" type="font/woff2" crossorigin>
    <?php
}
add_action( 'wp_head', 'foladmarket_preload_fonts', 1 );

/**
 * Inline Critical CSS (Priority: 1)
 */
function foladmarket_inline_critical_css() {
    if ( ! is_homepage_template() ) {
        return;
    }
    
    $inline_file = ASTRA_CHILD_DIR . '/assets/css/min/inline.min.css';
    
    if ( file_exists( $inline_file ) ) {
        $inline_css = file_get_contents( $inline_file );
        if ( $inline_css ) {
            echo "\n<!-- Critical Inline CSS -->\n";
            echo '<style id="inline-critical-css">' . $inline_css . '</style>';
            echo "\n<!-- /Critical Inline CSS -->\n";
        }
    }
}
add_action( 'wp_head', 'foladmarket_inline_critical_css', 1 );

/**
 * Preload Critical CSS (Priority: 2)
 */
function foladmarket_preload_critical_css() {
    if ( ! is_homepage_template() ) {
        return;
    }
    
    echo '<link rel="preload" as="style" href="' . esc_url( ASTRA_CHILD_URI . '/assets/css/min/critical.min.css' ) . '">' . "\n";
}
add_action( 'wp_head', 'foladmarket_preload_critical_css', 2 );

/**
 * Add Schema Markup (Priority: 5)
 */
function foladmarket_add_schema_homepage() {
    if ( ! is_homepage_template() ) {
        return;
    }
    
    if ( function_exists( 'foladmarket_organization_schema' ) ) {
        echo foladmarket_organization_schema();
    }
    
    if ( function_exists( 'foladmarket_localbusiness_schema' ) ) {
        echo foladmarket_localbusiness_schema();
    }
}
add_action( 'wp_head', 'foladmarket_add_schema_homepage', 5 );

/**
 * ============================================================================
 * CORE STYLES - Priority 10-15
 * ============================================================================
 */

/**
 * Enqueue Global Assets (Priority: 10)
 */
function foladmarket_global_assets() {
    if ( is_admin() ) {
        return;
    }

    // 1. Font Styles
    wp_enqueue_style(
        'foladmarket-fonts',
        ASTRA_CHILD_URI . '/assets/css/fonts.css',
        array(),
        filemtime( ASTRA_CHILD_DIR . '/assets/css/fonts.css' ),
        'all'
    );

    // 2. Critical CSS (Homepage)
    if ( is_page_template( 'template-homepage.php' ) ) {
        $critical_css_path = ASTRA_CHILD_DIR . '/assets/css/min/critical.min.css';
        if ( file_exists( $critical_css_path ) ) {
            wp_enqueue_style(
                'foladmarket-critical-css',
                ASTRA_CHILD_URI . '/assets/css/min/critical.min.css',
                array( 'foladmarket-fonts' ),
                filemtime( $critical_css_path ),
                'all'
            );
        }
    }

    // 3. Header CSS (Non-Homepage) - Lazy Load
    $header_css_path = ASTRA_CHILD_DIR . '/assets/css/min/header-style.min.css';
    if ( file_exists( $header_css_path ) ) {
        wp_enqueue_style(
            'foladmarket-header-css',
            ASTRA_CHILD_URI . '/assets/css/min/header-style.min.css',
            array( 'foladmarket-fonts' ),
            filemtime( $header_css_path ),
            'all'
        );
        wp_style_add_data( 'foladmarket-header-css', 'lazy', true );
    }

    // 4. Footer CSS - Lazy Load
    $footer_css_path = ASTRA_CHILD_DIR . '/assets/css/min/footer-style.min.css';
    if ( file_exists( $footer_css_path ) ) {
        wp_enqueue_style(
            'foladmarket-footer-css',
            ASTRA_CHILD_URI . '/assets/css/min/footer-style.min.css',
            array( 'foladmarket-fonts' ),
            filemtime( $footer_css_path ),
            'all'
        );
        wp_style_add_data( 'foladmarket-footer-css', 'lazy', true );
    }

    // 5. Header JS - Defer
    $header_js_path = ASTRA_CHILD_DIR . '/assets/js/min/header-script.min.js';
    if ( file_exists( $header_js_path ) ) {
        wp_enqueue_script(
            'foladmarket-header-js',
            ASTRA_CHILD_URI . '/assets/js/min/header-script.min.js',
            array(),
            filemtime( $header_js_path ),
            array(
                'strategy' => 'defer',
                'in_footer' => true
            )
        );
    }

    // 6. Custom Global Fixes CSS (New)
    // این فایل استایل‌های جداول ریسپانسیو را نگه می‌دارد
    $custom_fixes_css = ASTRA_CHILD_DIR . '/assets/css/custom-fixes.css';
    if ( file_exists( $custom_fixes_css ) ) {
        wp_enqueue_style(
            'foladmarket-custom-fixes-css',
            ASTRA_CHILD_URI . '/assets/css/custom-fixes.css',
            array(), // وابستگی ندارد
            filemtime( $custom_fixes_css ),
            'all'
        );
    }

    // 7. Custom Global Fixes JS (New)
    // این فایل جداول را داخل رپر قرار می‌دهد - حتما Defer شود
    $custom_fixes_js = ASTRA_CHILD_DIR . '/assets/js/custom-fixes.js';
    if ( file_exists( $custom_fixes_js ) ) {
        wp_enqueue_script(
            'foladmarket-custom-fixes-js',
            ASTRA_CHILD_URI . '/assets/js/custom-fixes.js',
            array(), // وابستگی خاصی ندارد
            filemtime( $custom_fixes_js ),
            array(
                'strategy' => 'defer', // برای جلوگیری از بلاک شدن رندر
                'in_footer' => true
            )
        );
    }
}

add_action( 'wp_enqueue_scripts', 'foladmarket_global_assets', 10 );

/**
 * Enqueue Parent & Child Theme Styles (Priority: 15)
 */
function astra_child_enqueue_styles() {
    if ( is_admin() ) {
        return;
    }

    // Parent Theme Style
    wp_enqueue_style(
        'astra-parent-style',
        get_template_directory_uri() . '/style.css',
        array(),
        wp_get_theme()->parent()->get( 'Version' )
    );
    
    // Child Theme Style
    wp_enqueue_style(
        'astra-child-style',
        get_stylesheet_uri(),
        array( 'astra-parent-style' ),
        ASTRA_CHILD_VERSION
    );
}
add_action( 'wp_enqueue_scripts', 'astra_child_enqueue_styles', 15 );

/**
 * Enqueue Homepage Specific Assets (Priority: 20)
 * 🚀 همه non-critical هستند و lazy load می‌شوند
 */
function foladmarket_homepage_assets() {
    if ( ! is_homepage_template() ) {
        return;
    }

    // Non-Critical CSS #1 - Lazy Load
    $none_critical_path = ASTRA_CHILD_DIR . '/assets/css/min/none-critical.min.css';
    if ( file_exists( $none_critical_path ) ) {
        wp_enqueue_style(
            'foladmarket-none-critical-css',
            ASTRA_CHILD_URI . '/assets/css/min/none-critical.min.css',
            array(),
            filemtime( $none_critical_path ),
            'all'
        );
        // ✅ علامت‌گذاری برای lazy loading
        wp_style_add_data( 'foladmarket-none-critical-css', 'lazy', true );
    }

    // Non-Critical CSS #2 - Homepage Style - Lazy Load
    $homepage_style_path = ASTRA_CHILD_DIR . '/assets/css/min/homepage-style.min.css';
    if ( file_exists( $homepage_style_path ) ) {
        wp_enqueue_style(
            'foladmarket-homepage-style-css',
            ASTRA_CHILD_URI . '/assets/css/min/homepage-style.min.css',
            array(),
            filemtime( $homepage_style_path ),
            'all'
        );
        // ✅ علامت‌گذاری برای lazy loading
        wp_style_add_data( 'foladmarket-homepage-style-css', 'lazy', true );
    }

    // Homepage JS - Defer با اولویت پایین
    $homepage_js_path = ASTRA_CHILD_DIR . '/assets/js/min/homepage.min.js';
    if ( file_exists( $homepage_js_path ) ) {
        wp_enqueue_script(
            'foladmarket-homepage-js',
            ASTRA_CHILD_URI . '/assets/js/min/homepage.min.js',
            array(),
            filemtime( $homepage_js_path ),
            array(
                'strategy' => 'defer',
                'in_footer' => true
            )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'foladmarket_homepage_assets', 20 );

/**
 * ============================================================================
 * SCRIPT & STYLE OPTIMIZATION
 * ============================================================================
 */

/**
 * 🚀 Lazy Load CSS با preload + onload
 * این تابع CSS های علامت‌گذاری شده را به صورت async بارگذاری می‌کند
 */
function foladmarket_lazy_load_styles( $html, $handle, $href, $media ) {
    // چک کردن محیط
    if ( is_admin() ) {
        return $html;
    }

    // فقط برای homepage
    if ( ! is_page_template( 'template-homepage.php' ) ) {
        return $html;
    }

    // لیست CSS هایی که باید lazy load شوند
    $lazy_handles = array(
        'foladmarket-none-critical-css',
        'foladmarket-homepage-style-css',
        'foladmarket-footer-css',
        'foladmarket-header-css'
    );
    
    // چک کردن اینکه CSS علامت‌گذاری شده است
    $is_lazy = wp_styles()->get_data( $handle, 'lazy' );
    
    if ( ! in_array( $handle, $lazy_handles, true ) && ! $is_lazy ) {
        return $html;
    }

    // تبدیل به preload با fallback
    return sprintf(
        '<link rel="preload" href="%1$s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'" media="%2$s">
        <noscript><link rel="stylesheet" href="%1$s" media="%2$s"></noscript>',
        esc_url( $href ),
        esc_attr( $media )
    );
}
add_filter( 'style_loader_tag', 'foladmarket_lazy_load_styles', 10, 4 );

/**
 * 🚀 Defer/Async JavaScript
 * همه JS های صفحه را defer می‌کند
 */
function foladmarket_defer_scripts( $tag, $handle, $src ) {
    // چک کردن محیط
    if ( is_admin() ) {
        return $tag;
    }

    // فقط برای homepage
    if ( ! is_page_template( 'template-homepage.php' ) ) {
        return $tag;
    }

    // لیست JS هایی که defer می‌شوند
    $defer_handles = array(
        'foladmarket-homepage-js',
        'foladmarket-header-js',
        'wp-embed',
        'hoverintent-js'
    );
    
    if ( in_array( $handle, $defer_handles, true ) ) {
        // اضافه کردن defer اگر وجود ندارد
        if ( strpos( $tag, ' defer' ) === false ) {
            return str_replace( ' src', ' defer src', $tag );
        }
    }
    
    return $tag;
}
add_filter( 'script_loader_tag', 'foladmarket_defer_scripts', 10, 3 );

/**
 * 🚀 Lazy Load JS با requestIdleCallback
 * این تابع در footer اجرا می‌شود و JS های اضافی را تاخیری بارگذاری می‌کند
 */
function foladmarket_lazy_load_scripts() {
    if ( ! is_homepage_template() ) {
        return;
    }
    ?>
    <script>
    (function() {
        'use strict';
        
        // Polyfill برای requestIdleCallback
        window.requestIdleCallback = window.requestIdleCallback || function(cb) {
            var start = Date.now();
            return setTimeout(function() {
                cb({
                    didTimeout: false,
                    timeRemaining: function() {
                        return Math.max(0, 50 - (Date.now() - start));
                    }
                });
            }, 1);
        };

        // تابع بارگذاری تاخیری CSS
        function loadCSS(href, media) {
            var link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = href;
            link.media = media || 'all';
            document.head.appendChild(link);
        }

        // تابع بارگذاری تاخیری JS
        function loadJS(src, callback) {
            var script = document.createElement('script');
            script.src = src;
            script.defer = true;
            if (callback) {
                script.onload = callback;
            }
            document.body.appendChild(script);
        }

        // بارگذاری منابع غیرضروری بعد از idle
        requestIdleCallback(function() {
            // اینجا می‌توانید JS/CSS های اضافی را بارگذاری کنید
            // مثال:
            // loadCSS('<?php echo esc_url( ASTRA_CHILD_URI . '/assets/css/extra.css' ); ?>');
        }, { timeout: 2000 });

    })();
    </script>
    <?php
}
add_action( 'wp_footer', 'foladmarket_lazy_load_scripts', 999 );

/**
 * Remove Query Strings from Static Resources
 */
function foladmarket_remove_query_strings( $src ) {
    if ( is_admin() ) {
        return $src;
    }

    if ( ! is_page_template( 'template-homepage.php' ) ) {
        return $src;
    }
    
    if ( strpos( $src, '?ver=' ) !== false ) {
        $src = remove_query_arg( 'ver', $src );
    }
    
    return $src;
}
add_filter( 'style_loader_src', 'foladmarket_remove_query_strings', 10, 1 );
add_filter( 'script_loader_src', 'foladmarket_remove_query_strings', 10, 1 );

/**
 * ============================================================================
 * CLEANUP & PERFORMANCE - Priority 99-100
 * ============================================================================
 */

/**
 * Dequeue Astra Addon (Priority: 99)
 */
function foladmarket_dequeue_astra_addon() {
    if ( is_admin() ) {
        return;
    }

    if ( ! is_page_template( 'template-homepage.php' ) ) {
        return;
    }
    
    // در صورت نیاز کامنت را بردارید
    // wp_dequeue_style( 'astra-addon-css' );
    // wp_dequeue_script( 'astra-addon-js' );
}
add_action( 'wp_enqueue_scripts', 'foladmarket_dequeue_astra_addon', 99 );

/**
 * Dequeue Unnecessary Assets (Priority: 100)
 */
function foladmarket_dequeue_unnecessary_assets() {
    if ( is_admin() ) {
        return;
    }

    if ( ! is_page_template( 'template-homepage.php' ) ) {
        return;
    }

    // WordPress Core
    $wp_styles = array(
        'wp-block-library',
        'wp-block-library-rtl',
        'wp-block-library-theme',
        'classic-theme-styles',
        'global-styles'
    );
    
    foreach ( $wp_styles as $style ) {
        wp_dequeue_style( $style );
    }

    // Elementor
    $elementor_styles = array(
        'elementor-icons',
        'elementor-common',
        'e-theme-ui-light',
        'elementor-frontend',
        'elementor-post',
        'elementor-global'
    );
    
    foreach ( $elementor_styles as $style ) {
        wp_dequeue_style( $style );
    }

    $elementor_scripts = array(
        'elementor-web-cli',
        'elementor-common-modules',
        'elementor-dialog',
        'elementor-frontend',
        'elementor-frontend-modules'
    );
    
    foreach ( $elementor_scripts as $script ) {
        wp_dequeue_script( $script );
    }

    // Custom Plugins
    $custom_styles = array(
        'tablepress-default',
        'contact-us-product-style',
        'steel-comparison-css',
        'sina-cart-style',
        'isa-stories-style',
        'imagify-admin-bar',
        'yoast-seo-adminbar'
    );
    
    foreach ( $custom_styles as $style ) {
        wp_dequeue_style( $style );
    }

    $custom_scripts = array(
        'steel-comparison-js',
        'sina-cart-script',
        'isa-stories-script',
        'jquery-ui-core',
        'underscore',
        'backbone',
        'react',
        'react-dom',
        'imagify-admin-bar'
    );
    
    foreach ( $custom_scripts as $script ) {
        wp_dequeue_script( $script );
    }
}
add_action( 'wp_enqueue_scripts', 'foladmarket_dequeue_unnecessary_assets', 100 );

/**
 * ============================================================================
 * WORDPRESS CLEANUP
 * ============================================================================
 */

/**
 * Disable Emoji Scripts
 */
function foladmarket_disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
}
add_action( 'init', 'foladmarket_disable_emojis' );

/**
 * Clean Up WordPress Head
 */
function foladmarket_cleanup_head() {
    if ( is_admin() ) {
        return;
    }

    if ( ! is_page_template( 'template-homepage.php' ) ) {
        return;
    }
    
    remove_action( 'wp_head', 'rest_output_link_wp_head' );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'feed_links', 2 );
    remove_action( 'wp_head', 'feed_links_extra', 3 );
    remove_action( 'wp_head', 'wp_generator' );
    remove_action( 'wp_head', 'wlwmanifest_link' );
    remove_action( 'wp_head', 'rsd_link' );
    remove_action( 'wp_head', 'wp_shortlink_wp_head' );
}
add_action( 'init', 'foladmarket_cleanup_head' );

/**
 * ============================================================================
 * THEME SETUP
 * ============================================================================
 */

/**
 * Register Custom Page Templates
 */
function astra_child_custom_templates( $templates ) {
    $templates['template-homepage.php'] = 'Homepage Custom (High Performance)';
    return $templates;
}
add_filter( 'theme_page_templates', 'astra_child_custom_templates' );

/**
 * Include Schema Markup
 */
if ( file_exists( ASTRA_CHILD_DIR . '/inc/schema-markup.php' ) ) {
    require_once ASTRA_CHILD_DIR . '/inc/schema-markup.php';
}


/**
 * ============================================================================
 * Cache Management for Category Schemas
 * ============================================================================
 */

/**
 * Cache clearing wrapper function
 * Uses foladmarket_clear_all_schema_caches() from schema-markup.php
 */
function foladmarket_clear_schema_cache() {
    if (function_exists('foladmarket_clear_all_schema_caches')) {
        return foladmarket_clear_all_schema_caches();
    }
    
    // Fallback
    delete_transient('foladmarket_category_schema_v1');
    delete_transient('foladmarket_breadcrumb_schema_v1');
    delete_transient('foladmarket_organization_schema_v1');
    
    return true;
}

// Hook to clear cache on theme customization
add_action('customize_save_after', 'foladmarket_clear_schema_cache');

/**
 * Add cache clear button to admin bar
 */
add_action('admin_bar_menu', 'foladmarket_add_schema_cache_button', 999);

function foladmarket_add_schema_cache_button($wp_admin_bar) {
    if (!current_user_can('manage_options') || is_admin()) {
        return;
    }
    
    $args = array(
        'id'    => 'foladmarket-clear-cache',
        'title' => '🧹 پاک کردن Cache Schema',
        'href'  => wp_nonce_url(
            add_query_arg('action', 'foladmarket_clear_schema', home_url()),
            'clear_schema_cache_nonce'
        ),
        'meta'  => array(
            'title' => 'پاک کردن کش اسکیماهای JSON-LD',
            'class' => 'foladmarket-cache-clear',
        ),
    );
    
    $wp_admin_bar->add_node($args);
}

/**
 * Handle cache clear request from admin bar
 */
add_action('init', 'foladmarket_handle_cache_clear');

function foladmarket_handle_cache_clear() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'foladmarket_clear_schema') {
        return;
    }
    
    if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'clear_schema_cache_nonce')) {
        wp_die('درخواست نامعتبر است.');
    }
    
    if (!current_user_can('manage_options')) {
        wp_die('شما مجوز انجام این عملیات را ندارید.');
    }
    
    foladmarket_clear_schema_cache();
    
    $redirect_url = add_query_arg('cache_cleared', '1', remove_query_arg(array('action', '_wpnonce')));
    wp_safe_redirect($redirect_url);
    exit;
}

/**
 * Success notice after cache clear
 */
add_action('admin_notices', 'foladmarket_cache_cleared_notice');

function foladmarket_cache_cleared_notice() {
    if (isset($_GET['cache_cleared']) && $_GET['cache_cleared'] === '1') {
        echo '<div class="notice notice-success is-dismissible">';
        echo '<p><strong>✅ کش اسکیماهای JSON-LD با موفقیت پاک شد!</strong></p>';
        echo '</div>';
    }
}

/**
 * Add cache management page to Tools menu
 */
add_action('admin_menu', 'foladmarket_add_cache_tool_page');

function foladmarket_add_cache_tool_page() {
    add_management_page(
        'مدیریت Cache Schema',
        'Cache Schema',
        'manage_options',
        'foladmarket-schema-cache',
        'foladmarket_cache_tool_page_html'
    );
}

function foladmarket_cache_tool_page_html() {
    if (!current_user_can('manage_options')) {
        return;
    }
    
    if (isset($_POST['clear_cache']) && check_admin_referer('foladmarket_clear_cache_action')) {
        foladmarket_clear_schema_cache();
        echo '<div class="notice notice-success"><p><strong>✅ کش با موفقیت پاک شد!</strong></p></div>';
    }
    
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="card" style="max-width: 600px; margin-top: 20px;">
            <h2>مدیریت Cache اسکیماهای JSON-LD</h2>
            <p>این ابزار برای پاک کردن کش داده‌های Schema.org استفاده می‌شود.</p>
            
            <hr>
            
            <h3>اسکیماهای فعال:</h3>
            <ul style="list-style: disc; margin-right: 20px;">
                <li><strong>ItemList Schema</strong> - لیست دسته‌بندی‌ها</li>
                <li><strong>BreadcrumbList Schema</strong> - مسیر صفحه</li>
                <li><strong>Organization Schema</strong> - اطلاعات سازمان</li>
            </ul>
            
            <hr>
            
            <h3>وضعیت Cache:</h3>
            <table class="widefat" style="margin-top: 10px;">
                <thead>
                    <tr>
                        <th>نوع اسکیما</th>
                        <th>وضعیت</th>
                        <th>زمان باقی‌مانده</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $caches = array(
                        'ItemList' => 'foladmarket_category_schema_v1',
                        'BreadcrumbList' => 'foladmarket_breadcrumb_schema_v1',
                        'Organization' => 'foladmarket_organization_schema_v1'
                    );
                    
                    foreach ($caches as $name => $key) {
                        $transient = get_transient($key);
                        $timeout = get_option('_transient_timeout_' . $key);
                        $status = $transient ? '✅ فعال' : '❌ خالی';
                        
                        $remaining = '';
                        if ($timeout) {
                            $seconds = $timeout - time();
                            if ($seconds > 0) {
                                $hours = floor($seconds / 3600);
                                $remaining = $hours > 0 ? $hours . ' ساعت' : floor($seconds / 60) . ' دقیقه';
                            }
                        }
                        
                        echo "<tr><td>{$name}</td><td>{$status}</td><td>{$remaining}</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            
            <hr>
            
            <form method="post" style="margin-top: 20px;">
                <?php wp_nonce_field('foladmarket_clear_cache_action'); ?>
                <button type="submit" name="clear_cache" class="button button-primary button-large">
                    🧹 پاک کردن کش
                </button>
                <p class="description">با کلیک بر روی این دکمه، تمام کش‌های اسکیما پاک می‌شوند.</p>
            </form>
        </div>
        
        <div class="card" style="max-width: 600px; margin-top: 20px; border-right: 4px solid #00a0d2;">
            <h3>ℹ️ توضیحات</h3>
            <ul style="list-style: disc; margin-right: 20px; line-height: 1.8;">
                <li>کش‌ها به صورت خودکار هر <strong>24 ساعت</strong> تمدید می‌شوند</li>
                <li>Organization Schema هر <strong>7 روز</strong> تمدید می‌شود</li>
                <li>پس از هر تغییر در دسته‌بندی‌ها، کش را پاک کنید</li>
                <li>کش‌ها در <code>wp_options</code> با prefix <code>_transient_</code> ذخیره می‌شوند</li>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Admin bar button styling
 */
add_action('wp_head', 'foladmarket_admin_bar_styles');
add_action('admin_head', 'foladmarket_admin_bar_styles');

function foladmarket_admin_bar_styles() {
    if (!is_admin_bar_showing() || !current_user_can('manage_options')) {
        return;
    }
    ?>
    <style>
        #wp-admin-bar-foladmarket-clear-cache .ab-item {
            color: #00a0d2 !important;
            font-weight: 600;
        }
        #wp-admin-bar-foladmarket-clear-cache .ab-item:hover {
            color: #0073aa !important;
            background-color: rgba(0, 160, 210, 0.1) !important;
        }
    </style>
    <?php
}
/**
 * ============================================================================
 * End of Cache Management for Category Schemas
 * ============================================================================
 */
 /**
 * حذف استایل‌ها و اسکریپت‌های Astra از صفحه هوم‌پیج
 * برای بهینه‌سازی عملکرد و کاهش حجم بارگذاری
 */
function remove_astra_assets_from_homepage() {
    // بررسی اینکه آیا در صفحه‌ای با تمپلیت homepage هستیم
    if ( is_page_template( 'template-homepage.php' ) ) {
        
        // حذف استایل‌های Astra
        wp_dequeue_style( 'astra-theme-css' );
        wp_deregister_style( 'astra-theme-css' );
        
        wp_dequeue_style( 'astra-theme-css-rtl' );
        wp_deregister_style( 'astra-theme-css-rtl' );
        
        wp_dequeue_style( 'astra-addon-css' );
        wp_deregister_style( 'astra-addon-css' );
        
        wp_dequeue_style( 'astra-parent-style' );
        wp_deregister_style( 'astra-parent-style' );

        wp_dequeue_style( 'astra-google-fonts' );
        wp_deregister_style( 'astra-google-fonts' );
   

        
        // حذف جاوااسکریپت‌های Astra
        wp_dequeue_script( 'astra-theme-js' );
        wp_deregister_script( 'astra-theme-js' );
        
        wp_dequeue_script( 'astra-addon-js' );
        wp_deregister_script( 'astra-addon-js' );
        
        wp_dequeue_script( 'astra-dom-purify' );
        wp_deregister_script( 'astra-dom-purify' );
    }
}
add_action( 'wp_enqueue_scripts', 'remove_astra_assets_from_homepage', 999 );

/**
 * حذف Inline Styles از Astra
 */
function remove_astra_inline_styles() {
    if ( is_page_template( 'template-homepage.php' ) ) {
        // حذف inline styles
        wp_dequeue_style( 'astra-theme-css-inline-css' );
        wp_dequeue_style( 'astra-addon-css-inline-css' );
    }
}
add_action( 'wp_print_styles', 'remove_astra_inline_styles', 999 );

/**
 * جلوگیری از بارگذاری CSS های غیرضروری با استفاده از فیلتر
 */
function filter_astra_styles( $tag, $handle ) {
    if ( is_page_template( 'template-homepage.php' ) ) {
        $blocked_handles = array(
            'astra-theme-css',
            'astra-theme-css-rtl',
            'astra-addon-css',
            'astra-parent-style',
            'astra-google-fonts' 
        );
        
        if ( in_array( $handle, $blocked_handles ) ) {
            return '';
        }
    }
    return $tag;
}
add_filter( 'style_loader_tag', 'filter_astra_styles', 10, 2 );

/**
 * جلوگیری از بارگذاری JS های غیرضروری با استفاده از فیلتر
 */
function filter_astra_scripts( $tag, $handle ) {
    if ( is_page_template( 'template-homepage.php' ) ) {
        $blocked_handles = array(
            'astra-theme-js',
            'astra-addon-js',
            'astra-dom-purify'
        );
        
        if ( in_array( $handle, $blocked_handles ) ) {
            return '';
        }
    }
    return $tag;
}
add_filter( 'script_loader_tag', 'filter_astra_scripts', 10, 2 );

