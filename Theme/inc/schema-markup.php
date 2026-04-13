<?php
/**
 * Schema.org Structured Data Markup Manager
 * 
 * @package FoladMarket
 * @version 4.2.1 (Moved to Head)
 * @description Solves "Multiple ListItem" error using @graph AND outputs in <head>
 */

if (!defined('ABSPATH')) {
    exit;
}

// Global variable to hold all schema parts
global $foladmarket_schema_graph;
$foladmarket_schema_graph = array();

/**
 * 0. CRITICAL FIX: Disable Yoast Schema ONLY on Homepage
 */
function foladmarket_disable_yoast_on_home( $data ) {
    if ( is_front_page() ) {
        return false; 
    }
    return $data;
}
add_filter( 'wpseo_json_ld_output', 'foladmarket_disable_yoast_on_home' );

function foladmarket_disable_astra_schema() {
    if ( is_front_page() ) {
        add_filter( 'astra_schema_enabled', '__return_false' );
    }
}
add_action( 'wp', 'foladmarket_disable_astra_schema' );

/**
 * 1. CATEGORIES SCHEMA
 */
function foladmarket_homepage_category_schema() {
    if (!is_front_page()) return;

    global $foladmarket_schema_graph;
    $cache_key = 'foladmarket_category_schema_v4_2'; 
    $cached = get_transient($cache_key);

    if ($cached !== false) {
        $foladmarket_schema_graph[] = $cached;
        return;
    }

    $categories = array(
        array(
            'title' => 'استنلس استیل',
            'title_en' => 'Stainless Steel',
            'url' => home_url('/stainless-steel/'),
            'image' => 'stainless-steel.webp',
            'description' => 'فولادهای ضد زنگ با مقاومت استثنایی در برابر خوردگی و استحکام بالا. مناسب صنایع غذایی، پزشکی، شیمیایی و سازه‌های ساحلی با قیمت رقابتی و گواهی اصالت.',
            'grades' => '1.4301 (304), 1.4401 (316), 1.4404 (316L), 1.4016 (430), 1.4021 (420), 1.4057 (431), 1.4541 (321), 1.4571 (316Ti)',
            'product_count' => 156,
            'popularity' => 95
        ),
        array(
            'title' => 'فولاد ابزار سردکار',
            'title_en' => 'Cold Work Tool Steel',
            'url' => home_url('/tool-steel/coldwork/'),
            'image' => 'cold-work-steel.webp',
            'description' => 'فولادهای ابزار با سختی فوق‌العاده برای قالب‌سازی دقیق و ابزارهای برش فلزات. مقاومت سایشی برتر با واردات مستقیم و تضمین استاندارد DIN.',
            'grades' => '1.2379 (D2), 1.2080 (D3), 1.2510 (O1), 1.2842 (O2), 1.2363 (A2), 1.2767 (Caldie), 1.2601 (Rigor), 1.2436 (Vanadis 4)',
            'product_count' => 89,
            'popularity' => 88
        ),
        array(
            'title' => 'فولاد ابزار گرمکار',
            'title_en' => 'Hot Work Tool Steel',
            'url' => home_url('/tool-steel/hotwork/'),
            'image' => 'hot-work-steel.webp',
            'description' => 'فولادهای مخصوص کار در دماهای بالا با مقاومت حرارتی و چقرمگی استثنایی. ایده‌آل برای قالب‌های دایکاست و فورج با استانداردهای بین‌المللی.',
            'grades' => '1.2344 (H13), 1.2343 (H11), 1.2365 (H10), 1.2367 (H21), 1.2681 (SKD61), 1.2714 (L6), 1.2842 (H19), 1.2606 (W302)',
            'product_count' => 72,
            'popularity' => 92
        ),
        array(
            'title' => 'فولاد ابزار تندبر',
            'title_en' => 'High Speed Steel (HSS)',
            'url' => home_url('/tool-steel/hs/'),
            'image' => 'high-speed-steel.webp',
            'description' => 'فولادهای تندبر با حفظ سختی در دماهای بالا برای ابزارهای برشی CNC و صنعتی. کیفیت اروپایی با قیمت مناسب و تحویل سریع.',
            'grades' => '1.3343 (M2), 1.3243 (M35), 1.3247 (M42), 1.3355 (T1), 1.3202 (T15), 1.3207 (SKH51), 1.3505 (SKH9), 1.3257 (PM-M4)',
            'product_count' => 64,
            'popularity' => 85
        ),
        array(
            'title' => 'فولاد سمانتاسیون',
            'title_en' => 'Case Hardening Steel',
            'url' => home_url('/cementation/'),
            'image' => 'cementation-steel.webp',
            'description' => 'فولادهای قابل سخت‌کاری سطحی برای قطعات با هسته چقرمه و سطح سخت. ویژه ساخت چرخ‌دنده و محورهای صنعتی با مشاوره رایگان عملیات حرارتی.',
            'grades' => '1.7131 (16MnCr5), 1.7147 (20MnCr5), 1.6587 (18CrNiMo7-6), 1.5920 (20CrMo5), 1.7225 (42CrMo4), 1.7321 (25CrMo4), 1.6523 (20MoCr4), 1.5752 (17CrNi6-6)',
            'product_count' => 45,
            'popularity' => 78
        ),
        array(
            'title' => 'فولاد قالب پلاستیک',
            'title_en' => 'Plastic Mold Steel',
            'url' => home_url('/plastic-mold-steel/'),
            'image' => 'plastic-mold-steel.webp',
            'description' => 'فولادهای ویژه قالب‌سازی با قابلیت پولیش آینه‌ای و یکنواختی ابعادی عالی. تامین P20، NAK80 و 718 با کیفیت تضمینی و خدمات برش.',
            'grades' => '1.2311 (P20), 1.2312 (P20+S), 1.2738 (718), 1.2083 (420), 1.2316 (S136), 1.2085 (NAK80), 1.2767 (Stavax), 1.2842 (Corrax)',
            'product_count' => 58,
            'popularity' => 82
        ),
        array(
            'title' => 'فولاد عملیات حرارتی',
            'title_en' => 'Heat Treatable Steel',
            'url' => home_url('/heat-treatable/'),
            'image' => 'heat-treatable-steel.webp',
            'description' => 'فولادهای آلیاژی کوئنچ و تمپر برای دستیابی به استحکام و سختی بهینه. مناسب محورها و قطعات ماشین‌آلات سنگین با خدمات عملیات حرارتی.',
            'grades' => '1.7225 (42CrMo4), 1.6511 (4140), 1.6582 (34CrNiMo6), 1.0503 (C45), 1.1191 (C60), 1.0050 (ST52), 1.7035 (41Cr4), 1.2842 (50CrMo4)',
            'product_count' => 94,
            'popularity' => 90
        ),
        array(
            'title' => 'فولاد فنر',
            'title_en' => 'Spring Steel',
            'url' => home_url('/spring-steel/'),
            'image' => 'spring-steel.webp',
            'description' => 'فولادهای با الاستیسیته بالا برای ساخت فنرهای صنعتی و خودرویی. ورق و میل فنری با کیفیت استاندارد اروپا و قیمت عمده.',
            'grades' => '1.8159 (51CrV4), 1.7108 (50CrV4), 1.0905 (SUP9), 1.1274 (SUP10), 1.1191 (65Mn), 1.7176 (55Cr3), 1.8401 (60Si7), 1.5026 (54SiCr6)',
            'product_count' => 38,
            'popularity' => 75
        ),
        array(
            'title' => 'فولاد کربنی',
            'title_en' => 'High Carbon Steel',
            'url' => home_url('/high-carbon/'),
            'image' => 'high-carbon-steel.webp',
            'description' => 'فولادهای کربن بالا با قابلیت سخت‌شوندگی عالی برای ابزارآلات و کاربردهای صنعتی. تامین با گواهینامه کیفیت معتبر و تست متالوژیکی.',
            'grades' => '1.1191 (CK60), 1.1231 (CK67), 1.0601 (C60E), 1.1274 (CS70), 1.0603 (C80E), 1.1525 (C100W2), 1.1545 (C105W2), 1.0535 (C55E)',
            'product_count' => 67,
            'popularity' => 80
        ),
        array(
            'title' => 'ورق ضد سایش',
            'title_en' => 'Abrasion Resistant Plate',
            'url' => home_url('/high-carbon/ar-plate/'),
            'image' => 'ar-plate.webp',
            'description' => 'ورق‌های با سختی فوق‌العاده و مقاومت برتر در برابر سایش برای صنایع سنگین و معدنی. Hardox و AR با امکان برش و خم و خدمات مهندسی.',
            'grades' => 'Hardox 400, Hardox 450, Hardox 500, Hardox 550, Hardox 600, AR400, AR500, AR550',
            'product_count' => 41,
            'popularity' => 87
        ),
        array(
            'title' => 'فلزات رنگی',
            'title_en' => 'Non-Ferrous Metals',
            'url' => home_url('/non-ferrous/'),
            'image' => 'non-ferrous.webp',
            'description' => 'برنج، آلومینیوم، مس و آلیاژهای غیرآهنی با کیفیت بالا. تنوع سایز کامل و قیمت‌های رقابتی برای صنایع برق، الکترونیک و معماری.',
            'grades' => 'CuZn37 (برنج 63-37), CuZn40 (برنج 60-40), EN AW-6061, EN AW-6082, Cu-ETP (مس الکترولیت), CuSn8 (برنز فسفری), CuNi10Fe (کوپرونیکل), AlMg3',
            'product_count' => 112,
            'popularity' => 83
        ),
        array(
            'title' => 'فولاد ساختمانی',
            'title_en' => 'Structural Steel',
            'url' => home_url('/structural-steel/'),
            'image' => 'structural-steel.webp',
            'description' => 'تیرآهن، ناودانی، میلگرد و پروفیل‌های فولادی برای پروژه‌های عمرانی و صنعتی. تامین با استانداردهای ملی، تحویل در محل و برش رایگان.',
            'grades' => '1.0037 (ST37), 1.0044 (ST44), 1.0050 (ST52), 1.0038 (S235JR), 1.0116 (S275J0), 1.0577 (S355J2), A36, A572 Gr50',
            'product_count' => 203,
            'popularity' => 94
        )
    );

    $schema_items = array();
    
    foreach ($categories as $index => $cat) {
        $rating_value = min(5, max(1, number_format(($cat['popularity'] / 20), 1))); 
        $img_url = filter_var($cat['image'], FILTER_VALIDATE_URL) 
            ? $cat['image'] 
            : get_stylesheet_directory_uri() . '/assets/images/categories/' . $cat['image'];

        $schema_items[] = array(
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => array(
                '@type' => 'ProductGroup',
                '@id' => $cat['url'] . '#product-group',
                'name' => $cat['title'],
                'alternateName' => $cat['title_en'],
                'description' => $cat['description'],
                'url' => $cat['url'],
                'image' => $img_url,
                'variesBy' => array(
                    '@type' => 'PropertyValue',
                    'name' => 'Steel Grades',
                    'value' => $cat['grades']
                ),
                'brand' => array(
                    '@type' => 'Brand',
                    'name' => 'فولادمارکت'
                ),
                'aggregateRating' => array(
                    '@type' => 'AggregateRating',
                    'ratingValue' => $rating_value,
                    'bestRating' => '5',
                    'worstRating' => '1',
                    'ratingCount' => $cat['product_count'],
                ),
                'offers' => array(
                    '@type' => 'AggregateOffer',
                    'priceCurrency' => 'IRR',
                    'availability' => 'https://schema.org/InStock',
                    'offerCount' => $cat['product_count'],
                )
            )
        );
    }
    
    $schema = array(
        '@type' => 'ItemList',
        '@id' => home_url('/#categories'),
        'name' => 'دسته‌بندی محصولات فولادی',
        'description' => 'لیست دسته‌بندی‌های اصلی محصولات فولادی موجود در فولادمارکت',
        'numberOfItems' => count($categories),
        'itemListElement' => $schema_items
    );
    
    set_transient($cache_key, $schema, DAY_IN_SECONDS);
    $foladmarket_schema_graph[] = $schema;
}
// CHANGED: wp_footer -> wp_head
add_action('wp_head', 'foladmarket_homepage_category_schema', 5);

