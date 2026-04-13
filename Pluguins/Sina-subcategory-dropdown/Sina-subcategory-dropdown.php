<?php
/*
Plugin Name: Sina Subcategory Dropdown
Description: Outputs a dropdown of Products subcategories.
Version: 1.0
Author: Sina Sotoudeh
*/

/**
 * subcat-dropdown.php
 * شورت‌کد [subcat_dropdown]
 * نمایش فیلتر آبشاری زیرمجموعه‌ها تا دو سطح با نمایش تعداد.
 */

function fm_subcat_dropdown_shortcode() {
    if ( ! is_category() ) {
        return '';
    }
    $cat = get_queried_object();
    if ( ! $cat || is_wp_error( $cat ) ) {
        return '';
    }

    // فراخوانی زیردسته‌ها تا عمق 2
    $terms = get_terms( [
        'taxonomy'   => 'category',
        'child_of'   => $cat->term_id,
        'hide_empty' => false,
        'depth'      => 2,
    ] );
    if ( is_wp_error( $terms ) || empty( $terms ) ) {
        return '';
    }

    // گروه‌بندی بر اساس parent
    $tree = [];
    foreach ( $terms as $t ) {
        if ( $t->parent == $cat->term_id ) {
            $tree[ $t->term_id ] = [
                'term'     => $t,
                'children' => []
            ];
        }
    }
    foreach ( $terms as $t ) {
        if ( isset( $tree[ $t->parent ] ) ) {
            $tree[ $t->parent ]['children'][] = $t;
        }
    }

    // محاسبه مجموع count برای سطح اول
    foreach ( $tree as &$node ) {
$sum = $node['term']->count;
        foreach ( $node['children'] as $c ) {
            $sum += $c->count;
        }
        $node['sum'] = $sum;
    }
    unset( $node );

    // ساخت HTML
    $html  = '<div class="subcat-dropdown">';
    $html .= '<label class="dropdown-label">فیلتر بر اساس دسته‌های زیرمجموعه:</label>';
    $html .= '<div class="dropdown-field" tabindex="0" role="button" aria-haspopup="true" aria-expanded="false">';
    $html .= '<span class="dropdown-placeholder">انتخاب زیرمجموعه…</span>';
    $html .= '</div>';
    $html .= '<ul class="dropdown-menu" role="menu">';
    foreach ( $tree as $node ) {
        $t = $node['term'];
        // سطح اول
        $html .= '<li class="fm-lvl1" role="none">';
        $html .= sprintf(
            '<a href="%1$s" role="menuitem" class="fm-lvl1-link">%2$s (%3$d)</a>',
            esc_url( get_term_link( $t ) ),
            esc_html( $t->name ),
            intval( $node['sum'] )
        );
        // سطح دوم
        if ( ! empty( $node['children'] ) ) {
            $html .= '<ul class="fm-sub-menu" role="menu">';
            foreach ( $node['children'] as $c ) {
                $html .= sprintf(
                    '<li class="fm-lvl2" role="none"><a href="%1$s" role="menuitem" class="fm-lvl2-link">%2$s (%3$d)</a></li>',
                    esc_url( get_term_link( $c ) ),
                    esc_html( $c->name ),
                    intval( $c->count )
                );
            }
            $html .= '</ul>';
        }
        $html .= '</li>';
    }
    $html .= '</ul>';
    $html .= '</div>';

    return $html;
}
add_shortcode( 'subcat_dropdown', 'fm_subcat_dropdown_shortcode' );

add_action( 'wp_enqueue_scripts', function(){
    if ( ! is_category() ) {
        return;
    }
        // Plugin base paths
    $dir = plugin_dir_path(__FILE__);
    $uri = plugin_dir_url(__FILE__);
    // CSS
    $css = $dir . 'assets/css/subcat-dropdown.css';
    if ( file_exists($css) ) {
        wp_enqueue_style(
            'fm-subcat-dropdown',
            $uri . 'assets/css/subcat-dropdown.css',
            [],
            filemtime( $css )
        );
    }
    // JS
    wp_enqueue_script(
        'fm-subcat-dropdown-js',
        $uri . 'assets/js/subcat-dropdown.js',
        [],
        null,
        true
    );
});