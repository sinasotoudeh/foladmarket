# 📊 Sina Product Price Table Renderer

![WordPress](https://img.shields.io/badge/WordPress-Plugin-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php)
![ACF](https://img.shields.io/badge/ACF-Integration-46B450?logo=wordpress)
![SEO](https://img.shields.io/badge/SEO-JSON--LD-success?logo=googlesearchconsole)
![Status](https://img.shields.io/badge/Status-Active-success)

**Sina Product Price Table Renderer** is a highly optimized, frontend-focused WordPress plugin designed to dynamically render complex product data and pricing tables for non-WooCommerce setups. It intelligently combines Advanced Custom Fields (ACF) data, real-time currency fluctuation logic, and advanced SEO schema markup into a responsive, user-friendly data table.

---

## ✨ Key Features

- 🧩 **Dynamic Data Population:** Automatically filters and renders only the columns that contain data, preventing blank spaces in the UI.
- 📈 **Currency-Aware UI:** Visually indicates price trends (up, down, stable) based on daily USD rates using color-coded text.
- 🛒 **Decoupled Cart Operations:** Injects robust `data-*` attributes into action buttons for seamless, decoupled JavaScript interactions.
- 📱 **Responsive UX:** Features a sticky table header, mobile-hidden columns, and a custom CSS scrollbar for large datasets.
- 🔍 **Advanced Technical SEO:** Automatically injects comprehensive `application/ld+json` Schema markup (Product, Offer, AggregateRating) into the DOM.

---

## 🛠️ User-Centric: Installation & Usage

### 📦 Installation

1. Upload the `Sina-product-price-table-renderer` folder to your `/wp-content/plugins/` directory.
2. Ensure you have the **Advanced Custom Fields (ACF)** plugin installed and configured with the required repeater fields (`product_rows`).
3. Activate the plugin via the WordPress **Plugins** screen.

### 💻 Shortcodes & Implementation

Use the following shortcodes within your Elementor builder, Gutenberg blocks, or classic editor to render the tables.

| Shortcode               | Default Context | Description                                                                                                          |
| :---------------------- | :-------------- | :------------------------------------------------------------------------------------------------------------------- |
| `[product_price_table]` | Current Post ID | Renders the complete, scrollable pricing table with all technical specifications, action buttons, and schema markup. |
| `[product_first_price]` | Current Post ID | Extracts and displays _only_ the price from the first row of the ACF repeater. Useful for hero sections.             |

**Example Usage:**

```text
[product_price_table]
```

---

## ⚙️ Developer-Centric: Architecture & Code Quality

This section outlines the plugin's underlying architecture, performance optimizations, and mathematical logic for technical reviewers and system maintainers.

### 1. 🏗️ UI/UX & Asset Management

- **Targeted Enqueuing:** CSS assets are conditionally loaded using `is_singular('product')` to prevent stylesheet bloat on non-product pages. Cache-busting is intelligently handled via `filemtime()`.
- **Ergonomic Data Display:** The CSS implements a `max-height: 430px` wrapper with `overflow-y: auto` alongside `position: sticky` headers. This creates a viewport-friendly table that displays roughly $10$ rows at a time, ensuring a compact DOM footprint visually.
- **Data Attribute Architecture:** Instead of rendering inline JS, action buttons utilize HTML5 data attributes (e.g., `data-product-thickness`, `data-product-price`). This allows frontend JavaScript to easily parse product data without traversing complex DOM trees.

### 2. 🔄 Currency Logic & Mathematics

The plugin evaluates market volatility by comparing today's USD rate ($T$) with yesterday's rate ($Y$). It defines a fixed evaluation constant ($C$) where $C = 7500$.

The delta logic ($\Delta$) for UI color-coding is processed as follows:

- If $T > Y$, then $\Delta = +7500 \rightarrow$ Color **Red** (Price increased)
- If $T < Y$, then $\Delta = -7500 \rightarrow$ Color **Green** (Price decreased)
- If $T = Y$, then $\Delta = 0 \rightarrow$ Color **Blue** (Price stable)

If a product is marked with the `unstable_price` boolean flag, the mathematical evaluation is bypassed, and a routing fallback (`/تماس-با-ما/`) is rendered instead.

### 3. 🔍 SEO & JSON-LD Generation

The plugin abandons outdated Microdata in favor of modern `application/ld+json`.

- **Dynamic Offers:** Calculates valid pricing dates dynamically (`strtotime('+1 week')`).
- **Aggregate Rating:** Directly queries the custom database table `$wpdb->prefix}product_ratings` to calculate accurate average ratings and review counts for the Schema block.

### 4. 🛡️ Security & Data Integrity

- **Prepared Statements:** All custom database queries utilize `$wpdb->prepare` with strict type casting (`%d`) to neutralize SQL injection vectors.
- **Output Escaping:** Late escaping is strictly enforced. Functions like `esc_html()`, `esc_attr()`, and `esc_url()` wrap every dynamic output, and classes are sanitized via `sanitize_html_class()`.
- **JSON Encoding:** The Schema markup is secured using `wp_json_encode()` with `JSON_UNESCAPED_UNICODE` to ensure safe, valid rendering of Persian characters without XSS vulnerabilities.

---

## 👨‍💻 Author Info

- **Name:** Sina Sotoudeh
- **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- **Email:** s.sotoudeh1@gmail.com