/**
 * 2. DAILY PRICING SCHEMA
 */
function foladmarket_daily_pricing_schema() {
    if (!is_front_page()) return;

    global $foladmarket_schema_graph;
    $cache_key = 'foladmarket_pricing_schema_v4_2';
    $cached = get_transient($cache_key);
    
    if ($cached !== false) {
        $foladmarket_schema_graph[] = $cached;
        return;
    }

    $recent_products = get_posts([
        'post_type' => 'product',
        'posts_per_page' => 8,
        'orderby' => 'date',
        'order' => 'DESC',
        'fields' => 'ids'
    ]);

    $schema_items = [];
    $position = 1;

    foreach ($recent_products as $pid) {
        if (!function_exists('get_field')) continue;
        $rows = get_field('product_rows', $pid);
        if (empty($rows) || !isset($rows[0])) continue;

        $row = $rows[0];
        $price = isset($row['product_price']) ? floatval($row['product_price']) : 0;
        
        if ($price <= 0) continue;

        $thumb = get_the_post_thumbnail_url($pid, 'thumbnail');
        $image_url = $thumb ? $thumb : get_template_directory_uri() . '/assets/images/steel-op.webp';
        $product_url = get_permalink($pid);

        $schema_items[] = [
            '@type' => 'ListItem',
            'position' => $position,
            'item' => [
                '@type' => 'Product',
                '@id' => $product_url . '#product',
                'name' => $row['product_name'] ?? get_the_title($pid),
                'url' => $product_url,
                'sku' => $row['product_code'] ?? '',
                'image' => $image_url,
                'offers' => [
                    '@type' => 'Offer',
                    'price' => $price,
                    'priceCurrency' => 'IRR',
                    'availability' => 'https://schema.org/InStock',
                    'priceValidUntil' => date('Y-m-d', strtotime('+7 days'))
                ]
            ]
        ];
        $position++;
    }

    if (empty($schema_items)) return;

    $schema = [
        '@type' => 'OfferCatalog',
        '@id' => home_url('/#daily-prices'),
        'name' => 'قیمت روز فولادهای صنعتی',
        'description' => 'آخرین قیمت‌های به‌روزرسانی شده در بازار آهن',
        'numberOfItems' => count($schema_items),
        'itemListElement' => $schema_items
    ];

    set_transient($cache_key, $schema, 2 * HOUR_IN_SECONDS);
    $foladmarket_schema_graph[] = $schema;
}
// CHANGED: wp_footer -> wp_head
add_action('wp_head', 'foladmarket_daily_pricing_schema', 6);

