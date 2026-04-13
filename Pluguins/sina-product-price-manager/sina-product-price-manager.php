<?php
/*
Plugin Name: Sina Product Price Manager (مهین پرایس)
Description: مدیریت نرخ دلار و بروز رسانی قیمت محصولات 
Version: 1.0
Author: Sina Sotoudeh
*/
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

// 1. افزودن منوهای Tools
add_action('admin_menu', function(){
    add_menu_page('مهین پرایس', 'مهین پرایس', 'manage_options',
        'sina-custom-plugin', '', 'dashicons-admin-generic', 60
    );
    // زیرمنوی آپدیت دلار
    add_submenu_page('sina-custom-plugin', 'آپدیت نرخ دلار', 'آپدیت نرخ دلار',
        'manage_options','update-dollar-rate','render_update_dollar_rate_page'
    );
    // زیرمنوی بروز محصولات
    add_submenu_page('sina-custom-plugin','آپدیت قیمت محصولات','آپدیت قیمت محصولات',
        'manage_options','update-product-prices','render_update_products_page'
    );
    add_submenu_page(
    'sina-custom-plugin',
    'تغییر قیمت سفارشی',
    'تغییر قیمت سفارشی',
    'manage_options',
    'custom-delta-update',
    'render_custom_delta_page'
);
    add_submenu_page(
        'sina-custom-plugin',
        'اکسپورت اکسل',
        'اکسپورت اکسل',
        'manage_options',
        'export-prices-to-excel',
        'render_export_excel_page'
    );
});



function render_update_dollar_rate_page(){
    if(!current_user_can('manage_options')) return;
    if(isset($_POST['upd_rate']) && check_admin_referer('upd_rate_action','upd_rate_nonce')){
        echo '<div class="notice notice-info"><p>شروع فراخوانی API …</p></div>';

        // 1. انتقال نرخ امروز به دیروز
        $prev = get_option('daily_dollar_today_rate', 0);
        update_option('daily_dollar_yesterday_rate', $prev);

        // 2. فراخوانی API
        $url = 'https://brsapi.ir/Api/Market/Gold_Currency.php?key=Freee8Rnkf0cByGXacfRHeH3kH1F0Abd';
        $response = wp_remote_get($url, ['timeout'=>10]);

        // 2.a خطای WP_Error
        if ( is_wp_error($response) ) {
            $err = $response->get_error_message();
            error_log("SinaPlugin ERROR: wp_remote_get failed: {$err}");
            echo '<div class="notice notice-error"><p>خطا در اتصال به API: '.esc_html($err).'</p></div>';
            return;
        }

        // 2.b کد وضعیت HTTP
        $code = wp_remote_retrieve_response_code($response);
        if ( 200 !== intval($code) ) {
            $body = wp_remote_retrieve_body($response);
            error_log("SinaPlugin ERROR: HTTP status {$code}, body: {$body}");
            echo '<div class="notice notice-error"><p>API وضعیت '.esc_html($code).' داد.</p></div>';
            return;
        }

        // 3. دیکد JSON
        $body = wp_remote_retrieve_body($response);
        $json = json_decode($body, true);
        if ( null === $json ) {
            $err = json_last_error_msg();
            error_log("SinaPlugin ERROR: JSON decode failed: {$err}, raw body: {$body}");
            echo '<div class="notice notice-error"><p>خطا در دیکد JSON: '.esc_html($err).'</p></div>';
            return;
        }

        // 4. وجود کلید currency
        if ( ! isset($json['currency']) || ! is_array($json['currency']) ) {
            error_log("SinaPlugin ERROR: JSON missing 'currency' key. JSON keys: ".implode(',', array_keys($json)));
            echo '<div class="notice notice-error"><p>دادهٔ ' . esc_html($body) . ' ساختار درستی ندارد.</p></div>';
            return;
        }

        // 5. خواندن نرخ دلار از آرایه
        $today = 0;
        foreach($json['currency'] as $c){
            if(isset($c['symbol']) && $c['symbol']==='USD'){
                $today = floatval($c['price']);
                break;
            }
        }
        if ( $today <= 0 ) {
            error_log("SinaPlugin ERROR: USD rate not found or zero. currency items: ".print_r($json['currency'],true));
            echo '<div class="notice notice-error"><p>نرخ دلار یافت نشد.</p></div>';
            return;
        }

        // 6. ذخیره نرخ امروز
        update_option('daily_dollar_today_rate', $today);
        echo '<div class="notice notice-success"><p>نرخ امروز با موفقیت به '.esc_html($today).' به‌روزرسانی شد.</p></div>';
    }

    // فرم HTML
    ?>
    <div class="wrap"><h1>به‌روزرسانی نرخ دلار</h1>
      <form method="post">
        <?php wp_nonce_field('upd_rate_action','upd_rate_nonce'); ?>
        <p>نرخ دیروز: <strong><?php echo get_option('daily_dollar_yesterday_rate','–'); ?></strong></p>
        <p>نرخ امروز: <strong><?php echo get_option('daily_dollar_today_rate','–'); ?></strong></p>
        <p><button name="upd_rate" class="button button-primary">بروز رسانی نرخ</button></p>
      </form>
    </div>
    <?php
}


