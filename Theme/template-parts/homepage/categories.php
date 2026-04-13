<?php
/**
 * Template Part: Product Categories Section - Premium Edition v3.2
 * SEO-optimized product categories display
 * @package Astra Child
 * @version 3.2.0
 * @author Sina Sotoudeh
 */

// Security: Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Categories data array
$categories = array(
    array(
        'title' => 'استنلس استیل',
        'title_en' => 'Stainless Steel',
        'slug' => 'stainless-steel',
        'url' => home_url('/stainless-steel/'),
        'image' => 'stainless-steel.webp',
        'description' => 'فولادهای ضد زنگ با مقاومت استثنایی در برابر خوردگی و استحکام بالا. مناسب صنایع غذایی، پزشکی، شیمیایی و سازه‌های ساحلی با قیمت رقابتی و گواهی اصالت.',
        'applications' => 'تجهیزات پزشکی، صنایع غذایی، ساخت مخازن، سازه‌های ساحلی',
        'grades' => '1.4301 (304), 1.4401 (316), 1.4404 (316L), 1.4016 (430), 1.4021 (420), 1.4057 (431), 1.4541 (321), 1.4571 (316Ti)',
        'product_count' => 156,
        'popularity' => 95
    ),
    array(
        'title' => 'فولاد ابزار سردکار',
        'title_en' => 'Cold Work Tool Steel',
        'slug' => 'cold-work-steel',
        'url' => home_url('/tool-steel/coldwork/'),
        'image' => 'cold-work-steel.webp',
        'description' => 'فولادهای ابزار با سختی فوق‌العاده برای قالب‌سازی دقیق و ابزارهای برش فلزات. مقاومت سایشی برتر با واردات مستقیم و تضمین استاندارد DIN.',
        'applications' => 'قالب‌های تزریق پلاستیک، پانچ و ماتریس، ابزار برش دقیق فلزات',
        'grades' => '1.2379 (D2), 1.2080 (D3), 1.2510 (O1), 1.2842 (O2), 1.2363 (A2), 1.2767 (Caldie), 1.2601 (Rigor), 1.2436 (Vanadis 4)',
        'product_count' => 89,
        'popularity' => 88
    ),
    array(
        'title' => 'فولاد ابزار گرمکار',
        'title_en' => 'Hot Work Tool Steel',
        'slug' => 'hot-work-steel',
        'url' => home_url('/tool-steel/hotwork/'),
        'image' => 'hot-work-steel.webp',
        'description' => 'فولادهای مخصوص کار در دماهای بالا با مقاومت حرارتی و چقرمگی استثنایی. ایده‌آل برای قالب‌های دایکاست و فورج با استانداردهای بین‌المللی.',
        'applications' => 'قالب‌های دایکاست آلومینیوم، قالب‌های فورج، اکستروژن فلزات',
        'grades' => '1.2344 (H13), 1.2343 (H11), 1.2365 (H10), 1.2367 (H21), 1.2681 (SKD61), 1.2714 (L6), 1.2842 (H19), 1.2606 (W302)',
        'product_count' => 72,
        'popularity' => 92
    ),
    array(
        'title' => 'فولاد ابزار تندبر',
        'title_en' => 'High Speed Steel (HSS)',
        'slug' => 'high-speed-steel',
        'url' => home_url('/tool-steel/hs/'),
        'image' => 'high-speed-steel.webp',
        'description' => 'فولادهای تندبر با حفظ سختی در دماهای بالا برای ابزارهای برشی CNC و صنعتی. کیفیت اروپایی با قیمت مناسب و تحویل سریع.',
        'applications' => 'مته‌های صنعتی، فرز CNC، اره‌های برش، ابزار تراشکاری پیشرفته',
        'grades' => '1.3343 (M2), 1.3243 (M35), 1.3247 (M42), 1.3355 (T1), 1.3202 (T15), 1.3207 (SKH51), 1.3505 (SKH9), 1.3257 (PM-M4)',
        'product_count' => 64,
        'popularity' => 85
    ),
    array(
        'title' => 'فولاد سمانتاسیون',
        'title_en' => 'Case Hardening Steel',
        'slug' => 'cementation-steel',
        'url' => home_url('/cementation/'),
        'image' => 'cementation-steel.webp',
        'description' => 'فولادهای قابل سخت‌کاری سطحی برای قطعات با هسته چقرمه و سطح سخت. ویژه ساخت چرخ‌دنده و محورهای صنعتی با مشاوره رایگان عملیات حرارتی.',
        'applications' => 'چرخ‌دنده‌های صنعتی، شفت‌ها، پین‌ها، قطعات خودرو',
        'grades' => '1.7131 (16MnCr5), 1.7147 (20MnCr5), 1.6587 (18CrNiMo7-6), 1.5920 (20CrMo5), 1.7225 (42CrMo4), 1.7321 (25CrMo4), 1.6523 (20MoCr4), 1.5752 (17CrNi6-6)',
        'product_count' => 45,
        'popularity' => 78
    ),
    array(
        'title' => 'فولاد قالب پلاستیک',
        'title_en' => 'Plastic Mold Steel',
        'slug' => 'plastic-mold-steel',
        'url' => home_url('/plastic-mold-steel/'),
        'image' => 'plastic-mold-steel.webp',
        'description' => 'فولادهای ویژه قالب‌سازی با قابلیت پولیش آینه‌ای و یکنواختی ابعادی عالی. تامین P20، NAK80 و 718 با کیفیت تضمینی و خدمات برش.',
        'applications' => 'قالب‌های تزریق پلاستیک، بادی، قطعات اپتیک، لنزهای پلاستیکی',
        'grades' => '1.2311 (P20), 1.2312 (P20+S), 1.2738 (718), 1.2083 (420), 1.2316 (S136), 1.2085 (NAK80), 1.2767 (Stavax), 1.2842 (Corrax)',
        'product_count' => 58,
        'popularity' => 82
    ),
    array(
        'title' => 'فولاد عملیات حرارتی',
        'title_en' => 'Heat Treatable Steel',
        'slug' => 'heat-treatable-steel',
        'url' => home_url('/heat-treatable/'),
        'image' => 'heat-treatable-steel.webp',
        'description' => 'فولادهای آلیاژی کوئنچ و تمپر برای دستیابی به استحکام و سختی بهینه. مناسب محورها و قطعات ماشین‌آلات سنگین با خدمات عملیات حرارتی.',
        'applications' => 'محورها، شفت‌های صنعتی، قطعات ماشین‌آلات سنگین، تجهیزات معدنی',
        'grades' => '1.7225 (42CrMo4), 1.6511 (4140), 1.6582 (34CrNiMo6), 1.0503 (C45), 1.1191 (C60), 1.0050 (ST52), 1.7035 (41Cr4), 1.2842 (50CrMo4)',
        'product_count' => 94,
        'popularity' => 90
    ),
    array(
        'title' => 'فولاد فنر',
        'title_en' => 'Spring Steel',
        'slug' => 'spring-steel',
        'url' => home_url('/spring-steel/'),
        'image' => 'spring-steel.webp',
        'description' => 'فولادهای با الاستیسیته بالا برای ساخت فنرهای صنعتی و خودرویی. ورق و میل فنری با کیفیت استاندارد اروپا و قیمت عمده.',
        'applications' => 'فنرهای خودرو، فنرهای صنعتی، تیغه‌های فنری، کلاچ خودرو',
        'grades' => '1.8159 (51CrV4), 1.7108 (50CrV4), 1.0905 (SUP9), 1.1274 (SUP10), 1.1191 (65Mn), 1.7176 (55Cr3), 1.8401 (60Si7), 1.5026 (54SiCr6)',
        'product_count' => 38,
        'popularity' => 75
    ),
    array(
        'title' => 'فولاد کربنی',
        'title_en' => 'High Carbon Steel',
        'slug' => 'high-carbon-steel',
        'url' => home_url('/high-carbon/'),
        'image' => 'high-carbon-steel.webp',
        'description' => 'فولادهای کربن بالا با قابلیت سخت‌شوندگی عالی برای ابزارآلات و کاربردهای صنعتی. تامین با گواهینامه کیفیت معتبر و تست متالوژیکی.',
        'applications' => 'ابزارآلات دستی، تیغه‌های برش صنعتی، قطعات ماشین‌کاری دقیق',
        'grades' => '1.1191 (CK60), 1.1231 (CK67), 1.0601 (C60E), 1.1274 (CS70), 1.0603 (C80E), 1.1525 (C100W2), 1.1545 (C105W2), 1.0535 (C55E)',
        'product_count' => 67,
        'popularity' => 80
    ),
    array(
        'title' => 'ورق ضد سایش',
        'title_en' => 'Abrasion Resistant Plate',
        'slug' => 'ar-plate',
        'url' => home_url('/high-carbon/ar-plate/'),
        'image' => 'ar-plate.webp',
        'description' => 'ورق‌های با سختی فوق‌العاده و مقاومت برتر در برابر سایش برای صنایع سنگین و معدنی. Hardox و AR با امکان برش و خم و خدمات مهندسی.',
        'applications' => 'تجهیزات معدنی، کفی کامیون، شوت‌های سنگ‌شکن، بیل مکانیکی',
        'grades' => 'Hardox 400, Hardox 450, Hardox 500, Hardox 550, Hardox 600, AR400, AR500, AR550',
        'product_count' => 41,
        'popularity' => 87
    ),
    array(
        'title' => 'فلزات رنگی',
        'title_en' => 'Non-Ferrous Metals',
        'slug' => 'non-ferrous',
        'url' => home_url('/non-ferrous/'),
        'image' => 'non-ferrous.webp',
        'description' => 'برنج، آلومینیوم، مس و آلیاژهای غیرآهنی با کیفیت بالا. تنوع سایز کامل و قیمت‌های رقابتی برای صنایع برق، الکترونیک و معماری.',
        'applications' => 'صنایع برق، الکترونیک، تزئینات معماری، اتصالات لوله‌کشی',
        'grades' => 'CuZn37 (برنج 63-37), CuZn40 (برنج 60-40), EN AW-6061, EN AW-6082, Cu-ETP (مس الکترولیت), CuSn8 (برنز فسفری), CuNi10Fe (کوپرونیکل), AlMg3',
        'product_count' => 112,
        'popularity' => 83
    ),
    array(
        'title' => 'فولاد ساختمانی',
        'title_en' => 'Structural Steel',
        'slug' => 'structural-steel',
        'url' => home_url('/structural-steel/'),
        'image' => 'structural-steel.webp',
        'description' => 'تیرآهن، ناودانی، میلگرد و پروفیل‌های فولادی برای پروژه‌های عمرانی و صنعتی. تامین با استانداردهای ملی، تحویل در محل و برش رایگان.',
        'applications' => 'سازه‌های فلزی، اسکلت ساختمان، پل‌های فولادی، سوله‌های صنعتی',
        'grades' => '1.0037 (ST37), 1.0044 (ST44), 1.0050 (ST52), 1.0038 (S235JR), 1.0116 (S275J0), 1.0577 (S355J2), A36, A572 Gr50',
        'product_count' => 203,
        'popularity' => 94
    )
);
?>