/**
 * 3. GLOBAL SCHEMAS (Breadcrumb & Organization)
 */
function foladmarket_global_schemas() {
    global $foladmarket_schema_graph;
    
    // Breadcrumb (Fixed)
    $bc_cache_key = 'foladmarket_breadcrumb_schema_v4_2';
    $bc_schema = get_transient($bc_cache_key);
    
    if ($bc_schema === false) {
        $bc_schema = array(
            '@type' => 'BreadcrumbList',
            '@id' => home_url('/#breadcrumb'),
            'itemListElement' => array(
                array(
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'خانه',
                    'item' => array(
                        '@id' => home_url('/'),
                        'name' => 'فولادمارکت'
                    )
                ),
                array(
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'محصولات',
                    'item' => array(
                        '@id' => home_url('/products/'),
                        'name' => 'محصولات'
                    )
                )
            )
        );
        set_transient($bc_cache_key, $bc_schema, DAY_IN_SECONDS);
    }
    $foladmarket_schema_graph[] = $bc_schema;
    
    // Organization
    $org_cache_key = 'foladmarket_organization_schema_v4_2';
    $org_schema = get_transient($org_cache_key);
    
    if ($org_schema === false) {
        $org_schema = array(
            '@type' => 'Organization',
            '@id' => home_url('/#organization'),
            'name' => 'فولادمارکت',
            'alternateName' => 'Foladmarket',
            'slogan' => 'تأمین‌کننده مطمئن صنعت',
            'foundingDate' => '2009',
            'url' => home_url('/'),
            'logo' => get_stylesheet_directory_uri() . '/assets/images/logo.png',
            'description' => 'فولادمارکت با بیش از ۱۵ سال تجربه، مرجع تخصصی تامین و توزیع انواع فولادهای صنعتی، آلیاژی و فلزات رنگی در ایران است.',
            'address' => array(
                '@type' => 'PostalAddress',
                'streetAddress' => 'رو به روی بازار آهن شاد آباد، ۴۵ متری زرند، نبش خیابان امری، مجتمع تجارت فلزات پارسه، پلاک ۴',
                'addressLocality' => 'تهران',
                'addressRegion' => 'تهران',
                'postalCode' => '1371915971',
                'addressCountry' => 'IR'
            ),
            'contactPoint' => array(
                '@type' => 'ContactPoint',
                'telephone' => '+98-21-9200-3255',
                'contactType' => 'customer service',
                'areaServed' => 'IR',
                'availableLanguage' => array('fa', 'en'),
                'email' => 'foladmarket@gmail.com'
            ),
            'sameAs' => array(
                'https://www.instagram.com/foladmarket',
                'https://www.linkedin.com/company/foladmarket',
                'https://t.me/+989122833844',
                'https://wa.me/+989122833844'
            )
        );
        set_transient($org_cache_key, $org_schema, 7 * DAY_IN_SECONDS);
    }
    $foladmarket_schema_graph[] = $org_schema;
}
// CHANGED: wp_footer -> wp_head
add_action('wp_head', 'foladmarket_global_schemas', 4);

