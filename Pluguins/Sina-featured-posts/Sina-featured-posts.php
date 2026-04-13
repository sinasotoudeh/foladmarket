<?php
/*
Plugin Name: Sina Featured Posts
Description: Adds a featured posts slider via shortcode
Version: 1.0
Author: Sina Sotoudeh
*/

// Pegalo en functions.php del child theme o en un plugin propio

add_action('init', 'fm_register_featured_assets');
function fm_register_featured_assets() {
    // Base paths for plugin
    $dir = plugin_dir_path(__FILE__);
    $uri = plugin_dir_url(__FILE__);

    // CSS
    $css = $dir . 'assets/css/fm-featured.css';
    if ( file_exists($css) ) {
        wp_register_style(
            'fm-featured-style',
            $uri . 'assets/css/fm-featured.css',
            array(),
            filemtime($css)
        );
    }

    // JS
    $js = $dir . 'assets/js/fm-featured.js';
    if ( file_exists($js) ) {
        wp_register_script(
            'fm-featured-script',
            $uri . 'assets/js/fm-featured.js',
            array(),
            filemtime($js),
            true
        );
    }
}

add_shortcode('fm_featured_posts', 'fm_featured_posts_shortcode');
function fm_featured_posts_shortcode($atts) {
    $atts = shortcode_atts( array(
        'posts'      => 5,
        'rotation'   => 3000,   // milisegundos
        'image_size' => 'large'
    ), $atts, 'fm_featured_posts');

$args = array(
    'post_status'    => 'publish',
    'posts_per_page' => intval($atts['posts']),
    'tax_query'      => array(
        array(
            'taxonomy' => 'special_article_category', // اینجا slug تاکسونومی خودت
            'field'    => 'slug',
            'terms'    => 'blogs',   // اینجا slug ترم موردنظر
        ),
    ),
);

    $q = new WP_Query($args);
    if (!$q->have_posts()) return '<!-- fm_featured_posts: no posts -->';

    $posts_data = array();
    foreach ($q->posts as $post) {
        $title = get_the_title($post);
        $excerpt = has_excerpt($post) ? get_the_excerpt($post) : wp_trim_words( wp_strip_all_tags( $post->post_content ), 40, '...' );
        $image = get_the_post_thumbnail_url($post, $atts['image_size']);
        if (!$image) $image = 'https://via.placeholder.com/800x450?text=No+Image';
        $link = get_permalink($post);

        $posts_data[] = array(
            'title'   => $title,
            'excerpt' => $excerpt,
            'image'   => $image,
            'link'    => $link
        );
    }

    // ID único para soportar múltiples instancias
    $uid = 'fm-featured-' . uniqid();

    // Encolamos assets
    wp_enqueue_style('fm-featured-style');
    wp_enqueue_script('fm-featured-script');

    // Pasamos datos a JS (por instancia)
    $init = 'window.fmFeaturedInit = window.fmFeaturedInit || []; window.fmFeaturedInit.push('
          . wp_json_encode( array(
                'id' => $uid,
                'rotation' => intval($atts['rotation']),
                'posts' => $posts_data
            ) ) . ');';
    wp_add_inline_script('fm-featured-script', $init);

    // HTML inicial (server-side) usando el primer post
    $first = $posts_data[0];
    $out  = '<div id="' . esc_attr($uid) . '" class="fm-featured-posts" data-rotation="' . intval($atts['rotation']) . '">';
    $out .= '  <ul class="fm-post-list">';
    foreach ($posts_data as $i => $p) {
        $active = $i === 0 ? ' fm-active' : '';
        $out .= '<li class="fm-post-item' . $active . '" data-index="' . intval($i) . '">';
        $out .= '  <button class="fm-post-button" type="button">' . esc_html($p['title']) . '</button>';
        $out .= '</li>';
    }
    $out .= '  </ul>';

    $out .= '  <div class="fm-featured-display">';
    $out .= '    <a class="fm-featured-link" href="' . esc_url($first['link']) . '">';
    $out .= '      <img class="fm-featured-image" src="' . esc_url($first['image']) . '" alt="' . esc_attr($first['title']) . '" loading="lazy">';
    $out .= '      <h3 class="fm-featured-title">' . esc_html($first['title']) . '</h3>';
    $out .= '      <p class="fm-featured-excerpt">' . esc_html( $first['excerpt'] ) . '</p>';
    $out .= '    </a>';
    $out .= '  </div>';

    $out .= '</div>';

    return $out;
}