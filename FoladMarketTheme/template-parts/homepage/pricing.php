<?php
/**
 * Daily Pricing Premium Section
 * 
 * @package Astra Child
 * @version 2.0
 * @author Sina Sotoudeh
 */

if (!defined('ABSPATH')) exit;

/**
 * دریافت اولین ردیف از جدول قیمت یک محصول
 */
function get_product_first_row_data($product_id) {
    $rows = get_field('product_rows', $product_id);
    
    if (empty($rows) || !isset($rows[0])) {
        return null;
    }
    
    $row = $rows[0];
    $raw_price = $row['product_price'] ?? '';
    
    // محاسبه ترند قیمت
    $trend = 'neutral';
    $change_percent = '0%';
    
    if (is_numeric($raw_price) && floatval($raw_price) > 0) {
        $price = floatval($raw_price);
        $today = floatval(get_option('daily_dollar_today_rate', 0));
        $yesterday = floatval(get_option('daily_dollar_yesterday_rate', 0));
        
        if ($today > $yesterday) {
            $trend = 'up';
            $change_percent = '+2.5%'; 
        } elseif ($today < $yesterday) {
            $trend = 'down';
            $change_percent = '-1.2%';
        }
    }
    
    return [
        'product_id' => $product_id,
        'name' => $row['product_name'] ?? get_the_title($product_id),
        'code' => $row['product_code'] ?? '',
        'grade' => $row['product_grade'] ?? '',
        'size' => $row['product_size'] ?? '',
        'thickness' => $row['product_thickness'] ?? '',
        'weight' => $row['product_weight'] ?? '',
        'manufacturer' => $row['product_manufacturer'] ?? '',
        'unit' => $row['measurement_unit'] ?? 'کیلوگرم',
        'price' => $raw_price,
        'trend' => $trend,
        'change' => $change_percent,
        'row_index' => 0,
        'thumbnail' => get_the_post_thumbnail_url($product_id, 'thumbnail')
    ];
}

// دریافت لیست محصولات
$product_ids = [];

// روش 1: از query_var
$pricing_ids_string = get_query_var('pricing_product_ids', '');

// روش 2: از $args
if (empty($pricing_ids_string) && !empty($args['product_ids'])) {
    $pricing_ids_string = $args['product_ids'];
}

// تبدیل رشته به آرایه
if (!empty($pricing_ids_string)) {
    if (is_array($pricing_ids_string)) {
        $product_ids = array_map('intval', $pricing_ids_string);
    } elseif (is_string($pricing_ids_string)) {
        $product_ids = array_map('intval', array_filter(explode(',', $pricing_ids_string)));
    }
}

// اگر هیچ محصولی مشخص نشده، 8 محصول اخیر
if (empty($product_ids)) {
    $recent_products = get_posts([
        'post_type' => 'product',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
        'fields' => 'ids'
    ]);
    $product_ids = $recent_products;
}

$pricing_items = [];
foreach ($product_ids as $pid) {
    $data = get_product_first_row_data($pid);
    if ($data && is_numeric($data['price']) && floatval($data['price']) > 0) {
        $pricing_items[] = $data;
    }
}

if (empty($pricing_items)) {
    return;
}

// تاریخ آخرین به‌روزرسانی
$last_update = current_time('Y-m-d\TH:i:s');
$last_update_display = date_i18n('d F Y - H:i', current_time('timestamp'));
?>

<div class="contentstyletype5">
    <div class="content-title">
        <h3>قیمت روز فولاد و استعلام آنلاین</h3>
    </div>
    <div class="content-text">
        <p>
            با توجه به نوسانات بازار آهن و فولاد، فولادمارکت سیستم استعلام قیمت آنلاین و ارائه قیمت روز را برای مشتریان فراهم کرده است. خریداران می‌توانند با مراجعه به وبسایت یا تماس تلفنی با شماره ۰۲۱-۹۲۰۰۳۲۵۵، آخرین قیمت‌ها را دریافت و سفارش خود را ثبت کنند. این شفافیت در قیمت‌گذاری یکی از عواملی است که اعتماد مشتریان را جلب کرده است.
        </p>
    </div>
