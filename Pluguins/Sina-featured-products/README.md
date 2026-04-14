# 🌟 Sina Featured Products

![WordPress](https://img.shields.io/badge/WordPress-Plugin-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php)
![JavaScript](https://img.shields.io/badge/JS-Vanilla-F7DF1E?logo=javascript)
![CSS3](https://img.shields.io/badge/CSS3-Responsive-1572B6?logo=css3)
![Status](https://img.shields.io/badge/Status-Active-success)

**Sina Featured Products** is a highly optimized WordPress plugin designed to display product price tables within an auto-rotating slider. It efficiently integrates nested shortcodes and ensures high performance by loading assets conditionally.

---

## ✨ Key Features

- 🚀 **Smart Asset Loading:** CSS and JS files are strictly loaded only when the shortcode is executed.
- 🔄 **Interactive Slider:** Vanilla JavaScript slider with auto-rotation, pagination, and intuitive pause-on-hover functionality.
- 📐 **Layout Stability:** Fixed heights and strict CSS constraints to prevent Cumulative Layout Shift (CLS).
- 🧩 **Nested Shortcode Execution:** Dynamically executes `[product_price_table]` within each slide.
- 📱 **Responsive & Sticky UI:** Features sticky table headers and responsive hiding for optimal mobile viewing.

---

## 🛠️ User-Centric: Installation & Usage

### 📦 Installation

1. Upload the plugin folder to your `/wp-content/plugins/` directory.
2. Go to the **Plugins** page in your WordPress dashboard.
3. Activate the **Sina Featured Products** plugin.

### 💻 Usage

To render the product tables slider, insert the following shortcode wherever you need it:

```text
[fm_products_tables ids="12,34,56" posts="10" rotation="3000"]
```

### 📋 Shortcode Parameters

| Parameter  | Default Value | Description                                                                              |
| :--------- | :------------ | :--------------------------------------------------------------------------------------- |
| `ids`      | `''` (Empty)  | Comma-separated list of specific product IDs to display. If provided, overrides `posts`. |
| `posts`    | `10`          | The maximum number of latest products to retrieve if `ids` is empty.                     |
| `rotation` | `3000`        | Delay between auto-rotations in milliseconds (e.g., $3000$ = $3$ seconds).               |

---

## ⚙️ Developer-Centric: Architecture & Code Quality

This section outlines the architectural decisions and optimizations for technical reviewers.

### 1. 🏗️ Performance & Asset Management

The plugin utilizes `wp_register_style` and `wp_register_script` on the `init` hook, but strictly delays enqueuing (`wp_enqueue_style`) until the `fm_products_tables_shortcode` function is invoked. This ensures zero payload footprint on pages lacking the shortcode.

### 2. 🛡️ Security & Data Sanitization

- **Input Handling:** User inputs from shortcode attributes are rigorously sanitized using `absint()` and `intval()`. Array filtering is applied: `array_filter(array_map('absint', array_map('trim', explode(',', $atts['ids']))))`.
- **Output Escaping:** DOM injection points are secured using `esc_attr()` for IDs and classes, and `esc_url()` for permalinks.

### 3. ⏱️ Mathematical Timing & JS Architecture

The JavaScript architecture relies on a Vanilla JS closure to prevent global scope pollution. It uses `setInterval` for rotation based on the `data-rotation` attribute.
For example, if `rotation="3000"` and there are `$10$` posts, a complete visual cycle takes:
`$$ 3000 \times 10 = 30000 $$` milliseconds ($30$ seconds).
The rotation logic is intelligently tied to DOM events (`mouseenter` to `clearInterval`, and `mouseleave` to restart), providing superior UX. The `initAll` function ensures multi-instance support via localized `initInstance(container)`.

### 4. 🎨 UI/UX & CSS Architecture

- **CLS Prevention:** The `.fm-slider-wrapper` has a `min-height: 500px;` applied to ensure the DOM does not jump while slider initialization occurs.
- **Modern CSS:** Utilizes CSS properties like `position: sticky` for table headers and `overflow-y: auto` for constrained internal scrolling, paired with linear gradients and transition effects for price display columns.

---

## 👨‍💻 Author Info

- **Name:** Sina Sotoudeh
- **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- **Email:** s.sotoudeh1@gmail.com