/**
 * به‌روزرسانی قیمت محصولات با دلتا
 * @param int $post_id شناسهٔ پست محصول
 */
function manual_update_product_prices( $post_id ) {
    // ۱. خواندن نرخ دلار و دلتا
    $today     = floatval( get_option('daily_dollar_today_rate', 0) );
    $yesterday = floatval( get_option('daily_dollar_yesterday_rate', 0) );
$fixed = 7500;
$delta = $today > $yesterday
           ?  $fixed    // اگر امروز بزرگتر از دیروز بود +7500
           : ( $today < $yesterday
               ? -$fixed // اگر امروز کوچکتر از دیروز بود −7500
               : 0       // در غیر این صورت (مساوی) 0
             );

    // ۲. واکشی ردیف‌های ACF
    $rows = get_field('product_rows', $post_id) ?: [];
    if ( empty($rows) || ! is_array($rows) ) {
        return;
    }

    // ۳. اعمال دلتا روی product_price
    $modified = false;
foreach ( $rows as &$row ) {
    if ( isset($row['product_price']) && is_numeric($row['product_price']) ) {
        $old = floatval( $row['product_price'] );
        $new = $old + $delta;
        // ذخیرهٔ عدد خالص جدید
        $row['product_price'] = number_format( $new, 0, '.', '' );
        $modified = true;
    }
}
unset($row);


    // ۴. ذخیرهٔ ردیف‌ها
    if ( $modified ) {
        update_field('product_rows', $rows, $post_id);
    }
}



/**
 * به‌روزرسانی قیمت‌های یک محصول با دلتا سفارشی
 *
 * @param int $post_id شناسهٔ پست محصول
 * @param int $delta   مقدار دلتا (مثبت یا منفی)
 * @return bool        true اگر تغییرات ذخیره شد
 */
function manual_update_custom_delta( $post_id, $delta ) {
    // واکشی ردیف‌های ACF
    $rows = get_field('product_rows', $post_id) ?: [];
    if ( empty($rows) || ! is_array($rows) ) {
        return false;
    }

    $modified = false;
    foreach ( $rows as &$row ) {
        if ( isset($row['product_price']) && is_numeric($row['product_price']) ) {
            // اعمال دلتا
            $new_price = floatval($row['product_price']) + intval($delta);
            $row['product_price'] = number_format( $new_price, 0, '.', '' );
            $modified = true;
        }
    }
    unset($row);

    if ( $modified ) {
        update_field( 'product_rows', $rows, $post_id );
    }

    return $modified;
}



