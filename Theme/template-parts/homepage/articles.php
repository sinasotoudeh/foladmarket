<?php
/**
 * Featured Articles - Ultra Compact Auto-Rotate (Final Version)
 * @package Astra Child
 * @author Sina Sotoudeh
 */

if (!defined('ABSPATH')) exit;

$articles_query = new WP_Query(array(
    'post_status'    => 'publish',
    'posts_per_page' => 6,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'tax_query'      => array(
        array(
            'taxonomy' => 'special_article_category',
            'field'    => 'slug',
            'terms'    => 'blogs',
        ),
    ),
));

if (!$articles_query->have_posts()) return;

$articles = array();
while ($articles_query->have_posts()) {
    $articles_query->the_post();
    $post_id = get_the_ID();
    
    $articles[] = array(
        'id'      => $post_id,
        'title'   => get_the_title(),
        'excerpt' => wp_trim_words(get_the_excerpt() ?: get_the_content(), 60), // 60 کلمه (3 برابر قبل)
        'image'   => get_the_post_thumbnail_url($post_id, 'large') ?: 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" width="800" height="450"%3E%3Crect fill="%23f3b50d" width="800" height="450"/%3E%3C/svg%3E',
        'link'    => get_permalink(),
        'date'    => get_the_date('j F Y'),
        'cat'     => get_the_terms($post_id, 'special_article_category')[0]->name ?? 'مقاله',
    );
}
wp_reset_postdata();
?>

<section class="fma-slim" id="fma-articles">
    <div class="fma-container">
        
        <!-- Header -->
        <div class="fma-header">
            <h2 class="fma-h2">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
                مقالات تخصصی و محتوای آموزشی
            </h2>
            <p class="fm-subtitle">
            دسترسی به دانش تخصصی و به‌روز در حوزه فولاد و متالورژی برای تصمیم‌گیری بهتر
            </p>
            <a href="<?php echo home_url('/articles/'); ?>" class="fma-all">
                همه مقالات →
            </a>
        </div>

        <!-- Two Column Layout -->
        <div class="fma-layout">
            
            <!-- Main: Featured Article -->
            <article class="fma-main" id="fma-featured" data-current="0">
                <a href="<?php echo esc_url($articles[0]['link']); ?>" class="fma-main-link" id="fma-main-link">
                    <div class="fma-main-img-wrap">
                        <img src="<?php echo esc_url($articles[0]['image']); ?>" 
                             alt="<?php echo esc_attr($articles[0]['title']); ?>"
                             class="fma-main-img" id="fma-main-img" loading="eager">
                        <span class="fma-cat" id="fma-main-cat"><?php echo esc_html($articles[0]['cat']); ?></span>
                    </div>
                    <div class="fma-main-content">
                        <time class="fma-time" id="fma-main-time"><?php echo esc_html($articles[0]['date']); ?></time>
                        <h3 class="fma-h3" id="fma-main-title"><?php echo esc_html($articles[0]['title']); ?></h3>
                        <p class="fma-excerpt" id="fma-main-excerpt"><?php echo esc_html($articles[0]['excerpt']); ?></p>
                    </div>
                </a>
            </article>

            <!-- Sidebar: Article List -->
            <aside class="fma-sidebar">
                <?php foreach($articles as $i => $article): ?>
                <button class="fma-item <?php echo $i === 0 ? 'fma-active' : ''; ?>" 
                     data-index="<?php echo $i; ?>"
                     data-title="<?php echo esc_attr($article['title']); ?>"
                     data-excerpt="<?php echo esc_attr($article['excerpt']); ?>"
                     data-image="<?php echo esc_url($article['image']); ?>"
                     data-link="<?php echo esc_url($article['link']); ?>"
                     data-date="<?php echo esc_attr($article['date']); ?>"
                     data-cat="<?php echo esc_attr($article['cat']); ?>">
                    
                    <!-- Arrow -->
                    <span class="fma-arrow">
                        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor">
                            <path d="M12 4l-8 8 8 8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                    
                    <!-- Text -->
                    <div class="fma-text">
                        <span class="fma-item-title"><?php echo esc_html($article['title']); ?></span>
                        <span class="fma-item-time"><?php echo esc_html($article['date']); ?></span>
                    </div>
                    
                    <!-- Icon -->
                    <div class="fma-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                        </svg>
                    </div>
                </button>
                <?php endforeach; ?>
            </aside>

        </div>
    </div>
</section>
