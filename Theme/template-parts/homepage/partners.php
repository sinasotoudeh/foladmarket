<?php
/**
 * Template Part: Suppliers and Partners Section
 * 
 * @package Astra Child
 * @since 1.0.0
 * @author Sina Sotoudeh
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Suppliers data array
$suppliers = [
    [
        'name' => 'ذوب آهن اصفهان',
        'slug' => 'zobahan-esfahan',
        'url' => '/articles/steel-suppliers/%da%a9%d8%a7%d8%b1%d8%ae%d8%a7%d9%86%d9%87-%d8%b0%d9%88%d8%a8-%d8%a2%d9%87%d9%86-%d8%a7%d8%b5%d9%81%d9%87%d8%a7%d9%86/',
        'image' => '/wp-content/uploads/2025/12/1_zobahan.webp',
        'alt' => 'لوگو کارخانه ذوب آهن اصفهان - تولیدکننده فولاد',
    ],
    [
        'name' => 'پرشین فولاد',
        'slug' => 'persian-folad',
        'url' => '/articles/steel-suppliers/%da%af%d8%b1%d9%88%d9%87-%d8%b5%d9%86%d8%b9%d8%aa%db%8c-%d9%be%d8%b1%d8%b4%db%8c%d9%86-%d9%81%d9%88%d9%84%d8%a7%d8%af/',
        'image' => '/wp-content/uploads/2025/12/2_persian.webp',
        'alt' => 'لوگو گروه صنعتی پرشین فولاد - تولید فولاد آلیاژی',
    ],
    [
        'name' => 'فولاد آلیاژی یزد',
        'slug' => 'folad-aliazhi-yazd',
        'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%a2%d9%84%db%8c%d8%a7%da%98%db%8c-%db%8c%d8%b2%d8%af/',
        'image' => '/wp-content/uploads/2025/12/3_yazd.webp',
        'alt' => 'لوگو شرکت فولاد آلیاژی یزد - فولاد ابزار',
    ],
    [
        'name' => 'جهان فولاد سیرجان',
        'slug' => 'jahan-folad-sirjan',
        'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%b3%db%8c%d8%b1%d8%ac%d8%a7%d9%86/',
        'image' => '/wp-content/uploads/2025/12/4_sirjan.webp',
        'alt' => 'لوگو مجتمع جهان فولاد سیرجان - تولید میلگرد',
    ],
    [
        'name' => 'فولاد ماهان سپاهان',
        'slug' => 'folad-mahan-sepahan',
        'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d9%85%d8%a7%d9%87%d8%a7%d9%86-%d8%b3%d9%be%d8%a7%d9%87%d8%a7%d9%86/',
        'image' => '/wp-content/uploads/2025/12/5_sepahan.webp',
        'alt' => 'لوگو فولاد ماهان سپاهان - نورد فولاد',
    ],
    [
        'name' => 'فولاد البرز ایرانیان فایکو',
        'slug' => 'faico',
        'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%a7%d9%84%d8%a8%d8%b1%d8%b2-%d8%a7%db%8c%d8%b1%d8%a7%d9%86%db%8c%d8%a7%d9%86-%d9%81%d8%a7%db%8c%da%a9%d9%88/',
        'image' => '/wp-content/uploads/2025/12/6_FAICO.webp',
        'alt' => 'لوگو فولاد البرز ایرانیان فایکو - FAICO',
    ],
    [
        'name' => 'فولاد ناب تبریز',
        'slug' => 'folad-nab-tabriz',
        'url' => '/articles/steel-suppliers/%d9%81%d9%88%d9%84%d8%a7%d8%af-%d9%86%d8%a7%d8%a8-%d8%aa%d8%a8%d8%b1%db%8c%d8%b2/',
        'image' => '/wp-content/uploads/2025/12/7_tabriz.webp',
        'alt' => 'لوگو فولاد ناب تبریز - فولاد ضد زنگ',
    ],
    [
        'name' => 'فولاد کویر کاشان',
        'slug' => 'folad-kavir-kashan',
        'url' => '/articles/steel-suppliers/%d9%81%d9%88%d9%84%d8%a7%d8%af-%da%a9%d9%88%db%8c%d8%b1-%da%a9%d8%a7%d8%b4%d8%a7%d9%86/',
        'image' => '/wp-content/uploads/2025/12/8_kavir.webp',
        'alt' => 'لوگو فولاد کویر کاشان - تیرآهن و ناودانی',
    ],
    [
        'name' => 'فولاد کاویان',
        'slug' => 'folad-kavian',
        'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%da%a9%d8%a7%d9%88%db%8c%d8%a7%d9%86/',
        'image' => '/wp-content/uploads/2025/12/9_kavian.webp',
        'alt' => 'لوگو فولاد کاویان - مقاطع فولادی',
    ],
    [
        'name' => 'صنایع هفت الماس',
        'slug' => 'haft-almas',
        'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d8%b5%d9%86%d8%a7%db%8c%d8%b9-%d9%87%d9%81%d8%aa-%d8%a7%d9%84%d9%85%d8%a7%d8%b3/',
        'image' => '/wp-content/uploads/2025/12/10_haftalmas.webp',
        'alt' => 'لوگو صنایع هفت الماس - ورق فولادی',
    ],
    [
        'name' => 'فولاد اکسین خوزستان',
        'slug' => 'folad-oxin',
        'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%a7%da%a9%d8%b3%db%8c%d9%86-%d8%ae%d9%88%d8%b2%d8%b3%d8%aa%d8%a7%d9%86/',
        'image' => '/wp-content/uploads/2025/12/11_oxin.webp',
        'alt' => 'لوگو فولاد اکسین خوزستان - فولاد صنعتی',
    ],
    [
        'name' => 'فولاد مبارکه اصفهان',
        'slug' => 'folad-mobarakeh',
        'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d9%85%d8%a8%d8%a7%d8%b1%da%a9%d9%87-%d8%a7%d8%b5%d9%81%d9%87%d8%a7%d9%86/',
        'image' => '/wp-content/uploads/2025/12/12_mobarake.webp',
        'alt' => 'لوگو فولاد مبارکه اصفهان - بزرگترین تولیدکننده',
    ],
    [
        'name' => 'فولاد آساب',
        'slug' => 'assab',
        'url' => '/articles/steel-suppliers/%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%a2%d8%b3%d8%a7%d8%a8/',
        'image' => '/wp-content/uploads/2025/12/13_ASSAB.webp',
        'alt' => 'لوگو فولاد آساب - ASSAB سوئد',
    ],
    [
        'name' => 'فولاد بوهلر',
        'slug' => 'bohler',
        'url' => '/articles/steel-suppliers/%da%a9%d8%a7%d8%b1%d8%ae%d8%a7%d9%86%d9%87-%d8%aa%d9%88%d9%84%db%8c%d8%af-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%a8%d9%88%d9%87%d9%84%d8%b1bohler/',
        'image' => '/wp-content/uploads/2025/12/14_boehler.webp',
        'alt' => 'لوگو فولاد بوهلر - Bohler اتریش',
    ],
];
?>

<!-- Partners Section -->
<section class="partners-section" id="suppliers" aria-labelledby="suppliers-heading">
    <div class="container">
        
        <!-- Trust Badge -->
        <div class="partners-trust-badge">
            <svg class="trust-icon" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <path d="M12 2L3 7V12C3 16.97 6.84 21.62 12 23C17.16 21.62 21 16.97 21 12V7L12 2Z" 
                      stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M9 12L11 14L15 10" stroke="currentColor" stroke-width="2" 
                      stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <p class="trust-text">
                عاملیت فروش و همکاری مستقیم با <span style="font-weight: 700;">+14 تولیدکننده</span> معتبر داخلی و بین‌المللی
            </p>
        </div>
        <!-- Suppliers Grid -->
        <div class="suppliers-grid" role="list">
            <?php foreach ($suppliers as $index => $supplier) : ?>
                <article 
                    class="supplier-card" 
                    data-supplier="<?php echo esc_attr($supplier['slug']); ?>"
                    role="listitem"
                >
                    <a 
                        href="<?php echo esc_url($supplier['url']); ?>" 
                        class="supplier-link"
                        aria-label="مشاهده اطلاعات <?php echo esc_attr($supplier['name']); ?>"
                    >
                        <!-- Supplier Logo -->
                        <div class="supplier-logo-wrapper">
                            <img 
                                src="<?php echo esc_url($supplier['image']); ?>" 
                                alt="<?php echo esc_attr($supplier['alt']); ?>"
                                class="supplier-logo"
                                loading="lazy"
                                width="200"
                                height="100"
                                decoding="async"
                            />
                        </div>

                        <!-- Hover Overlay -->
                        <div class="supplier-overlay" aria-hidden="true">
                            <h3 class="supplier-name"><?php echo esc_html($supplier['name']); ?></h3>
                            <span class="supplier-cta">
                                <span>مشاهده اطلاعات</span>
                                <svg 
                                    class="cta-icon" 
                                    width="20" 
                                    height="20" 
                                    viewBox="0 0 20 20" 
                                    fill="none"
                                    aria-hidden="true"
                                >
                                    <path 
                                        d="M7.5 15L12.5 10L7.5 5" 
                                        stroke="currentColor" 
                                        stroke-width="2" 
                                        stroke-linecap="round" 
                                        stroke-linejoin="round"
                                    />
                                </svg>
                            </span>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

    </div>
</section>