/**
 * 4. TOOLS SECTION SCHEMA 
 */
function foladmarket_tools_schema() {
    if (!is_front_page()) return;

    global $foladmarket_schema_graph;

    $tools_data = array(
        array(
            '@type' => 'SoftwareApplication',
            'name' => 'ماشین حساب وزن فولاد',
            'description' => 'محاسبه دقیق وزن انواع میلگرد، تسمه، ورق و مقاطع فولادی',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web Browser',
            'offers' => array(
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'IRR'
            ),
            'url' => home_url('/weight-calculator/'),
            'featureList' => array('محاسبه آنی', 'پشتیبانی از 50+ مقطع', 'تخمین هزینه')
        ),
        array(
            '@type' => 'SoftwareApplication',
            'name' => 'مقایسه‌گر گریدهای فولادی',
            'description' => 'مقایسه جامع ترکیب شیمیایی و خواص مکانیکی گریدهای مختلف فولاد',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem' => 'Web Browser',
            'offers' => array(
                '@type' => 'Offer',
                'price' => '0',
                'priceCurrency' => 'IRR'
            ),
            'url' => home_url('/steel-comparison/'),
            'featureList' => array('مقایسه همزمان', 'ترکیبات عنصری', 'معادل‌های بین‌المللی')
        ),
        array(
            '@type' => 'Service',
            'name' => 'سامانه استعلام قیمت آنلاین',
            'description' => 'دریافت قیمت لحظه‌ای و رقابتی فولاد با پاسخ‌گویی سریع',
            'serviceType' => 'استعلام قیمت فولاد',
            'url' => home_url('/price/')
        )
    );

    $list_items = array();
    foreach ($tools_data as $index => $item) {
        $list_items[] = array(
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => $item
        );
    }

    $schema = array(
        '@type' => 'DataCatalog',
        '@id' => home_url('/#tools-catalog'),
        'name' => 'ابزارهای تخصصی فولادمارکت',
        'description' => 'ابزارهای کاربردی محاسبه و استعلام برای خریداران فولاد',
        'url' => home_url('/#tools'),
        'itemListElement' => $list_items
    );

    $foladmarket_schema_graph[] = $schema;
}
// CHANGED: wp_footer -> wp_head
add_action('wp_head', 'foladmarket_tools_schema', 7);

