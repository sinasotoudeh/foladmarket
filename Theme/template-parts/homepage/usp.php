<?php
/**
 * Template Part: USP Section (Why FoladMarket)
 * Optimized for Performance & E-E-A-T Signals
 * 
 * @package Astra Child
 * @since 1.0.0
 * @author Sina Sotoudeh

 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// USP Data Structure
$usp_items = array(
    array(
        'id' => 'quality-assurance',
        'title' => 'شفافیت و تضمین کیفیت صنعتی با مدارک معتبر',
        'subtitle' => 'EN 10204 – 3.1 و ردیابی',
        'icon' => 'certificate',
        'content' => array(
            'ارائه گواهینامه EN 10204 – 3.1 برای فولادهای آلیاژی و ابزار (CK45، 42CrMo4، MO40، 1.2312، 1.2344، D2)',
            'درج Heat Number و Cast Number روی کالا جهت ردیابی کامل تا منبع ذوب',
            'به‌روزرسانی مستمر اسناد کیفی براساس الزامات پروژه‌محور'
        ),
        'image' => 'quality-certificate.webp'
    ),
    array(
        'id' => 'supply-guarantee',
        'title' => 'تضمین تامین قابل اتکا در تمامی ظرفیت‌های تناژی و پروژه‌ای',
        'subtitle' => 'توانایی تامین خرد تا پروژه‌ای',
        'icon' => 'supply-chain',
        'content' => array(
            'توانایی تامین فولادهای آلیاژی و ابزار در احجام خرد، تناژی و پروژه‌ای با تعهد به زمانبندی دقیق',
            'زیرساخت لجستیکی جهت ارسال مطمئن و پیگیری شفاف سفارش (ثبت/رهگیری دیجیتال)'
        ),
        'image' => 'supply-chain.webp'
    ),
    array(
        'id' => 'dimensional-accuracy',
        'title' => 'حفظ تلرانس‌های ابعادی و کنترل ابعاد مطابق الزامات ماشین‌کاری',
        'subtitle' => 'دقت در ابعاد سفارشی',
        'icon' => 'precision',
        'content' => array(
            'ارائه فولاد با ابعاد سفارشی و تلرانس صنعتی مطابق نقشه',
            'قابلیت برش دقیق براساس سفارش مشتری با ارائه گواهی تایید ابعاد'
        ),
        'image' => 'precision-cutting.webp'
    ),
    array(
        'id' => 'technical-consulting',
        'title' => 'خدمات عملیات حرارتی و مشاوره فنی تخصصی متناسب با کاربری',
        'subtitle' => 'پشتیبانی تخصصی در کاربردهای صنعتی',
        'icon' => 'consulting',
        'content' => array(
            'ارائه خدمات مشاوره مواد و عملیات حرارتی متناسب با کاربرد خاص (قالب‌سازی، قطعه‌سازی، ماشین‌کاری سنگین، نفت و گاز)',
            'همکاری با آزمایشگاه‌های معتبر جهت تهیه مدارک تست مکانیکی و حرارتی'
        ),
        'image' => 'heat-treatment.webp'
    ),
    array(
        'id' => 'transparent-pricing',
        'title' => 'قیمت‌گذاری شفاف و راهکارهای مالی انعطاف‌پذیر',
        'subtitle' => 'مدیریت ریسک ارزی',
        'icon' => 'pricing',
        'content' => array(
            'اعلام قیمت شفاف و کنترل‌شده مبتنی بر شرایط بازار و نرخ حواله/ارز لحظه‌ای',
            'ارائه مشاوره ریسک مالی خرید و تأمین'
        ),
        'image' => 'transparent-pricing.webp'
    ),
    array(
        'id' => 'digital-tracking',
        'title' => 'امکان رهگیری دیجیتال سفارشات و تعهد به ارتباطات صنعتی شفاف',
        'subtitle' => 'پیگیری آنلاین و پشتیبانی 360 درجه',
        'icon' => 'tracking',
        'content' => array(
            'ثبت سفارش، رهگیری وضعیت و دریافت مستندات کالای خریداری‌شده به‌صورت کاملاً دیجیتال',
            'امکان ارتباط مستقیم با کارشناسان فنی، فروش و پیگیری لجستیکی از طریق سامانه آنلاین و تلفن‌های ثابت/همراه تعریف‌شده',
            'پشتیبانی پس از خرید و بازخوردگیری منظم جهت اصلاح فرآیندها'
        ),
        'image' => 'digital-tracking.webp'
    )
);

// SVG Icons (Inline for performance)
$icons = array(
    'certificate' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/><path d="M9 21v-2a4 4 0 014-4h0a4 4 0 014 4v2"/></svg>',
    'supply-chain' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 2v6m6-6v6M9 18v4m6-4v4M5 8h14M3 12h2m14 0h2M5 16h14M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
    'precision' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M2 12h20M6 6l12 12M6 18L18 6"/><circle cx="12" cy="12" r="3"/></svg>',
    'consulting' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 8h2a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V10a2 2 0 012-2h2M7 8V6a5 5 0 0110 0v2m-5 5v4m0 0l-2-2m2 2l2-2"/></svg>',
    'pricing' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
    'tracking' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>'
);
?>

<section class="foladmarket-usp" id="why-foladmarket">
    <div class="container">
        
        <!-- Section Header -->
        <header class="usp-header">
            <h2 class="usp-main-title">
                چرا خریداران صنعتی به فولادمارکت اعتماد می‌کنند؟
            </h2>
            <p class="usp-subtitle">
                شش مزیت رقابتی مستند و قابل‌اعتماد که فولادمارکت را به انتخاب اول تامین‌کنندگان صنعتی تبدیل کرده است
            </p>
        </header>

        <!-- USP Interactive Panel -->
        <div class="usp-panel">
            
            <!-- Left: Features List -->
            <nav class="usp-features-list" role="navigation" aria-label="مزایای فولادمارکت">
                <?php foreach ($usp_items as $index => $item): ?>
                <button 
                    class="usp-feature-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                    data-usp-target="<?php echo esc_attr($item['id']); ?>"
                    role="tab"
                    aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                    aria-controls="usp-content-<?php echo esc_attr($item['id']); ?>"
                >
                    <span class="feature-icon" aria-hidden="true">
                        <?php echo $icons[$item['icon']]; ?>
                    </span>
                    <span class="feature-text">
                        <strong class="feature-title"><?php echo esc_html($item['title']); ?></strong>
                        <small class="feature-subtitle"><?php echo esc_html($item['subtitle']); ?></small>
                    </span>
                    <span class="feature-arrow" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                </button>
                <?php endforeach; ?>
            </nav>

            <!-- Right: Content Display -->
            <div class="usp-content-display" role="tabpanel">
                <?php foreach ($usp_items as $index => $item): ?>
                <article 
                    class="usp-content-item <?php echo $index === 0 ? 'active' : ''; ?>" 
                    id="usp-content-<?php echo esc_attr($item['id']); ?>"
                    data-usp-content="<?php echo esc_attr($item['id']); ?>"
                >
                    <div class="content-wrapper">
                        <div class="content-text">
                            <h3 class="content-title">
                                <?php echo esc_html($item['title']); ?>
                            </h3>
                            <ul class="content-list">
                                <?php foreach ($item['content'] as $point): ?>
                                <li><?php echo esc_html($point); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <div class="content-visual">
                            <div class="visual-placeholder" data-image="<?php echo esc_attr($item['image']); ?>">
                                <span class="visual-icon" aria-hidden="true">
                                    <?php echo $icons[$item['icon']]; ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </article>
                <?php endforeach; ?>
            </div>

        </div>

    </div>
</section>

<div class="contentstyletype5">
    <div class="content-title">
        <h3>خدمات ویژه برای مشتریان B2B</h3>
    </div>
    <div class="content-text">
        <p>
            فولادمارکت علاوه بر فروش محصول، خدمات ارزش‌افزوده متنوعی نیز ارائه می‌دهد. <strong>مشاوره فنی رایگان</strong> توسط تیم مهندسی مجرب، <strong>برش و فرآوری</strong> مطابق نقشه، <strong>عملیات حرارتی</strong> شامل کوئنچ، تمپر و نرماله‌سازی، و <strong>ارسال سریع به سراسر کشور</strong> از جمله این خدمات است. این رویکرد جامع باعث شده که کارخانجات بزرگ خودروسازی، قطعه‌سازی، قالب‌سازی و صنایع نفت و گاز به فولادمارکت به‌عنوان تامین‌کننده اصلی خود اعتماد کنند.
        </p>
    </div>    
</div>
