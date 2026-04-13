<?php 
/*
Plugin Name: Sina Cost Calculator
Description: Adds a product price calculator via shortcode.
Version: 1.0
Author: Sina Sotoudeh
*/

// محاسبه گر صفحه ی محصولات
// ──────────────────────────────
// New function: Retrieve the Cost-Per-Cm lookup table
// ──────────────────────────────
function get_cost_lookup_table() {
    return [
        'steel' => [
            'roundbar' => [
                ['min' => 0,     'max' => 350,    'cost' => 600],
                ['min' => 350.0001, 'max' => 10000, 'cost' => 1000],
            ],
            'rectangular' => [
                ['min' => 0,     'max' => 700,    'cost' => 600],
                ['min' => 700.0001, 'max' => 10000, 'cost' => 1000],
            ],
        ],
        'iron' => [
            'roundbar' => [
                ['min' => 0,     'max' => 350,    'cost' => 400],
                ['min' => 350.0001, 'max' => 500,    'cost' => 500],
                ['min' => 500.0001, 'max' => 700,    'cost' => 800],
                ['min' => 700.0001, 'max' => 10000,  'cost' => 1200],
            ],
            'rectangular' => [
                ['min' => 0,     'max' => 700,    'cost' => 500],
                ['min' => 700.0001, 'max' => 10000,  'cost' => 700],
            ],
        ],
    ];
}

// ──────────────────────────────
// Enqueue Calculator Scripts and Pass Data
// ──────────────────────────────
function my_calculator_enqueue_scripts() {
    if ( is_singular(['product']) ) {
        // CSS
        wp_enqueue_style(
            'calculator-style',
            plugin_dir_url(__FILE__) . 'assets/css/calculator.css'
        );

        // JS with version based on file modification time
        $js_path = plugin_dir_path(__FILE__) . 'assets/js/calculator.js';
        wp_enqueue_script(
            'calculator-script',
            plugin_dir_url(__FILE__) . 'assets/js/calculator.js',
            [],
            filemtime($js_path),
            true
        );

        // Pass PHP data into JS
        $calc_data = array(
            'density'   => get_field('density') ? get_field('density') : 7840,
            'material'  => strtolower(get_field('material')), // expects Steel, Iron, or Metal
            'costTable' => get_cost_lookup_table(),
        );
        wp_localize_script('calculator-script', 'calcData', $calc_data);
    }
}
add_action('wp_enqueue_scripts', 'my_calculator_enqueue_scripts');



// ──────────────────────────────
// Calculator Shortcode and Title Shortcode (remain unchanged)
// ──────────────────────────────
function my_calculator_shortcode() {
    
    $density = get_field('density') ? get_field('density') : 7840;
    $title   = get_the_title();
  ob_start();
    ?>
<div class="product-weight-calculator" data-density="<?php echo esc_attr($density); ?>" data-title="<?php echo esc_attr('محاسبه وزن و قیمت ' . $title); ?>">
                <!--title -->
                    <div class="calculator-title">
<h4>
 <?php  echo esc_attr('محاسبه وزن و قیمت ' . $title); ?>
</h4>
                </div>
            <!-- Row for Shape Selection -->
            <div class="row shape-selection">
                <div class="shape-frame" id="roundBarFrame" onclick="selectShape('roundBar')">
                    <span class="icon">⚫</span>
                    <h4>میلگرد</h4>
                </div>
                <div class="shape-frame" id="rectangularBarFrame" onclick="selectShape('rectangularBar')">
                    <span class="icon">🔲</span>
                    <h4>تسمه</h4>
                </div>
            </div>
            <!-- Dynamic Input Rows -->
            <div id="dynamicInputs"></div>
            <!-- Input for Quantity and Price -->
            <div class="row two-column-row">
                <div class="input-group" title="تعداد محصولات مورد نیاز را وارد کنید">
                    <label for="quantity">
                        <span class="icon">#️⃣</span>
                        تعداد:
                    </label>
                    <input type="number" id="quantity" value="1" oninput="calculateWeightAndPrice()">
                </div>
                <div class="input-group" title="قیمت به تومان برای هر کیلو">
                    <label for="pricePerKg">
                        <span class="icon">💰</span>
                        قیمت هر کیلو (تومان):
                    </label>
                    <input type="number" id="pricePerKg" placeholder="قیمت به تومان" oninput="calculateWeightAndPrice()">
                </div>
            </div>
            <!-- Result Row -->
            <div class="result-frame fade-in">
                <div class="result-column weight-result" title="وزن محاسبه شده بر حسب کیلوگرم">
                    <p id="totalWeight">وزن کل: </p>
                    <p id="singleWeight">هر عدد: </p>
                </div>
                <div class="result-column price-result" title="قیمت محاسبه شده بر حسب تومان">
                    <p id="totalPrice">قیمت کل: </p>
                    <p id="singlePrice">هر عدد: </p>
                </div>
                <div class="result-column cut-price-result" title="هزینه برش محاسبه شده">
                    <p id="totalCutPrice">هزینه برش کل: </p>
                    <p id="singleCutPrice">هر عدد: </p>

                </div>
            </div>
        </div>
    <?php
    return ob_get_clean();
}
add_shortcode('my_calculator', 'my_calculator_shortcode');
?>