</div>

<section class="pricing-premium-section" id="pricing">
    
    <div class="container">
        
        <!-- Section Header -->
        <div class="pricing-header-wrapper">
            <div class="pricing-header-content">
                <h2 class="pricing-main-title">
                    قیمت <span class="text-gradient-gold">روز فولادها</span>
                </h2>
                <p class="pricing-subtitle">
                    آخرین قیمت‌های بازار با امکان استعلام آنی
                </p>
            </div>
            
            <div class="pricing-meta">
                <div class="update-badge">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                        <path d="M8 3a5 5 0 104.546 2.914.5.5 0 00-.908-.417A4 4 0 118 4a.5.5 0 000-1z"/>
                        <path d="M8 0a.5.5 0 01.5.5v2a.5.5 0 01-1 0v-2A.5.5 0 018 0z"/>
                    </svg>
                    <time datetime="<?php echo esc_attr($last_update); ?>">
                        <?php echo esc_html($last_update_display); ?>
                    </time>
                </div>
            </div>
        </div>
        
        <!-- Stacked Cards Carousel -->
        <div class="pricing-carousel-wrapper">
            
            <!-- Navigation Arrows -->
            <button class="pricing-nav pricing-nav-prev" type="button" aria-label="محصول قبلی">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
            </button>
            
            <button class="pricing-nav pricing-nav-next" type="button" aria-label="محصول بعدی">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
            </button>
            
            <!-- Cards Container -->
            <div class="pricing-cards-stack" data-total="<?php echo count($pricing_items); ?>">
                
                <?php foreach ($pricing_items as $index => $item): 
                    $product_title = get_the_title($item['product_id']);
                    $is_active = $index === 0 ? ' active' : '';
                ?>
                
                <article 
                    class="pricing-card-premium<?php echo $is_active; ?>"
                    data-index="<?php echo $index; ?>"
                    data-post-id="<?php echo esc_attr($item['product_id']); ?>"
                    data-row-index="<?php echo esc_attr($item['row_index']); ?>"
                >
                    
                    <div class="pricing-card-inner">
                        
                        <!-- Card Header -->
                        <div class="pricing-card-header">
                            <div class="pricing-title-group">
                                <h3 class="pricing-product-name">
                                    <?php echo esc_html($item['name']); ?>
                                </h3>
                                <?php if (!empty($item['code'])): ?>
                                    <span class="pricing-product-code">
                                        <?php echo esc_html($item['code']); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="pricing-trend-badge trend-<?php echo esc_attr($item['trend']); ?>">
                                <?php if ($item['trend'] === 'up'): ?>
                                    <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                        <path d="M8 3l6 6H2z"/>
                                    </svg>
                                <?php elseif ($item['trend'] === 'down'): ?>
                                    <svg width="14" height="14" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true">
                                        <path d="M8 13l6-6H2z"/>
                                    </svg>
                                <?php endif; ?>
                                <span><?php echo esc_html($item['change']); ?></span>
                            </div>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="pricing-card-body">
                            
                            <!-- Specifications Grid -->
                            <dl class="pricing-specs">
                                <?php if (!empty($item['grade'])): ?>
                                    <div class="spec-item">
                                        <dt>آلیاژ:</dt>
                                        <dd><?php echo esc_html($item['grade']); ?></dd>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['size'])): ?>
                                    <div class="spec-item">
                                        <dt>سایز:</dt>
                                        <dd><?php echo esc_html($item['size']); ?></dd>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['thickness'])): ?>
                                    <div class="spec-item">
                                        <dt>ضخامت:</dt>
                                        <dd><?php echo esc_html($item['thickness']); ?></dd>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($item['manufacturer'])): ?>
                                    <div class="spec-item spec-manufacturer">
                                        <dt>کارخانه:</dt>
                                        <dd><?php echo esc_html($item['manufacturer']); ?></dd>
                                    </div>
                                <?php endif; ?>
                            </dl>
                            
                            <!-- Price Display -->
                            <div class="pricing-amount-wrapper">
                                <div class="pricing-amount">
                                    <span class="price-value">
                                        <?php echo number_format(floatval($item['price']), 0, ',', ','); ?>
                                    </span>
                                    <span class="price-currency">تومان</span>
                                </div>
                                <div class="pricing-unit">
                                    به ازای هر <?php echo esc_html($item['unit']); ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Card Footer -->
                        <div class="pricing-card-footer">
                            <button 
                                class="btn-pricing-inquiry sina-add-to-cart-btn"
                                type="button"
                                data-post-id="<?php echo esc_attr($item['product_id']); ?>"
                                data-row-index="<?php echo esc_attr($item['row_index']); ?>"
                                data-product-title="<?php echo esc_attr($product_title); ?>"
                                data-product-thumbnail="<?php echo esc_attr($item['thumbnail'] ?: get_template_directory_uri() . '/assets/images/steel-op.webp'); ?>"
                                data-product-code="<?php echo esc_attr($item['code']); ?>"
                                data-product-name="<?php echo esc_attr($item['name']); ?>"
                                data-product-size="<?php echo esc_attr($item['size']); ?>"
                                data-product-thickness="<?php echo esc_attr($item['thickness']); ?>"
                                data-product-grade="<?php echo esc_attr($item['grade']); ?>"
                                data-product-trim="<?php echo esc_attr($item['trim'] ?? ''); ?>"
                                data-product-weight="<?php echo esc_attr($item['weight']); ?>"
                                data-product-manufacturer="<?php echo esc_attr($item['manufacturer']); ?>"
                                data-measurement-unit="<?php echo esc_attr($item['unit']); ?>"
                                data-product-price="<?php echo esc_attr($item['price']); ?>"
                                aria-label="استعلام قیمت <?php echo esc_attr($item['name']); ?>"
                            >
                                <span class="btn-icon">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 11l3 3L22 4"></path>
                                        <path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"></path>
                                    </svg>
                                </span>
                                <span class="btn-text">استعلام قیمت</span>
                                <span class="btn-loading" style="display:none;">✅</span>
                            </button>
                            
                            <a 
                                href="<?php echo esc_url(get_permalink($item['product_id'])); ?>" 
                                class="btn-pricing-details"
                                aria-label="مشاهده جزئیات <?php echo esc_attr($item['name']); ?>"
                            >
                                جزئیات محصول
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </a>
                        </div>
                        
                    </div>
                </article>
                
                <?php endforeach; ?>
                
            </div>
            
            <!-- Progress Indicators -->
            <div class="pricing-progress" role="tablist" aria-label="انتخاب محصول">
                <?php foreach ($pricing_items as $index => $item): ?>
                    <button 
                        class="progress-dot<?php echo $index === 0 ? ' active' : ''; ?>"
                        type="button"
                        role="tab"
                        data-index="<?php echo $index; ?>"
                        aria-label="محصول <?php echo $index + 1; ?>"
                        aria-selected="<?php echo $index === 0 ? 'true' : 'false'; ?>"
                    ></button>
                <?php endforeach; ?>
            </div>
            
        </div>
        
        <!-- Notice Box -->
        <div class="pricing-notice-premium">
            <div class="notice-icon">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
            </div>
            <div class="notice-content">
                <p class="notice-text">
                    قیمت‌های نمایش داده شده بر اساس آخرین نرخ بازار بوده و ممکن است بسته به حجم و شرایط سفارش متغیر باشد. 
                    برای دریافت قیمت نهایی و شرایط ویژه، لطفاً با ما تماس بگیرید.
                </p>
            </div>
        </div>
        
    </div>
</section>