<div class="contentstyletype2">
    <div class="content-title">
        <h3>تنوع محصولات فولادمارکت</h3>
    </div>
    <div class="content-text">
        <p>
            فولادمارکت طیف گسترده‌ای از محصولات فولادی را شامل <strong>فولادهای ابزاری</strong> (Tool Steel) مانند ۱.۲۳۴۴، ۱.۲۳۷۹ و ۱.۲۰۸۰، <strong>استنلس استیل</strong> در گریدهای ۳۰۴، ۳۱۶ و ۴۲۰، <strong>فولادهای سمانتاسیون</strong> برای چرخ‌دنده و شفت، <strong>فولادهای کربنی</strong> نظیر CK45 و ST52، و همچنین <strong>فلزات رنگی</strong> شامل آلومینیوم، مس، برنز و برنج در اختیار مشتریان قرار می‌دهد. این تنوع محصول به مهندسان و خریداران صنعتی امکان می‌دهد تا تمام نیازهای خود را از یک منبع معتبر تامین کنند.
        </p>
    </div>
</div>

<section 
    class="product-categories-section" 
    id="categories"
    role="region"
    aria-labelledby="categories-title"
>
    
    <div class="container">
        
        <!-- Section Header -->
        <header class="section-header">
            <span class="section-label" aria-hidden="true">محصولات</span>
            <h2 id="categories-title" class="section-title">
                دسته‌بندی <span class="text-gradient-gold">محصولات فولادمارکت</span>
            </h2>
            <p class="section-subtitle">
                تامین‌کننده کامل‌ترین رنج فولادهای صنعتی از فولادهای ابزاری تا استنلس استیل و فلزات رنگی برای انواع کاربردهای صنعتی و ساختمانی 
            </p>
           </header>

        <!-- Categories Grid -->
        <div class="categories-grid" role="list">
            <?php foreach ($categories as $index => $cat): ?>
            <article 
                class="category-card" 
                role="listitem"
                data-category="<?php echo esc_attr($cat['slug']); ?>"
            >
                
                <div class="category-card-inner">
                    
                    <!-- Badge: Product Count -->
                    <div class="category-badge" aria-label="تعداد محصولات">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span><?php echo number_format($cat['product_count']); ?> محصول</span>
                    </div>

                    <!-- Category Image -->
                    <div class="category-image-wrapper">
                        <img 
                            src="<?php echo esc_url(get_stylesheet_directory_uri() . '/assets/images/categories/' . $cat['image']); ?>" 
                            alt="<?php echo esc_attr($cat['title']); ?>"
                            loading="lazy"
                            width="300"
                            height="200"
                            class="category-image"
                        >
                        <div class="category-image-overlay">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm-2 16l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Category Content -->
                    <div class="category-content">
                        <h3 class="category-title">
                            <a 
                                href="<?php echo esc_url($cat['url']); ?>" 
                                title="مشاهده محصولات <?php echo esc_attr($cat['title']); ?>"
                                aria-label="مشاهده <?php echo esc_attr($cat['product_count']); ?> محصول در دسته <?php echo esc_attr($cat['title']); ?>"
                            >
                                <?php echo esc_html($cat['title']); ?>
                            </a>
                        </h3>
                        
                        <p class="category-title-en">
                            <?php echo esc_html($cat['title_en']); ?>
                        </p>
                        
                        <p class="category-description">
                            <?php echo esc_html($cat['description']); ?>
                        </p>

                        <!-- Grades List -->
                        <div class="category-grades">
                            <span class="grades-label">گریدها:</span>
                            <span class="grades-text"><?php echo esc_html($cat['grades']); ?></span>
                        </div>

                        <!-- Applications Badge -->
                        <div class="category-applications" aria-label="کاربردها">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                            </svg>
                            <span><?php echo esc_html($cat['applications']); ?></span>
                        </div>
                    </div>

                    <!-- Card Footer: CTA Button -->
                    <div class="category-footer">
                        <a 
                            href="<?php echo esc_url($cat['url']); ?>" 
                            class="category-cta-btn"
                            aria-label="مشاهده تمام محصولات <?php echo esc_attr($cat['title']); ?>"
                        >
                            <span>مشاهده محصولات</span>
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </a>
                    </div>

                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Section Footer: Additional Information -->
        <footer class="section-footer">
            <div class="footer-info-grid">
                
                <!-- Quality Assurance -->
                <div class="footer-info-item">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <h3>تضمین کیفیت</h3>
                    <p>تمامی محصولات با گواهینامه‌های معتبر بین‌المللی و آزمایش کنترل کیفیت</p>
                </div>

                <!-- Expert Consultation -->
                <div class="footer-info-item">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z"/>
                    </svg>
                    <h3>مشاوره تخصصی</h3>
                    <p>تیم مهندسی ما آماده ارائه مشاوره رایگان در انتخاب بهترین گرید فولاد</p>
                </div>

                <!-- Fast Delivery -->
                <div class="footer-info-item">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <h3>تحویل سریع</h3>
                    <p>ارسال فوری به سراسر ایران با بسته‌بندی استاندارد و ایمن</p>
                </div>

                <!-- Competitive Pricing -->
                <div class="footer-info-item">
                    <svg width="48" height="48" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <h3>قیمت رقابتی</h3>
                    <p>بهترین نرخ بازار با تخفیف ویژه برای خریدهای عمده و پروژه‌ای</p>
                </div>

            </div>

            <!-- Call to Action Section -->
            <div class="final-cta-section">
                <h3 class="final-cta-title">محصول مورد نظر خود را پیدا نکردید؟</h3>
                <p class="final-cta-description">
                    تیم فروش فولادمارکت آماده پاسخگویی به سوالات شما و ارائه بهترین راهکار برای نیازهای فولادی شماست
                </p>
                <div class="final-cta-buttons">
                    <a href="<?php echo esc_url(home_url('/تماس-با-ما/')); ?>" class="cta-btn cta-primary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                        </svg>
                        <span>تماس با ما</span>
                    </a>
                    <a href="<?php echo esc_url(home_url('/products/')); ?>" class="cta-btn cta-secondary">
                        <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                        </svg>
                        <span>مشاهده همه محصولات</span>
                    </a>
                </div>
            </div>
        </footer>

    </div>
</section>
