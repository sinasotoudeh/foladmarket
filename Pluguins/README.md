# 🚀 Foladmarket Custom Plugins Repository

![WordPress](https://img.shields.io/badge/WordPress-Ready-21759b.svg?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4%20%7C%208.0%2B-777bb4.svg?logo=php)
![JavaScript](https://img.shields.io/badge/Vanilla_JS-ES6-F7DF1E.svg?logo=javascript)
![ACF](https://img.shields.io/badge/ACF_Pro-Integrated-00E676.svg)
![Architecture](https://img.shields.io/badge/Architecture-Singleton%20%7C%20OOP-informational)

Welcome to the **Foladmarket Custom Plugins** repository. This workspace contains a collection of highly optimized, bespoke WordPress plugins engineered specifically for industrial, metallurgical, and B2B e-commerce platforms.

These plugins are built from the ground up to handle complex mathematical computations, dynamic pricing based on currency fluctuations, real-time advanced UI/UX interactions, and seamless integrations with Advanced Custom Fields (ACF) and WooCommerce.

---

## 📂 Repository Navigation (Important)

**Detailed Documentation:** This document serves as a high-level overview of the entire repository. **Each plugin contains its own dedicated, highly detailed `README.md` file within its respective folder.**

If you are a developer looking for installation instructions, shortcode parameters, mathematical models, or architectural deep-dives, please navigate to the specific plugin's folder.

---

## 🛠️ Plugins Directory & Overview

Below is the index of the core plugins available in this repository:

| Plugin Name                                                                  | Primary Function                                                    | Highlight / Tech Stack                                                     |
| :--------------------------------------------------------------------------- | :------------------------------------------------------------------ | :------------------------------------------------------------------------- |
| 🧮 **[Sina Grand Calculator](./Pluguins/Sina-grand-calculator/)**            | Advanced multi-stage weight & price calculator for metals/polymers. | Real-time WooCommerce sync, Dynamic UI, Complex Math Models.               |
| 🛒 **[Sina Custom Cart](./Pluguins/sina-custom-cart/)**                      | B2B Session-Based Cart & Checkout system.                           | Non-WooCommerce, Secure Cookies, Custom DB Tables, $O(1)$ Queries.         |
| 🌟 **[Product Price Manager](./Pluguins/sina-product-price-manager/)**       | Automated bulk price updater based on daily USD/IRR rates.          | REST API, Algorithmic Deltas, PhpSpreadsheet Excel Exports.                |
| 📊 **[Steel Comparison](./Pluguins/Sina-steel-comparison/)**                 | Dynamic comparison table for steel grades and physical properties.  | Decoupled JSON data, Regex Formatting, High-Performance jQuery.            |
| ⚙️ **[Cost Calculator](./Pluguins/Sina-cost-calculator/)**                   | Real-time weight and cutting-cost calculator for industrial metals. | Vanilla JS, Matrix Data Mapping, Smart Mobile DOM Repositioning.           |
| 📱 **[Instagram Stories ACF](./Pluguins/instagram-stories-acf/)**            | Renders ACF galleries as interactive Instagram-style stories.       | Touch/Swipe Optimized, Lazy Loading, Hardware Acceleration.                |
| 🖼️ **[Horizontal Gallery](./Pluguins/sina-acf-horizontal-gallery/)**         | Smooth horizontal gallery synced with Elementor featured images.    | Vanilla JS Math Routing, CSS Scroll-Snap, Zero Dependencies.               |
| 🚀 **[Custom Menu](./Pluguins/Sina-custom-menu/)**                           | High-performance hierarchical category/product menu generator.      | Hybrid Rendering (PHP + JS), Lazy DOM Injection, Transient Caching.        |
| 📞 **[Contact-Us Box](./Pluguins/Sina-contact-us-box/)**                     | Highly converting, responsive contact widget for sales experts.     | Shortcode API, CSS Grid/Flexbox, Dynamic Asset Loading.                    |
| 🏷️ **[Category-Tag Filter](./Pluguins/Sina-category-tag-filter/)**           | Dynamic URL-based tag filtering for Elementor product archives.     | Elementor Query Hooks, Tag Mapping Dictionary, $posts\_per\_page = -1$.    |
| 🌟 **[Featured Posts](./Pluguins/Sina-featured-posts/)**                     | Auto-rotating, performant slider for featured blog articles.        | Hybrid SSR Rendering, Isolated JS Closures, Flexbox UI.                    |
| 🌟 **[Featured Products](./Pluguins/Sina-featured-products/)**               | Auto-rotating slider specifically for product pricing tables.       | Nested Shortcodes, Layout Stability (CLS Prevention), Interactive UI.      |
| 📊 **[Price Table Renderer](./Pluguins/Sina-product-price-table-renderer/)** | Frontend dynamic data and pricing tables mapped from ACF.           | Schema `JSON-LD`, Currency-Aware UI ($\Delta$ shifts), Decoupled JS Data.  |
| ⭐ **[Product Rating](./Pluguins/Sina-product-rating/)**                     | Custom 5-star product rating system via REST API.                   | Custom Indexed DB, REST API Endpoints, Vanilla JS Frontend.                |
| 📂 **[Subcategory Dropdown](./Pluguins/Sina-subcategory-dropdown/)**         | Context-aware category dropdown with total post aggregations.       | Tree Math Aggregation ($\sum Count$), a11y Accessibility, Context Loading. |

---

## 🏗️ Core Engineering Philosophy (For Reviewers & Employers)

All plugins in this repository strictly adhere to enterprise-level WordPress development standards. They are designed with a primary focus on **Performance**, **Security**, and **Scalability**.

### 1. Architecture & Design Patterns

- **Singleton Pattern:** Core logic relies on the Singleton pattern with private constructors to prevent memory leaks, accidental duplications, and global namespace pollution.
- **Modularity & SoC:** Strict Separation of Concerns (SoC). Database queries, AJAX handlers, and UI rendering are decoupled.
- **Complex Mathematical Modeling:** Calculations (e.g., calculating volume $\pi \times r^2 \times h$ and algorithmic price deltas where $P_{new} = P_{old} + \Delta$) are processed with strict type enforcement to prevent floating-point inaccuracies.

### 2. Performance Optimization

- **Zero-Bloat Asset Loading:** JS and CSS files are strictly enqueued conditionally (e.g., $O(1)$ checks using `is_singular()`) ensuring zero impact on global page load speeds.
- **Algorithmic Efficiency:** Scripts utilize optimized loops and event delegation to maintain $60fps$ UI rendering, even with complex DOM manipulations.
- **Smart Caching:** Heavy database operations utilize WP Transients and in-memory JS caching (`sessionStorage` or JSON localization) to minimize server requests.

### 3. Enterprise Security

- **Data Integrity:** Strict usage of WordPress sanitization (`esc_html`, `esc_attr`, `esc_url`, `absint`) and prepared SQL statements (`$wpdb->prepare()`) to prevent SQL Injection and XSS vulnerabilities.
- **AJAX Security:** All asynchronous requests are validated with cryptographic nonces (`check_ajax_referer`) and strict capability checks (`current_user_can`).

---

## 👨‍💻 About the Author

These plugins are designed, architected, and maintained by **Sina Sotoudeh**.

- 🌐 **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- 🐙 **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- 💼 **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- 📧 **Email:** [s.sotoudeh1@gmail.com](mailto:s.sotoudeh1@gmail.com)

---