/**
 * 5. FAQ SECTION SCHEMA
 */
function foladmarket_faq_schema() {
    if (!is_front_page()) return;

    global $foladmarket_schema_graph;
    $cache_key = 'foladmarket_faq_schema_v4_2';
    $cached = get_transient($cache_key);
    
    if ($cached !== false) {
        $foladmarket_schema_graph[] = $cached;
        return;
    }

    $schema = array(
        '@type' => 'FAQPage',
        '@id' => home_url('/#faq'),
        'mainEntity' => array(
            array(
                '@type' => 'Question',
                'name' => 'آیا محصولات فولادمارکت دارای گواهینامه هستند؟',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => 'بله، تمام محصولات ما دارای گواهی آنالیز شیمیایی و Certificate های معتبر بین‌المللی هستند که همراه با محصول ارائه می‌شود. این گواهی‌ها شامل اطلاعات کامل ترکیب شیمیایی، شماره ذوب، کارخانه تولیدکننده و استانداردهای رعایت شده (DIN, AISI, JIS) است.'
                )
            ),
            array(
                '@type' => 'Question',
                'name' => 'زمان تحویل محصولات چقدر است؟',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => 'محصولات موجود در انبار تهران در کمتر از ۲۴ ساعت و برای شهرستان‌ها بین ۲ تا ۵ روز کاری تحویل داده می‌شود. برای سفارشات خاص که نیاز به تامین از کارخانه دارند، زمان تحویل بسته به نوع محصول و میزان سفارش متغیر است که هنگام استعلام اطلاع‌رسانی می‌شود.'
                )
            ),
            array(
                '@type' => 'Question',
                'name' => 'آیا امکان خرید عمده و صنعتی وجود دارد؟',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => 'بله، فولادمارکت متخصص تامین سفارشات عمده و صنعتی است. برای خریدهای بالای یک تن، تخفیفات ویژه‌ای در نظر گرفته می‌شود. همچنین امکان اخذ پیش‌فاکتور، قرارداد بلندمدت و شرایط پرداخت اقساطی برای مشتریان حقوقی فراهم است.'
                )
            ),
            array(
                '@type' => 'Question',
                'name' => 'چگونه قیمت روز فولاد را دریافت کنم؟',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => 'برای دریافت قیمت روز می‌توانید از سه طریق اقدام کنید: ۱) ثبت درخواست در فرم استعلام آنلاین سایت ۲) تماس تلفنی با شماره ۰۲۱-۹۲۰۰۳۲۵۵ ۳) ارسال پیام واتساپ به شماره ۰۹۱۲۲۸۳۳۸۴۴. کارشناسان ما حداکثر ظرف ۲ ساعت کاری قیمت دقیق و به‌روز را اعلام خواهند کرد.'
                )
            ),
            array(
                '@type' => 'Question',
                'name' => 'آیا خدمات برش و فرآوری ارائه می‌دهید؟',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => 'بله، فولادمارکت خدمات برش به ابعاد دلخواه، عملیات حرارتی (کوئنچ، تمپر، نرماله‌سازی)، سطح‌بندی، سنگ‌زنی و حتی ماشین‌کاری اولیه را ارائه می‌دهد. شما می‌توانید نقشه و مشخصات قطعه مورد نیاز خود را ارسال کنید تا محصول آماده تحویل گرفته شود.'
                )
            ),
            array(
                '@type' => 'Question',
                'name' => 'تفاوت فولادهای ابزاری با فولادهای کربنی چیست؟',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => 'فولادهای ابزاری (Tool Steel) حاوی عناصر آلیاژی مانند کروم، مولیبدن، وانادیم و تنگستن هستند که باعث افزایش سختی، مقاومت به سایش و پایداری در دماهای بالا می‌شود. این فولادها برای ساخت قالب، ابزار برشی و قطعات با استهلاک بالا استفاده می‌شوند. در مقابل، فولادهای کربنی (مانند ST37، CK45) آلیاژ کمتری دارند و برای کاربردهای عمومی ساختمانی و مکانیکی مناسب‌تر هستند.'
                )
            ),
            array(
                '@type' => 'Question',
                'name' => 'نحوه ارتباط با کارشناسان فنی فولادمارکت چگونه است؟',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => 'شما می‌توانید از طریق تماس تلفنی با شماره ۰۲۱-۹۲۰۰۳۲۵۵ (در ساعات اداری)، پیام واتساپ به شماره ۰۹۱۲۲۸۳۳۸۴۴ (۲۴ ساعته)، یا ثبت درخواست در فرم تماس سایت با کارشناسان فولادمارکت در ارتباط باشید.'
                )
            ),
            array(
                '@type' => 'Question',
                'name' => 'آیا فولادمارکت فقط به تهران خدمات ارائه می‌دهد؟',
                'acceptedAnswer' => array(
                    '@type' => 'Answer',
                    'text' => 'خیر، فولادمارکت به تمام شهرهای ایران خدمات ارسال دارد. ما با شرکت‌های باربری معتبر و بیمه شده همکاری می‌کنیم تا محصولات را با بسته‌بندی استاندارد و ایمن به مقصد شما برسانیم.'
                )
            )
        )
    );

    set_transient($cache_key, $schema, 30 * DAY_IN_SECONDS);
    $foladmarket_schema_graph[] = $schema;
}
// CHANGED: wp_footer -> wp_head
add_action('wp_head', 'foladmarket_faq_schema', 8);

