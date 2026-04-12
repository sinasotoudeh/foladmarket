<?php
/*
Plugin Name: Sina ACF Horizontal Gallery
Description: نمایش گالری افقی تک‌ردیفه از فیلد ACF Product_gallery در محصولات + فلش + شمارنده.
Version: 1.3
Author: Sina Sotoudeh
*/

function sina_acf_gallery_shortcode() {

    $post_id = get_the_ID();
    if (!$post_id) return '';

    $images = get_field('product_gallery', $post_id);
    if (empty($images)) return '';

    $total = count($images);

    ob_start();
    ?>

    <div class="sina-acf-hg-wrapper">

        <!-- شمارنده -->
        <div class="sina-acf-hg-counter">
            <span class="current">1</span> / <?php echo $total; ?>
        </div>

        <!-- فلش‌ها -->
        <div class="sina-acf-hg-arrow left" data-dir="right">&#10095;</div>
        <div class="sina-acf-hg-arrow right" data-dir="left">&#10094;</div>

        <!-- خود گالری -->
        <div class="sina-acf-horizontal-gallery">
            <?php foreach ($images as $image): ?>
                <div class="sina-acf-hg-item">
                    <img src="<?php echo esc_url($image['sizes']['medium_large']); ?>" alt="">
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <?php
    return ob_get_clean();
}
add_shortcode('sina_acf_gallery', 'sina_acf_gallery_shortcode');



/* -----------------------  CSS  ------------------------ */
function sina_acf_horizontal_gallery_css() {
?>
<style>
.sina-acf-hg-wrapper {
    position: relative;
    margin: 10px 0 20px 0;
}

/* شمارنده */
.sina-acf-hg-counter {
    position: absolute;
    top: -22px;
    left: 0;
    font-size: 13px;
    color: #444;
    font-weight: 600;
}

/* گالری */
.sina-acf-horizontal-gallery {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    gap: 10px;
    padding-bottom: 5px;
    scroll-snap-type: x mandatory;
    -webkit-overflow-scrolling: touch;
}
.sina-acf-horizontal-gallery::-webkit-scrollbar { display: none; }

.sina-acf-hg-item { flex: 0 0 auto; scroll-snap-align: center; }
.sina-acf-hg-item img {
    height: 65px;
    width: auto;
    border-radius: 6px;
    object-fit: cover;
}

/* فلش‌ها */
.sina-acf-hg-arrow {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 22px;
    padding: 5px 8px;
    background: rgba(0,0,0,0.3);
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
    user-select: none;
    z-index: 10;
    transition: 0.2s;
}
.sina-acf-hg-arrow:hover {
    background: rgba(0,0,0,0.5);
}

.sina-acf-hg-arrow.left { left: -8px; }
.sina-acf-hg-arrow.right { right: -8px; }

.sina-acf-hg-item.active img {
    border: 3px solid #1976d2; /* رنگ دلخواه */
    border-radius: 6px;
}


</style>
<?php
}
add_action('wp_head', 'sina_acf_horizontal_gallery_css');



/* -----------------------  JS  ------------------------ */
function sina_acf_horizontal_gallery_js() {
?>
<script>
document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".sina-acf-hg-wrapper").forEach(function(wrapper) {

        const gallery = wrapper.querySelector(".sina-acf-horizontal-gallery");
        const items = wrapper.querySelectorAll(".sina-acf-hg-item");
        const imgs = wrapper.querySelectorAll(".sina-acf-hg-item img");

        const counter = wrapper.querySelector(".sina-acf-hg-counter .current");

        const leftArrow = wrapper.querySelector(".sina-acf-hg-arrow.left");
        const rightArrow = wrapper.querySelector(".sina-acf-hg-arrow.right");

        const mainImage = document.querySelector(".elementor-widget-theme-post-featured-image img");

        let index = 0;


        /* ----------------------------------------
           🔵 تابع: ست کردن ایندکس فعال + استایل + عکس اصلی
        ---------------------------------------- */
        function activateIndex(i, scroll = true) {
            if (i < 0) i = 0;
            if (i >= items.length) i = items.length - 1;

            index = i;

            items.forEach(item => item.classList.remove("active"));
            items[i].classList.add("active");

            counter.textContent = i + 1;

            const img = items[i].querySelector("img");
            if (mainImage && img) {
                mainImage.src = img.src;
                mainImage.removeAttribute("srcset");
                mainImage.removeAttribute("sizes");
            }

            if (scroll) {
                gallery.scrollTo({
                    left: items[i].offsetLeft - 20,
                    behavior: "smooth"
                });
            }
        }


        /* ----------------------------------------
           🔵 فلش‌ها
        ---------------------------------------- */
        rightArrow.addEventListener("click", () => activateIndex(index - 1));
        leftArrow.addEventListener("click", () => activateIndex(index + 1));


        /* ----------------------------------------
           🔵 کلیک روی هر عکس
        ---------------------------------------- */
        imgs.forEach((imgSmall, i) => {
            imgSmall.style.cursor = "pointer";

            imgSmall.addEventListener("click", () => {
                activateIndex(i);
            });
        });


        /* ----------------------------------------
           🔵 هنگام اسکرول دستی → پیدا کردن عکس فعال
        ---------------------------------------- */
        gallery.addEventListener("scroll", () => {
            let closestIndex = 0;
            let smallestDistance = Infinity;

            items.forEach((item, i) => {
                const distance = Math.abs(item.offsetLeft - gallery.scrollLeft);
                if (distance < smallestDistance) {
                    smallestDistance = distance;
                    closestIndex = i;
                }
            });

            activateIndex(closestIndex, false);
        });


        /* ----------------------------------------
           🔵 فعال کردن اولین آیتم هنگام لود
        ---------------------------------------- */
        activateIndex(0, false);

    });

});
</script>
<?php
}
add_action('wp_footer', 'sina_acf_horizontal_gallery_js');


