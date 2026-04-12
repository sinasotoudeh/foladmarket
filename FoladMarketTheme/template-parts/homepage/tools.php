<?php
/**
 * Template Part: Homepage Tools Section
 * Optimized for Performance & SEO
 * 
 * @package Astra Child
 * @since 1.0.0
 * @author Sina Sotoudeh

 */

// Preload critical resources
add_action('wp_head', function() {
    if (is_front_page()) {
        echo '<link rel="preload" as="style" href="' . get_stylesheet_directory_uri() . '/assets/css/tools-section.min.css" />';
    }
}, 5);
?>

<!-- Tools Section - SEO Optimized -->
<section class="tools-section" id="tools">
    <div class="container">
        
        <!-- Section Header with Semantic Structure -->
        <header class="section-header">
            <h2 class="section-title">
                ابزارهای تخصصی فولادمارکت برای مهندسان و خریداران
            </h2>
            <p class="section-description">
                استفاده از ابزارهای پیشرفته فولادمارکت به شما کمک می‌کند تا با دقت بالاتر و سرعت بیشتر، بهترین تصمیم خرید را بگیرید
            </p>
        </header>

        <!-- Tools Grid with Progressive Enhancement -->
        <div class="tools-grid" role="list">
            
            <?php
            // Tools Data Array - Easy to manage and extend
            $tools = [
                [
                    'id' => 'weight-calculator',
                    'icon' => '⚖️',
                    'title' => 'ماشین حساب وزن فولاد',
                    'description' => 'محاسبه دقیق وزن انواع میلگرد، تسمه، ورق و مقاطع فولادی بر اساس ابعاد استاندارد و چگالی مواد',
                    'features' => [
                        'محاسبه آنی با دقت 99.9%',
                        'پشتیبانی از 50+ نوع مقطع',
                        'تخمین هزینه براساس قیمت روز'
                    ],
                    'url' => '/weight-calculator/',
                    'cta' => 'محاسبه وزن',
                    'position' => 1
                ],
                [
                    'id' => 'steel-comparison',
                    'icon' => '🔍',
                    'title' => 'مقایسه‌گر گریدهای فولادی',
                    'description' => 'مقایسه جامع ترکیب شیمیایی، خواص مکانیکی، سختی و معادل‌های بین‌المللی گریدهای مختلف فولاد',
                    'features' => [
                        'مقایسه همزمان تا 10 گرید',
                        'بررسی ترکیبات عنصری و آلیاژی',
                        'نمایش معادل‌های ASTM, DIN, JIS'
                    ],
                    'url' => '/steel-comparison/',
                    'cta' => 'شروع مقایسه',
                    'position' => 2
                ],
                [
                    'id' => 'price-quote',
                    'icon' => '💰',
                    'title' => 'سامانه استعلام قیمت آنلاین',
                    'description' => 'دریافت قیمت لحظه‌ای و رقابتی فولاد با ثبت درخواست آنلاین و دریافت پاسخ سریع از کارشناسان فروش',
                    'features' => [
                        'پاسخ‌گویی حداکثر 2 ساعت کاری',
                        'قیمت‌گذاری شفاف بدون هزینه مخفی',
                        'مشاوره تخصصی رایگان'
                    ],
                    'url' => '/price/',
                    'cta' => 'ثبت استعلام',
                    'position' => 3
                ]
            ];

            // Loop through tools and render cards
            foreach ($tools as $tool):
            ?>
            
            <article 
                class="tool-card" 
                data-tool="<?php echo esc_attr($tool['id']); ?>"
                role="listitem"
            >
                
                <!-- Tool Icon with ARIA -->
                <div class="tool-icon" aria-hidden="true">
                    <span class="icon-emoji" role="img" aria-label="<?php echo esc_attr($tool['title']); ?>">
                        <?php echo $tool['icon']; ?>
                    </span>
                </div>

                <!-- Tool Title -->
                <h3 class="tool-title">
                    <?php echo esc_html($tool['title']); ?>
                </h3>

                <!-- Tool Description -->
                <p class="tool-description">
                    <?php echo esc_html($tool['description']); ?>
                </p>

                <!-- Tool Features List -->
                <ul class="tool-features">
                    <?php foreach ($tool['features'] as $feature): ?>
                    <li>
                        <span class="feature-bullet" aria-hidden="true">•</span>
                        <span><?php echo esc_html($feature); ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>

                <!-- CTA Button with SEO Attributes -->
                <a 
                    href="<?php echo esc_url($tool['url']); ?>" 
                    class="tool-cta"
                    aria-label="<?php echo esc_attr($tool['cta'] . ' - ' . $tool['title']); ?>"
                    rel="nofollow"
                >
                    <span><?php echo esc_html($tool['cta']); ?></span>
                    <span class="cta-arrow" aria-hidden="true">←</span>
                </a>

            </article>

            <?php endforeach; ?>

        </div><!-- .tools-grid -->

    </div><!-- .container -->
</section><!-- .tools-section -->