/**
 * 6. USP / SERVICES SCHEMA
 */
function foladmarket_usp_schema() {
    if (!is_front_page()) return;
    
    global $foladmarket_schema_graph;
    
    $services_data = array(
        array(
            '@type' => 'Service',
            'name' => 'تضمین کیفیت و اصالت کالا',
            'description' => 'ارائه گواهینامه EN 10204 – 3.1، درج Heat Number و Cast Number جهت ردیابی کامل',
            'serviceType' => 'Quality Assurance'
        ),
        array(
            '@type' => 'Service',
            'name' => 'تضمین تامین پایدار',
            'description' => 'توانایی تامین فولادهای آلیاژی و ابزار در احجام خرد، تناژی و پروژه‌ای',
            'serviceType' => 'Supply Chain Management'
        ),
        array(
            '@type' => 'Service',
            'name' => 'برش و کنترل ابعادی',
            'description' => 'برش CNC و کنترل دقیق ابعاد طبق نقشه با گواهی تایید ابعاد',
            'serviceType' => 'Cutting Service'
        ),
        array(
            '@type' => 'Service',
            'name' => 'مشاوره فنی و عملیات حرارتی',
            'description' => 'ارائه خدمات مشاوره مواد و عملیات حرارتی متناسب با کاربرد خاص',
            'serviceType' => 'Technical Consulting'
        ),
        array(
            '@type' => 'Service',
            'name' => 'قیمت‌گذاری شفاف',
            'description' => 'اعلام قیمت شفاف مبتنی بر شرایط بازار و ارائه مشاوره ریسک مالی',
            'serviceType' => 'Financial Service'
        ),
        array(
            '@type' => 'Service',
            'name' => 'رهگیری دیجیتال سفارشات',
            'description' => 'ثبت سفارش، رهگیری وضعیت و دریافت مستندات به صورت دیجیتال',
            'serviceType' => 'Digital Tracking'
        )
    );

    $list_items = array();
    foreach ($services_data as $index => $item) {
        $list_items[] = array(
            '@type' => 'ListItem',
            'position' => $index + 1,
            'item' => $item
        );
    }

    $schema = array(
        '@type' => 'ItemList',
        '@id' => home_url('/#services-list'),
        'name' => 'خدمات و مزایای فولادمارکت',
        'itemListElement' => $list_items
    );

    $foladmarket_schema_graph[] = $schema;
}
// CHANGED: wp_footer -> wp_head
add_action('wp_head', 'foladmarket_usp_schema', 9);

