<!DOCTYPE html>
<html <?php language_attributes(); ?> dir="rtl">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- هدر اصلی -->
<header id="main-header" class="site-header">
    <div class="header-container">
        <!-- لوگو -->
        <div class="header-logo">
            <a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>">
                <img 
                    src="<?php echo get_template_directory_uri(); ?>/assets/images/logo.webp" 
                    alt="<?php bloginfo('name'); ?>" 
                    class="logo-image"
                    width="150"    
                    height="50" 
                >
            </a>
        </div>
        
        <!-- آیکون‌های سریع موبایل -->
        <div class="mobile-quick-actions">
            <a href="tel:+982192003255" class="mobile-icon-btn phone-icon" title="تماس فوری" aria-label="تماس تلفنی">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                    <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                </svg>
            </a>
            
            <a href="https://wa.me/+989122833844" class="mobile-icon-btn whatsapp-icon" target="_blank" rel="noopener" title="واتساپ" aria-label="واتساپ">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                </svg>
            </a>
            
            <a>
                <?php echo do_shortcode('[sina_mini_cart]'); ?>
            </a>
        </div>

        <!-- منوی اصلی دسکتاپ -->
        <nav class="header-nav desktop-nav" role="navigation" aria-label="منوی اصلی">
            <ul class="nav-menu">                
                <!-- آیتم محصولات با مگامنو -->
                <li class="nav-item has-megamenu">
                    <button class="nav-link nav-trigger" aria-expanded="false" aria-haspopup="true">
                        <!-- آیکون محصولات -->
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M19.14 12.94c.04-.3.06-.61.06-.94 0-.32-.02-.64-.07-.94l2.03-1.58a.49.49 0 00.12-.61l-1.92-3.32a.488.488 0 00-.59-.22l-2.39.96c-.5-.38-1.03-.7-1.62-.94l-.36-2.54a.484.484 0 00-.48-.41h-3.84c-.24 0-.43.17-.47.41l-.36 2.54c-.59.24-1.13.57-1.62.94l-2.39-.96c-.22-.08-.47 0-.59.22L2.74 8.87c-.12.21-.08.47.12.61l2.03 1.58c-.05.3-.09.63-.09.94s.02.64.07.94l-2.03 1.58a.49.49 0 00-.12.61l1.92 3.32c.12.22.37.29.59.22l2.39-.96c.5.38 1.03.7 1.62.94l.36 2.54c.05.24.24.41.48.41h3.84c.24 0 .44-.17.47-.41l.36-2.54c.59-.24 1.13-.56 1.62-.94l2.39.96c.22.08.47 0 .59-.22l1.92-3.32c.12-.22.07-.47-.12-.61l-2.01-1.58zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z" fill="currentColor"/>
                        </svg>
                        <span class="nav-text">محصولات</span>
                        <svg class="nav-arrow" width="12" height="12" viewBox="0 0 12 12" fill="none">
                            <path d="M2 4L6 8L10 4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                        </svg>
                    </button>
                    
                    <!-- مگامنوی محصولات -->
                    <div class="megamenu-container">
                        <div class="megamenu-content">
                            <ul class="megamenu-categories">
                                <?php echo do_shortcode('[cmenu_ui]'); ?>
                            </ul>
                        </div>
                    </div>
                </li>

                <li class="nav-item icon-item">
                    <a href="<?php echo home_url('/تماس-با-ما/'); ?>" class="nav-link" title="تماس با ما">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M21 15.46l-5.27-.61-2.52 2.52a15.045 15.045 0 01-6.59-6.59l2.53-2.53L8.54 3H3.03C2.45 13.18 10.82 21.55 21 20.97v-5.51z" fill="currentColor"/>
                        </svg>
                        <span class="nav-text desktop-only">تماس با ما</span>
                    </a>
                </li>

                <li class="nav-item icon-item">
                    <a href="<?php echo home_url('/درباره-ما/'); ?>" class="nav-link" title="درباره ما">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" fill="currentColor"/>
                        </svg>
                        <span class="nav-text desktop-only">درباره ما</span>
                    </a>
                </li>

                <li class="nav-item icon-item">
                    <a href="<?php echo home_url('/articles/blogs/'); ?>" class="nav-link" title="مقالات">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z" fill="currentColor"/>
                        </svg>
                        <span class="nav-text desktop-only">مقالات</span>
                    </a>
                </li>

                <li class="nav-item icon-item">
                    <a href="<?php echo home_url('/articles/steel-suppliers/'); ?>" class="nav-link" title="تامین کنندگان">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M20 6h-2.18c.11-.31.18-.65.18-1a2.996 2.996 0 00-5.5-1.65l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z" fill="currentColor"/>
                        </svg>
                        <span class="nav-text desktop-only">تامین کنندگان</span>
                    </a>
                </li>

                <li class="nav-item highlight-item">
                    <a href="<?php echo home_url('/weight-calculator/'); ?>" class="nav-link nav-highlight">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M20.57 14.86L22 13.43 20.57 12 17 15.57 8.43 7 12 3.43 10.57 2 9.14 3.43 7.71 2 5.57 4.14 4.14 2.71 2.71 4.14l1.43 1.43L2 7.71l1.43 1.43L2 10.57 3.43 12 7 8.43 15.57 17 12 20.57 13.43 22l1.43-1.43L16.29 22l2.14-2.14 1.43 1.43 1.43-1.43-1.43-1.43L22 16.29z" fill="currentColor"/>
                        </svg>
                        <span class="nav-text">محاسبه وزن</span>
                    </a>
                </li>

                <li class="nav-item highlight-item">
                    <a href="<?php echo home_url('/steels-comparison/'); ?>" class="nav-link nav-highlight">
                        <svg class="nav-icon" width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path d="M9.01 14H2v2h7.01v3L13 15l-3.99-4v3zm5.98-1v-3H22V8h-7.01V5L11 9l3.99 4z" fill="currentColor"/>
                        </svg>
                        <span class="nav-text">مقایسه فولادها</span>
                    </a>
                </li>

                <li class="nav-item icon-item ">
                    <?php echo do_shortcode('[sina_mini_cart]'); ?>
                </li>
            </ul>
        </nav>

        <!-- دکمه‌های تماس - دسکتاپ -->
        <div class="header-contact-buttons">
            <!-- دکمه واتساپ -->
            <a href="https://wa.me/+989122833844" class="header-contact-btn whatsapp-btn" target="_blank" rel="noopener" title="واتساپ مستقیم">
                <span class="contact-btn-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/>
                    </svg>
                </span>
            </a>

            <!-- دکمه تلفن -->
            <a href="tel:+982192003255" class="header-contact-btn phone-btn" title="تماس فوری">
                <span class="contact-btn-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
                    </svg>
                </span>
                <span class="contact-btn-text"> ۰۲۱-۹۲۰۰۳۲۵۵</span>
            </a>
        </div>

        <!-- دکمه منوی موبایل -->
        <button class="mobile-menu-toggle" aria-label="منوی موبایل" aria-expanded="false">
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
            <span class="hamburger-line"></span>
        </button>
    </div>
    
    <!-- منوی موبایل -->
    <div class="mobile-menu" aria-hidden="true">
        <div class="mobile-menu-content">
            <ul class="mobile-nav-menu">     
                <!-- محصولات با زیرمنو -->
                <li class="has-submenu">
                    <!-- اضافه کردن تریگر برای باز کردن منو -->
                    <span class="submenu-toggle">
                        <strong>محصولات</strong>
                        <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="arrow-icon">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </span>
                    
                    <!-- لیست دسته‌ها -->
                    <ul class="mobile-submenu mobile-products-list">
                        <?php echo do_shortcode('[cmenu_ui]'); ?>
                    </ul>
                </li>

                <li><a href="<?php echo home_url('/weight-calculator/'); ?>"><strong>محاسبه وزن</strong></a></li>
                <li><a href="<?php echo home_url('/steels-comparison/'); ?>"><strong>مقایسه فولادها</strong></a></li>       
                <li><a href="<?php echo home_url('/تماس-با-ما/'); ?>"> <strong>تماس با ما</strong></a></li>
                <li><a href="<?php echo home_url('/درباره-ما/'); ?>"><strong>درباره ما</strong></a></li>
                <li><a href="<?php echo home_url('/articles/blogs/'); ?>"><strong>مقالات</strong></a></li>
                <li><a href="<?php echo home_url('/articles/steel-suppliers/'); ?>"><strong>تامین کنندگان</strong></a></li>
            </ul>
        </div>
    </div>
</header>

<?php wp_body_open(); ?>