function render_update_products_page(){
    if(!current_user_can('manage_options')) return;
    if(isset($_POST['upd_all']) && check_admin_referer('upd_all_action','upd_all_nonce')){
        // فقط پست‌هایی که فیلد product_rows دارند
        $products = get_posts([
            'post_type'      => 'product',
            'meta_key'       => 'product_rows',
            'posts_per_page' => -1
        ]);
        foreach($products as $p){
            manual_update_product_prices($p->ID);
        }
        echo '<div class="updated"><p>تمام قیمت‌ها به روز شد.</p></div>';
    }
    ?>
    <div class="wrap"><h1>بروز قیمت محصولات</h1>
      <form method="post">
        <?php wp_nonce_field('upd_all_action','upd_all_nonce'); ?>
        <button name="upd_all" class="button button-primary">بروز همه محصولات</button>
      </form>
    </div>
    <?php
}

/**
 * صفحه‌ی ادمین: تغییر قیمت سفارشی یک محصول
 */
function render_custom_delta_page() {
    if ( ! current_user_can('manage_options') ) {
        return;
    }

    // ۱. پردازش فرم ارسال‌شده
    if ( isset($_POST['custom_delta_submit'])
         && check_admin_referer('custom_delta_action','custom_delta_nonce') ) {

        $post_id = absint( $_POST['product_id'] );
        $delta   = intval( $_POST['custom_delta'] );

        if ( $post_id > 0 && $delta !== 0 ) {
            $success = manual_update_custom_delta( $post_id, $delta );
            if ( $success ) {
                echo '<div class="notice notice-success"><p>قیمت‌های محصول با ID ' 
                     . esc_html($post_id) . ' با دلتا ' 
                     . esc_html($delta) . ' به‌روزرسانی شد.</p></div>';
            } else {
                echo '<div class="notice notice-warning"><p>به‌روزرسانی انجام نشد؛ احتمالاً فیلد قیمت ندارد.</p></div>';
            }
        } else {
            echo '<div class="notice notice-error"><p>لطفاً محصول و دلتا را به‌درستی انتخاب کنید.</p></div>';
        }
    }

    // ۲. واکشی محصولات دارای فیلد product_rows
    $products = get_posts([
        'post_type'      => 'product',
        'meta_key'       => 'product_rows',
        'posts_per_page' => -1,
        'orderby'         => 'title',
        'order'           => 'ASC',
    ]);

    // ۳. فرم HTML
    ?>
    <div class="wrap">
      <h1>تغییر قیمت سفارشی</h1>
      <form method="post">
        <?php wp_nonce_field('custom_delta_action','custom_delta_nonce'); ?>

        <table class="form-table">
          <tr>
            <th scope="row"><label for="product_id">انتخاب محصول</label></th>
            <td>
              <select name="product_id" id="product_id">
                <option value="0">— انتخاب کنید —</option>
                <?php foreach ( $products as $p ): ?>
                  <option value="<?php echo $p->ID; ?>"
                    <?php selected( isset($_POST['product_id']) ? $_POST['product_id'] : 0, $p->ID ); ?>>
                      <?php echo esc_html( get_the_title($p) ); ?> (ID: <?php echo $p->ID; ?>)
                  </option>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
          <tr>
            <th scope="row"><label for="custom_delta">دلتا (مثبت/منفی)</label></th>
            <td>
              <input name="custom_delta" type="number" id="custom_delta"
                     value="<?php echo esc_attr( isset($_POST['custom_delta']) ? $_POST['custom_delta'] : '' ); ?>"
                     step="1" />
            </td>
          </tr>
        </table>

        <p class="submit">
          <button type="submit" name="custom_delta_submit" class="button button-primary">
            اعمال دلتا سفارشی
          </button>
        </p>
      </form>
    </div>
    <?php
}
function render_export_excel_page() {
    if (!current_user_can('manage_options')) return;

    if (isset($_POST['export_excel_nonce']) && check_admin_referer('export_excel_action', 'export_excel_nonce')) {
        // ساخت فایل اکسل و ذخیره روی سرور
        $file_url = create_and_save_excel_file();
        if ($file_url) {
            echo '<div class="notice notice-success"><p>فایل اکسل ساخته شد. <a href="' . esc_url($file_url) . '" target="_blank">دانلود فایل</a></p></div>';
        } else {
            echo '<div class="notice notice-error"><p>خطا در ساخت فایل اکسل رخ داد.</p></div>';
        }
    }

    ?>
    <div class="wrap">
        <h1>اکسپورت قیمت‌ها به اکسل</h1>
        <form method="post">
            <?php wp_nonce_field('export_excel_action', 'export_excel_nonce'); ?>
            <p><button type="submit" class="button button-primary">ساخت و دانلود فایل اکسل</button></p>
        </form>
    </div>
    <?php
}