/**
 * 7. PARTNERS
 */
function foladmarket_partners_schema() {
    if (!is_front_page()) return;

    global $foladmarket_schema_graph;

    $suppliers_list = [
        ['name' => 'ذوب آهن اصفهان', 'url' => '/articles/steel-suppliers/%da%a9%d8%a7%d8%b1%d8%ae%d8%a7%d9%86%d9%87-%d8%b0%d9%88%d8%a8-%d8%a2%d9%87%d9%86-%d8%a7%d8%b5%d9%81%d9%87%d8%a7%d9%86/', 'image' => '/wp-content/uploads/2025/12/1_zobahan.webp'],
        ['name' => 'پرشین فولاد', 'url' => '/articles/steel-suppliers/%da%af%d8%b1%d9%88%d9%87-%d8%b5%d9%86%d8%b9%d8%aa%db%8c-%d9%be%d8%b1%d8%b4%db%8c%d9%86-%d9%81%d9%88%d9%84%d8%a7%d8%af/', 'image' => '/wp-content/uploads/2025/12/2_persian.webp'],
        ['name' => 'فولاد آلیاژی یزد', 'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%a2%d9%84%db%8c%d8%a7%da%98%db%8c-%db%8c%d8%b2%d8%af/', 'image' => '/wp-content/uploads/2025/12/3_yazd.webp'],
        ['name' => 'جهان فولاد سیرجان', 'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%b3%db%8c%d8%b1%d8%ac%d8%a7%d9%86/', 'image' => '/wp-content/uploads/2025/12/4_sirjan.webp'],
        ['name' => 'فولاد ماهان سپاهان', 'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d9%85%d8%a7%d9%87%d8%a7%d9%86-%d8%b3%d9%be%d8%a7%d9%87%d8%a7%d9%86/', 'image' => '/wp-content/uploads/2025/12/5_sepahan.webp'],
        ['name' => 'فولاد البرز ایرانیان فایکو', 'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%a7%d9%84%d8%a8%d8%b1%d8%b2-%d8%a7%db%8c%d8%b1%d8%a7%d9%86%db%8c%d8%a7%d9%86-%d9%81%d8%a7%db%8c%da%a9%d9%88/', 'image' => '/wp-content/uploads/2025/12/6_FAICO.webp'],
        ['name' => 'فولاد ناب تبریز', 'url' => '/articles/steel-suppliers/%d9%81%d9%88%d9%84%d8%a7%d8%af-%d9%86%d8%a7%d8%a8-%d8%aa%d8%a8%d8%b1%db%8c%d8%b2/', 'image' => '/wp-content/uploads/2025/12/7_tabriz.webp'],
        ['name' => 'فولاد کویر کاشان', 'url' => '/articles/steel-suppliers/%d9%81%d9%88%d9%84%d8%a7%d8%af-%da%a9%d9%88%db%8c%d8%b1-%da%a9%d8%a7%d8%b4%d8%a7%d9%86/', 'image' => '/wp-content/uploads/2025/12/8_kavir.webp'],
        ['name' => 'فولاد کاویان', 'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%da%a9%d8%a7%d9%88%db%8c%d8%a7%d9%86/', 'image' => '/wp-content/uploads/2025/12/9_kavian.webp'],
        ['name' => 'صنایع هفت الماس', 'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d8%b5%d9%86%d8%a7%db%8c%d8%b9-%d9%87%d9%81%d8%aa-%d8%a7%d9%84%d9%85%d8%a7%d8%b3/', 'image' => '/wp-content/uploads/2025/12/10_haftalmas.webp'],
        ['name' => 'فولاد اکسین خوزستان', 'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%a7%da%a9%d8%b3%db%8c%d9%86-%d8%ae%d9%88%d8%b2%d8%b3%d8%aa%d8%a7%d9%86/', 'image' => '/wp-content/uploads/2025/12/11_oxin.webp'],
        ['name' => 'فولاد مبارکه اصفهان', 'url' => '/articles/steel-suppliers/%d8%b4%d8%b1%da%a9%d8%aa-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d9%85%d8%a8%d8%a7%d8%b1%da%a9%d9%87-%d8%a7%d8%b5%d9%81%d9%87%d8%a7%d9%86/', 'image' => '/wp-content/uploads/2025/12/12_mobarake.webp'],
        ['name' => 'فولاد آساب', 'url' => '/articles/steel-suppliers/%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%a2%d8%b3%d8%a7%d8%a8/', 'image' => '/wp-content/uploads/2025/12/13_ASSAB.webp'],
        ['name' => 'فولاد بوهلر', 'url' => '/articles/steel-suppliers/%da%a9%d8%a7%d8%b1%d8%ae%d8%a7%d9%86%d9%87-%d8%aa%d9%88%d9%84%db%8c%d8%af-%d9%81%d9%88%d9%84%d8%a7%d8%af-%d8%a8%d9%88%d9%87%d9%84%d8%b1bohler/', 'image' => '/wp-content/uploads/2025/12/14_boehler.webp']
    ];

    $itemListElement = [];
    $position = 1;

    foreach ($suppliers_list as $supplier) {
        $itemListElement[] = array(
            '@type' => 'ListItem',
            'position' => $position,
            'item' => array(
                '@type' => 'Organization',
                'name' => $supplier['name'],
                'url' => home_url($supplier['url']),
                'logo' => home_url($supplier['image'])
            )
        );
        $position++;
    }

    $schema = array(
        '@type' => 'ItemList',
        '@id' => home_url('/#partners-list'),
        'name' => 'تامین‌کنندگان همکار',
        'itemListElement' => $itemListElement
    );

    $foladmarket_schema_graph[] = $schema;
}
// CHANGED: wp_footer -> wp_head
add_action('wp_head', 'foladmarket_partners_schema', 10);


