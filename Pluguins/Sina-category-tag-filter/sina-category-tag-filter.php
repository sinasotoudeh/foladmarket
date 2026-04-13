<?php
/*
Plugin Name: Sina Category-Tag Filter
Description: Provides a tag filter for products and maps tags to categories.
Version: 1.0
Author: Sina Sotoudeh
*/

// آرشیو محصولات
// تابع نمایش پست ها در صفحات دسته بندی بر اساس مقدار وارد شده در فیلد category-coid
//  در ادامه مشخص شده هر دسته بر اساس چه تگ هایی قابل تفکیک است (براساس برچسب  های هر پست که مپینگ شده)
add_action( 'elementor/query/product_tag', 'debug_all_custom_fields_frontend', 10, 2 );
function debug_all_custom_fields_frontend( $query, $widget ) {
    $post_id = get_queried_object_id();
    $all_fields = get_post_meta( $post_id );
        $coid = $all_fields['category_coid'][0];
    $mapping = [
        'abzari'     => [ 'hotwork', 'coldwork','hss' ],
        'rangi'      => [ 'aluminum','brass','copper','phosphorbronze' ],
        'sakhtemani' => [ 's-profile','s-tube','s-plate' ],
        'semante'    => [ 'cementation' ],
        'fanar'      => [ 'spring' ],
        'heat'       => [ 'heat-treatable', 'vcn','ck45' ],
        'carbon'     => [ 'high-carbon','ar-plate'],
        'steel'      => [ 'austenitic', 'ferritic','304','309','310','316','321','420','430','roundbar','tube','hexagonalbar','plate' ],
        'products'   => [ 'austenitic', 'ferritic','304','309','310','316','321','420','430','roundbar','tube','hexagonalbar','plate','hotwork', 'coldwork','hss','heat-treatable','vcn','ck45','high-carbon','ar-plate','cementation', 'spring', 'aluminum','brass','copper','phosphorbronze', 's-profile','s-tube','s-plate',  ],
    ];
    
    if ( !empty( $mapping[ $coid ] ) ) {
        $tag_slugs = $mapping[ $coid ];

         $query->set( 'tag_slug__in',   $tag_slugs );
        $query->set( 'post_type',      'product' );
      $query->set( 'posts_per_page', -1 );              
    }
}

//  مپینگ برای نام فارسی تگ های هر دسته بندی
function fm_get_tag_mapping() {
    return [
        'abzari'     => [
            'hotwork'  => 'فولادهای ابزاری گرمکار',
            'coldwork' => 'فولادهای ابزاری سردکار',
            'hss'      => 'فولادهای ابزاری تندبر',
        ],
        'rangi'      => [
            'aluminum' => 'آلومینیوم',
            'brass' => 'برنج',
            'copper' => 'مس',
            'phosphorbronze' => 'فسفربرنز',
        ],
        'sakhtemani' => [
            's-profile'  => 'پروفیل های گالوانیزه',
            's-plate'  => 'ورق های گالوانیزه',
            's-tube'  => 'لوله های گالوانیزه',
        ],
        'semante'    => [
            'cementation' => 'فولادهای سمانتاسیون',
        ],
        'fanar'      => [
            'spring'      => 'فولادهای فنر',
        ],
        'heat'       => [
            'heat-treatable' => 'فولادهای قابل عملیات حرارتی',
            'vcn' => 'VCN',
            'ck45' => 'CK45',


        ],
        'carbon'     => [
            'high-carbon'    => 'فولادهای کربنی',
            'ar-plate'    => 'ورق های ضدسایش',
        ],
        'steel'      => [
            'ferritic'       => 'استیل های بگیر',
            'austenitic'     => 'استیل های نگیر',
            'plate'     => 'ورق',
            'roundbar'     => 'میلگرد',
            'tube'     => 'لوله',
            'hexagonalbar'     => 'ششپر',
            '304'     => '304',
            '309'     => '309',
            '310'     => '310',
            '316'     => '316',
            '321'     => '321',
            '420'     => '420',
            '430'     => '430',
        ],
        'products'    => [
            'ferritic'       => 'استیل های بگیر',
            'austenitic'     => 'استیل های نگیر',
            'plate'     => 'ورق',
            'roundbar'     => 'میلگرد',
            'tube'     => 'لوله',
            'hexagonalbar'     => 'ششپر',
            '304'     => '304',
            '309'     => '309',
            '310'     => '310',
            '316'     => '316',
            '321'     => '321',
            '420'     => '420',
            '430'     => '430',
            'hotwork'  => 'فولادهای ابزاری گرمکار',
            'coldwork' => 'فولادهای ابزاری سردکار',
            'hss'      => 'فولادهای ابزاری تندبر',
            'heat-treatable' => 'فولادهای قابل عملیات حرارتی',
            'vcn' => 'VCN',
            'ck45' => 'CK45',
            'high-carbon'    => 'فولادهای کربنی',
            'ar-plate'    => 'ورق های ضدسایش',
            'cementation' => 'فولادهای سمانتاسیون',
            'spring'      => 'فولادهای فنر', 
            'aluminum' => 'آلومینیوم',
            'brass' => 'برنج',
            'copper' => 'مس',
            'phosphorbronze' => 'فسفربرنز',
            's-profile'  => 'پروفیل های گالوانیزه',
            's-plate'  => 'ورق های گالوانیزه',
            's-tube'  => 'لوله های گالوانیزه',
        ],
    ];
}


