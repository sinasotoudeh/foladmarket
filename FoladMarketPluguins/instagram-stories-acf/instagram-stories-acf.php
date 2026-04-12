<?php
/**
 * Plugin Name: Instagram Stories for ACF
 * Description: نمایش تصاویر ACF به صورت استوری اینستاگرام با شورت‌کد
 * Version: 1.2.0
 * Author: Sina Sotoudeh
 */

if (!defined('ABSPATH')) exit;

define('ISA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('ISA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('ISA_VERSION', '1.2.0');

class Instagram_Stories_ACF {

    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) self::$instance = new self();
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
        add_shortcode('acf_stories', array($this, 'stories_shortcode'));
    }

    public function enqueue_assets() {
        wp_enqueue_style(
            'isa-stories-style',
            ISA_PLUGIN_URL . 'assets/css/stories.css',
            array(),
            ISA_VERSION
        );

        wp_enqueue_script(
            'isa-stories-script',
            ISA_PLUGIN_URL . 'assets/js/stories.js',
            array('jquery'),
            ISA_VERSION,
            true
        );
    }

    public function stories_shortcode($atts) {

        $atts = shortcode_atts(array(
            'field' => 'product_stories',
            'duration' => 2500,
            'autoplay' => 'true',
            'show_controls' => 'true',
            'loop' => 'true',
            'width' => '500px'
        ), $atts);

        $post_id = get_the_ID();
        if (!$post_id) return '<p>پست یافت نشد.</p>';

        $raw = get_field($atts['field'], $post_id);

        if (!$raw || !is_array($raw)) {
            return '';
        }

        $stories = [];

        foreach ($raw as $item) {

            // 1) اگر ACF آرایه کامل تصویر باشد (ساختار 24 کلیدی)
            if (is_array($item) && isset($item['url'])) {
                $stories[] = [
                    'url' => $item['url'],
                    'alt' => $item['alt'] ?? ''
                ];
                continue;
            }

            // 2) اگر فقط ID ذخیره شده باشد
            $id = intval($item);
            if ($id > 0) {
                $img = wp_get_attachment_image_src($id, 'full');
                if ($img) {
                    $alt = get_post_meta($id, '_wp_attachment_image_alt', true);
                    $stories[] = [
                        'url' => $img[0],
                        'alt' => $alt
                    ];
                }
            }
        }

        if (empty($stories)) return '';

        ob_start();
        $unique_id = 'isa-' . uniqid();
        ?>

        <div class="instagram-stories-container"
             id="<?php echo esc_attr($unique_id); ?>"
             data-duration="<?php echo esc_attr($atts['duration']); ?>"
             data-autoplay="<?php echo esc_attr($atts['autoplay']); ?>"
             data-loop="<?php echo esc_attr($atts['loop']); ?>"
             style="max-width: <?php echo esc_attr($atts['width']); ?>;">
         <div class="isa-story-topbox">
           فهرست موجودی تسمه و گرد <?php echo esc_html( get_the_title($post_id) ); ?>
         </div>
            <div class="stories-wrapper">

                <div class="stories-progress">
                    <?php foreach ($stories as $i => $story): ?>
                        <div class="progress-bar">
                            <div class="progress-fill" data-index="<?php echo esc_attr($i); ?>"></div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="stories-content">
                    <?php foreach ($stories as $i => $story): ?>
                        <div class="story-slide <?php echo $i === 0 ? 'active' : ''; ?>" data-index="<?php echo esc_attr($i); ?>">
                            <img src="<?php echo esc_url($story['url']); ?>"
                                 alt="<?php echo esc_attr($story['alt']); ?>"
                                 loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($atts['show_controls'] === 'true'): ?>
                    <button class="story-nav story-prev">&#10094;</button>
                    <button class="story-nav story-next">&#10095;</button>
                    <button class="story-zoom-button" onclick="isaOpenCurrentStory('<?php echo esc_attr($unique_id); ?>')">🔍</button>
                <?php endif; ?>

                <div class="story-counter">
                    <span class="current-story">1</span> /
                    <span class="total-stories"><?php echo count($stories); ?></span>
                </div>

            </div>
        </div>

        <?php
        return ob_get_clean();
    }
}

function instagram_stories_acf_init() {
    return Instagram_Stories_ACF::get_instance();
}
add_action('plugins_loaded', 'instagram_stories_acf_init');
