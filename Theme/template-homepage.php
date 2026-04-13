<?php
/**
 * Template Name: Homepage Custom (High Performance)
 * Custom high-performance homepage without Elementor
 * @author Sina Sotoudeh
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header(); 

?>

<div id="primary" class="content-area foladmarket-homepage">
    <main id="main" class="site-main" role="main">
        
        <?php
        /**
         * Load Homepage Sections
         * هر بخش به صورت modular لود می‌شود
         */
        
        // Hero Section
        get_template_part( 'template-parts/homepage/hero' );
        
        // Trust Bar
        get_template_part( 'template-parts/homepage/trust-bar' );

        // Partners
        get_template_part( 'template-parts/homepage/partners' );
        
        // Daily Pricing
        set_query_var('pricing_product_ids', '38148,38170,33675,39679,47765,47767,35946');
        get_template_part('template-parts/homepage/pricing');
        
        // Product Categories
        get_template_part( 'template-parts/homepage/categories' );
               
        // Tools Section
        get_template_part( 'template-parts/homepage/tools' );
        
        // USP Section
        get_template_part( 'template-parts/homepage/usp' );
 
        // Articles
        get_template_part( 'template-parts/homepage/articles' );
        
        // FAQ
        get_template_part( 'template-parts/homepage/faq' );
        ?>
        
    </main><!-- #main -->
</div><!-- #primary -->

<?php 
get_footer();