function create_and_save_excel_file() {
    if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
        error_log('[Excel Export] PhpSpreadsheet class not found.');
        return false;
    }

    error_log('[Excel Export] PhpSpreadsheet class loaded successfully.');

    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

    // حذف Sheet پیش‌فرض
    $spreadsheet->removeSheetByIndex(0);
    error_log('[Excel Export] Default sheet removed.');

    // تعریف فیلدها
    $fields = [
        'product_code'          => 'کد',
        'product_name'          => 'نام',
        'product_price'         => 'قیمت',
        'product_trim'          => 'حالت',
        'measurement_unit'      => 'واحد',
        'product_thickness'     => 'ضخامت',
        'product_size'          => 'سایز',
        'product_weight'        => 'وزن',
        'product_grade'         => 'استاندارد آلیاژ',
        'product_manufacturer'  => 'کارخانه',
        'loading_location'      => 'محل بارگیری',
        'manufacture_country'   => 'کشور سازنده',
        'additional_info_1'     => 'سایر مشخصات ۱',
        'additional_info_2'     => 'سایر مشخصات ۲',
        'additional_info_3'     => 'سایر مشخصات ۳',
        'additional_info_4'     => 'سایر مشخصات ۴',
        'additional_info_5'     => 'سایر مشخصات ۵',
    ];

    error_log('[Excel Export] Fields defined successfully.');

    // دریافت محصولات
    $products = get_posts([
        'post_type' => 'product',
        'meta_key' => 'product_rows',
        'posts_per_page' => -1,
    ]);

    if (empty($products)) {
        error_log('[Excel Export] No products found.');
        return false;
    }

    error_log('[Excel Export] ' . count($products) . ' products found.');

    foreach ($products as $product) {
        $title = get_the_title($product->ID);
        $rows = get_field('product_rows', $product->ID);

        if (!is_array($rows) || empty($rows)) {
            error_log("[Excel Export] Skipping product '$title' (ID: {$product->ID}) - no rows found.");
            continue;
        }

        error_log("[Excel Export] Processing product '$title' with " . count($rows) . " rows.");

        // محدودیت نام شیت 31 کاراکتر
        $sheet_title = mb_substr($title, 0, 31);

        $sheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $sheet_title);
        $spreadsheet->addSheet($sheet);
        error_log("[Excel Export] Sheet '$sheet_title' created.");

        // نوشتن عنوان ستون‌ها
$col = 1;
foreach ($fields as $field_label) {
    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
    $sheet->setCellValue($columnLetter . '1', $field_label);
    $col++;
}

        // نوشتن داده‌های ردیف‌ها
$row_num = 2;
foreach ($rows as $row_data) {
    $col = 1;
    foreach ($fields as $field_key => $field_label) {
        $value = isset($row_data[$field_key]) ? $row_data[$field_key] : '';
        $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($col);
        $sheet->setCellValue($columnLetter . $row_num, $value);
        $col++;
    }
    $row_num++;
}

        error_log("[Excel Export] Data written for product '$title'.");
    }

    // مسیر ذخیره فایل
    $upload_dir = wp_upload_dir();
    $export_dir = $upload_dir['basedir'] . '/exports';
    if (!file_exists($export_dir)) {
        wp_mkdir_p($export_dir);
        error_log("[Excel Export] Created directory: $export_dir");
    }

    $file_name = 'product_details_' . date('Y-m-d_H-i-s') . '.xlsx';
    $file_path = $export_dir . '/' . $file_name;

    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    try {
        $writer->save($file_path);
        error_log("[Excel Export] File saved to: $file_path");
    } catch (Exception $e) {
        error_log('[Excel Export] Error saving file: ' . $e->getMessage());
        return false;
    }

    return $upload_dir['baseurl'] . '/exports/' . $file_name;
}