//  تابع تولید شورتکد اچ تی ام ال فیلتر بر اساس تگ
function fm_category_tag_filter_shortcode() {
    $post_id = get_queried_object_id();
    $all_fields = get_post_meta( $post_id );
    $coid = $all_fields['category_coid'][0] ?? '';
    $mapping = fm_get_tag_mapping();

    if ( ! isset( $mapping[ $coid ] ) ) {
        return ''; // اگر گروهی تعریف نشده بود
    }

    // لیست تگ‌ها برچسب‌های نمایشی
    $tags = $mapping[ $coid ];

    // تعیین فیلتر انتخاب شده (از پارامتر URL)
    $current = isset( $_GET['tag_filter'] ) ? sanitize_text_field( $_GET['tag_filter'] ) : 'all';

    // شروع خروجی
    $html  = '<div class="tag-filter-buttons">';
    // دکمه «همه»
    $active = ( $current === 'all' ) ? ' active' : '';
    $html .= '<a href="' . esc_url( remove_query_arg('tag_filter') ) . '" class="filter-button' . $active . '">همه</a>';

    // بقیه دکمه‌ها
    foreach ( $tags as $slug => $label ) {
        $active = ( $current === $slug ) ? ' active' : '';
        $url    = esc_url( add_query_arg( 'tag_filter', $slug ) );
        $html  .= '<a href="' . $url . '" class="filter-button' . $active . '">' . esc_html( $label ) . '</a>';
    }

    $html .= '</div>';

    return $html;
}
add_shortcode( 'category_tag_filter', 'fm_category_tag_filter_shortcode' );

// به‌روزرسانی کوئری المنتور بر اساس فیلتر
add_action( 'elementor/query/product_tag', 'custom_query_with_tag_filter', 10, 2 );
function custom_query_with_tag_filter( $query, $widget ) {
    $post_id = get_queried_object_id();
    $all_fields = get_post_meta( $post_id );
    $coid = $all_fields['category_coid'][0] ?? '';
    $mapping = fm_get_tag_mapping();

    if ( isset( $mapping[ $coid ] ) ) {
        $tags = array_keys( $mapping[ $coid ] );
        $filter = isset( $_GET['tag_filter'] ) && in_array( $_GET['tag_filter'], $tags )
            ? [ sanitize_text_field( $_GET['tag_filter'] ) ]
            : $tags;

        $query->set( 'tag_slug__in',   $filter );
        $query->set( 'post_type',      'product' );
        $query->set( 'posts_per_page', -1 );
    }
}