/**
 * 8. OUTPUT FUNCTION (THE GRAPH GENERATOR)
 */
function foladmarket_render_final_schema() {
    global $foladmarket_schema_graph;
    
    if (empty($foladmarket_schema_graph)) return;

    $final_schema = array(
        '@context' => 'https://schema.org',
        '@graph' => $foladmarket_schema_graph
    );

    $json_flags = defined('WP_DEBUG') && WP_DEBUG 
        ? JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
        : JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;
    
    echo "\n<!-- Foladmarket Schema Graph v4.2.1 (Head Output) -->\n";
    echo '<script type="application/ld+json" class="foladmarket-schema-graph">' . "\n";
    echo wp_json_encode($final_schema, $json_flags);
    echo "\n" . '</script>' . "\n";
}
// CHANGED: wp_footer -> wp_head
add_action('wp_head', 'foladmarket_render_final_schema', 100);

/**
 * 9. UTILITY: Clear Cache
 */
function foladmarket_clear_all_schema_caches_v4_2() {
    delete_transient('foladmarket_category_schema_v4_2');
    delete_transient('foladmarket_pricing_schema_v4_2');
    delete_transient('foladmarket_breadcrumb_schema_v4_2');
    delete_transient('foladmarket_organization_schema_v4_2');
    delete_transient('foladmarket_faq_schema_v4_2');
}
add_action('after_switch_theme', 'foladmarket_clear_all_schema_caches_v4_2');
add_action('switch_theme', 'foladmarket_clear_all_schema_caches_v4_2');
