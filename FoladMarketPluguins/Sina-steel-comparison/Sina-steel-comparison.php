<?php
/**
 * Plugin Name: Sina Steel Comparison Table
 * Description: Compare up to 10 steel grades by their properties (from JSON dataset).
 * Version: 1.0
 * Author: Sina Sotoudeh
 */

if ( ! defined( 'ABSPATH' ) ) exit; // No direct access

// Register scripts
function sc_enqueue_assets() {
    wp_enqueue_script(
        'steel-comparison-js',
        plugin_dir_url(__FILE__) . 'assets/steel-comparison.js',
        array('jquery'),
        '1.0',
        true
    );
    wp_enqueue_style(
        'steel-comparison-css',
        plugin_dir_url(__FILE__) . 'assets/steel-comparison.css'
);
    // Localize JSON data
    $json_path = plugin_dir_path(__FILE__) . 'steels.json';
    $json_data = file_exists($json_path) ? file_get_contents($json_path) : '[]';
    wp_localize_script('steel-comparison-js', 'steelData', json_decode($json_data, true));
}
add_action('wp_enqueue_scripts', 'sc_enqueue_assets');

// Shortcode [steel_comparison]
function sc_render_comparison() {
    ob_start();
    ?>
    <div id="steel-comparison-app">
       <!-- Instruction text -->
<div style="display: flex; align-items: center; gap: 10px;">
    <p style="margin:0; font-weight:bold;">
        برای مشاهده و مقایسه‌ی ویژگی‌ها، فولادهای مورد نظر خود را از منوی مقابل انتخاب کنید
    </p>
    <button id="open-popup" class="sc-btn">انتخاب فولاد</button>
</div>

        <!-- Popup -->
        <div id="steel-popup" class="sc-popup">
            <div class="sc-popup-content">
                <h3>انتخاب فولادها</h3>
                <div id="steel-buttons" class="sc-steel-buttons"></div>
                <button id="compare-btn" class="sc-btn">مقایسه</button>
                <button id="close-popup" class="sc-btn sc-close">بستن</button>
            </div>
        </div>

        <!-- Table -->
        <div class="steel-table-wrapper" style="overflow-x:auto; margin-top:20px;">
            <table id="steel-table" border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; min-width:600px;">
                <tbody id="steel-body">
                    <!-- Rows filled by JS -->
                </tbody>
            </table>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

add_shortcode('steel_comparison', 'sc_render_comparison');
// Shortcode [steel_properties grade="1.2080"]
function sc_render_properties($atts) {
    $atts = shortcode_atts(array(
        'grade' => ''
    ), $atts);

    $grade = $atts['grade'];
    if (!$grade) return "<p>لطفاً گرید فولاد را مشخص کنید.</p>";

    // خواندن JSON
    $json_path = plugin_dir_path(__FILE__) . 'steels.json';
    if (!file_exists($json_path)) return "<p>فایل اطلاعات یافت نشد.</p>";

    $json_data = json_decode(file_get_contents($json_path), true);
    if (!$json_data) return "<p>اطلاعات فولاد موجود نیست.</p>";

    // جستجوی فولاد مورد نظر
    $steel = null;
    foreach ($json_data as $item) {
        if ($item['گرید فولاد'] == $grade) {
            $steel = $item;
            break;
        }
    }

    if (!$steel) return "<p>گرید {$grade} در دیتابیس پیدا نشد.</p>";

    // ساخت جدول
    ob_start();
    ?>
    <div class="steel-properties">
        <h3>مشخصات فولاد <?php echo esc_html($grade); ?></h3>
        <table border="1" cellpadding="5" cellspacing="0" style="border-collapse:collapse; min-width:400px;">
            <tbody>
                <?php foreach ($steel as $key => $value): ?>
                    <tr>
                        <th style="background:#f4f4f4; text-align:right;"><?php echo esc_html($key); ?></th>
                        <td><?php echo esc_html($value ? $value : '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('steel_properties', 'sc_render_properties');

// helper: گرفتن یک آیتم فولاد از JSON بر اساس گرید
function sc_get_steel_by_grade( $grade ) {
    $json_path = plugin_dir_path(__FILE__) . 'steels.json';
    if ( ! file_exists( $json_path ) ) return null;
    $json_data = json_decode( file_get_contents( $json_path ), true );
    if ( ! $json_data ) return null;

    foreach ( $json_data as $item ) {
        if ( isset($item['گرید فولاد']) && $item['گرید فولاد'] == $grade ) {
            return $item;
        }
    }
    return null;
}

function sc_format_value( $v ) {
    // نمایش خط فاصله برای مقادیر null/empty
    if ( $v === null || $v === '' ) return '–';
    return esc_html( $v );
}

// Map عناصر شیمیایی (کلید JSON => نام فارسی)
function sc_chem_map() {
    return array(
        'C'  => 'کربن (C)',
        'Si' => 'سیلیکون (Si)',
        'Mn' => 'منگنز (Mn)',
        'P'  => 'فسفر (P)',
        'S'  => 'گوگرد (S)',
        'Cr' => 'کروم (Cr)',
        'W'  => 'تنگستن (W)',
        'Mo' => 'مولیبدنوم (Mo)',
        'V'  => 'وانادیوم (V)',
        'Ni' => 'نیکل (Ni)',
        'Co' => 'کبالت (Co)',
    );
}

// فرمت مقدار عنصر (رِنج -> "بین a تا b درصد", عدد -> "a درصد" یا متن خام)
function sc_format_chem_value( $raw ) {
    if ( $raw === null ) return null;

    // نرمال‌سازی ایندیکاتور خالی/خط فاصله
    $trim = trim( (string) $raw );
    if ( $trim === '' ) return null;
    // common dash characters
    $dash_chars = array('-', '–', '—', '−');
    if ( in_array($trim, $dash_chars, true) ) return null;

    // Range like "1.9-2.2" or "1 - 2.3"
    if ( preg_match( '/^\s*([0-9]+(?:\.[0-9]+)?)\s*-\s*([0-9]+(?:\.[0-9]+)?)\s*$/u', $trim, $m ) ) {
        $a = $m[1];
        $b = $m[2];
        return "بین {$a} تا {$b} درصد";
    }

    // Single numeric (like "1.5") => اضافه کردن "درصد"
    if ( is_numeric( $trim ) ) {
        return "{$trim} درصد";
    }

    // Otherwise return as-is (متون توصیفی)
    return esc_html( $trim );
}

/**
 * Shortcode: [steel_composition grade="1.2080"]
 * نمایش ترکیب شیمیایی (فقط عناصر دارای مقدار) با نام فارسی و فرمت رِنج
 */
function sc_render_composition( $atts ) {
    $atts = shortcode_atts( array( 'grade' => '' ), $atts );
    $grade = $atts['grade'];
    if ( ! $grade ) return '<p>لطفاً گرید فولاد را مشخص کنید.</p>';

    $steel = sc_get_steel_by_grade( $grade );
    if ( ! $steel ) return "<p>گرید {$grade} در دیتابیس پیدا نشد.</p>";

    $chem_map = sc_chem_map();

    // Build list items for elements that have meaningful value
    $items = array();
    foreach ( $chem_map as $key => $label ) {
        if ( array_key_exists( $key, $steel ) ) {
            $formatted = sc_format_chem_value( $steel[ $key ] );
            if ( $formatted !== null && $formatted !== '' ) {
                $items[] = array( 'label' => $label, 'value' => $formatted );
            }
        }
    }

    if ( empty( $items ) ) {
        return "<p>برای گرید {$grade} اطلاعات ترکیب شیمیایی موجود نیست.</p>";
    }

    ob_start();
    ?>
    <div>
        <strong>ترکیب شیمیایی فولاد <?php echo esc_html($grade); ?>:</strong>
        <ul style="margin:6px 0 0 0; padding-right:18px; list-style:disc; line-height:1.25;">
            <?php foreach ( $items as $it ): ?>
                <li><strong style="margin-left:6px;"><?php echo esc_html($it['label']); ?>:</strong> <?php echo $it['value']; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'steel_composition', 'sc_render_composition' );


/**
 * Shortcode: [steel_physical grade="1.2080"]
 * نمایش خواص فیزیکی / عملکردی (فقط موارد دارای مقدار)
 */
function sc_render_physical( $atts ) {
    $atts = shortcode_atts( array( 'grade' => '' ), $atts );
    $grade = $atts['grade'];
    if ( ! $grade ) return '<p>لطفاً گرید فولاد را مشخص کنید.</p>';

    $steel = sc_get_steel_by_grade( $grade );
    if ( ! $steel ) return "<p>گرید {$grade} در دیتابیس پیدا نشد.</p>";

    $phys_keys = array(
        'مقاومت در برابر سایش',
        'مقاومت در برابر چسبندگی',
        'چقرمگی',
        'پایداری ابعادی',
        'مقاومت سایشی در دمای بالا',
        'چقرمگی در دمای بالا',
        'سختی نهایی'
    );

    $items = array();
    foreach ( $phys_keys as $k ) {
        if ( array_key_exists($k, $steel) ) {
            $raw = $steel[$k];
            // استفاده از همان تابع قالب‌بندی (اگر لازم شد می‌توان آن را متفاوت کرد)
            $formatted = sc_format_chem_value( $raw );
            // اگر مقدار متنی است (مثلاً "بسیار بالا") sc_format_chem_value آن را بازمی‌گرداند (escaped)
            if ( $formatted !== null && $formatted !== '' ) {
                $items[] = array( 'label' => $k, 'value' => $formatted );
            }
        }
    }

    if ( empty( $items ) ) {
        return "<p>برای گرید {$grade} اطلاعات خواص فیزیکی موجود نیست.</p>";
    }

    ob_start();
    ?>
    <div>
        <strong>خواص فیزیکی / عملکردی فولاد <?php echo esc_html($grade); ?>:</strong>
        <ul style="margin:6px 0 0 0; padding-right:18px; list-style:disc; line-height:1.25;">
            <?php foreach ( $items as $it ): ?>
                <li><strong style="margin-left:6px;"><?php echo esc_html($it['label']); ?>:</strong> <?php echo $it['value']; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'steel_physical', 'sc_render_physical' );

