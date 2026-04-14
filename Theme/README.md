# 🏭 FoladMarket High-Performance Theme

![WordPress](https://img.shields.io/badge/WordPress-6.0+-blue.svg?style=for-the-badge&logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4.svg?style=for-the-badge&logo=php)
![Vanilla JS](https://img.shields.io/badge/Vanilla_JS-ES6-F7DF1E.svg?style=for-the-badge&logo=javascript)
![CSS3](https://img.shields.io/badge/CSS3-Modern_Variables-1572B6.svg?style=for-the-badge&logo=css3)
![Performance](https://img.shields.io/badge/Core_Web_Vitals-Optimized-success.svg?style=for-the-badge)
![Astra Child](https://img.shields.io/badge/Theme-Astra_Child-purple.svg?style=for-the-badge)

_A highly optimized, Elementor-free, and structurally advanced custom WordPress child theme tailored for the steel market industry._

---

## 👥 User-Centric Section

### 🚀 Key Features

- **🛒 Custom Products Management:** Dedicated `product` Custom Post Type (CPT) with categorized hierarchies.
- **⚡ Blazing Fast Homepage:** Completely stripped of heavy page builders (like Elementor) in favor of modular, native PHP template parts.
- **📱 Advanced Responsive Navigation:** Features a CSS-driven Mega Menu for desktop (`megamenu-container`) and an off-canvas, slide-in mobile menu with smooth animations.
- **💬 Mobile Quick Actions:** Sticky, gradient-styled floating action buttons for mobile devices, enabling instant access to WhatsApp, Phone calls, Calculator, and Product Comparison.
- **🛡️ Premium Trust Bar & Stats:** Built-in floating stat cards and "Trust Bar Premium" components styled with modern CSS Grid layouts to highlight company credibility and certifications.
- **📅 Dynamic SEO Titles:** Automatically appends the current Jalali date to Yoast SEO titles (e.g., updates daily) to keep SERP results fresh.
- **📊 Advanced Schema Markup:** Built-in LocalBusiness and Organization JSON-LD schemas for enhanced search engine visibility.
- **🗄️ Cache Management UI:** A dedicated admin panel and top-bar button to manage Schema caches effortlessly.

### 🛠️ Installation & Setup

1. Ensure the parent theme (**Astra**) is installed and active.
2. Clone or upload this repository into `wp-content/themes/foladmarket-child`.
3. Activate **FoladMarket Child Theme** from the WordPress dashboard.
4. To utilize the high-performance homepage, create a new page, set it as the front page, and assign the `Homepage Custom (High Performance)` template.

### ⚙️ Shortcodes & Parameters (Example)

If implementing custom pricing blocks via shortcodes in the future, parameters are structured as follows:

| Parameter     | Default Value | Description                                                |
| :------------ | :------------ | :--------------------------------------------------------- |
| `product_ids` | `null`        | Comma-separated list of product IDs (e.g., `38148,38170`). |
| `layout`      | `stack`       | UI layout style: `stack`, `grid`, or `carousel`.           |
| `show_date`   | `true`        | Boolean to display the dynamic date badge.                 |

---

## 🏗️ Architecture & Code Quality (Developer/Reviewer-Centric)

This section details the underlying architecture, highlighting the specific optimization techniques, security measures, and custom routing implemented in the core files.

### ⚡ 1. Performance & Asset Delivery Optimization

The theme employs an aggressive, multi-layered asset delivery strategy to achieve optimal Core Web Vitals:

- **Critical CSS Architecture:** CSS is strictly split into `critical.min.css` (injected inline for immediate above-the-fold rendering) and `none-critical.min.css` (lazy-loaded).
- **Prioritized Resource Loading:**
  - _Priority 1:_ `woff2` fonts and critical inline CSS are preloaded/injected directly into `<head>`.
  - _Priority 2:_ Critical CSS files are preloaded.
- **Native Lazy Loading & Deferment:**
  - Non-critical CSS is dynamically transformed using `wp_style_add_data($handle, 'lazy', true)` and custom `<link rel="preload" onload="...">` fallbacks.
  - JavaScript execution is deferred, and non-essential scripts utilize a custom polyfilled `requestIdleCallback` to ensure the main thread remains unblocked.
- **Asset Stripping:** On the custom homepage (`template-homepage.php`), all heavy dependencies including Elementor, Astra core assets, WooCommerce block libraries, and native WP emojis are completely dequeued via `wp_dequeue_style` and `wp_dequeue_script`.

### 🎨 2. Modern CSS & Theming Engine

The theme completely abandons page builder bloat in favor of a robust, native CSS variables architecture:

- **Design Tokens (CSS Variables):** A comprehensive root variable system (`:root`) manages colors (e.g., `--color-brand-gold`), typography scales (`--font-size-xs` to `--font-size-6xl`), layout spacing (`--spacing-xs` to `--spacing-3xl`), and z-index hierarchy.
- **Pre-defined Content Types:** Custom layout classes (`contentstyletype1` to `contentstyletype5`) leverage CSS Grid and Flexbox to generate complex UI components dynamically via code, replacing the need for Elementor sections.
- **Hardware-Accelerated Animations:** Uses performant CSS keyframes (`fadeInUp`, `slideInFromRight`, `bounce`) utilizing `transform` and `opacity` properties to prevent DOM repaints.
- **Accessibility & Reduced Motion:** Native support for `@media (prefers-reduced-motion: reduce)` to disable animations for accessibility compliance, and `@media (prefers-contrast: high)` for enhanced visual distinction.

### 🔀 3. Custom Routing & CPT Architecture

Instead of relying on heavy permalink plugins, native WordPress Rewrite Rules are utilized:

- **Product Permalinks:** Custom rewrite rules dynamically reverse the standard hierarchy to `/{product_slug}/{product_group_slug}/`.
- **Article Silos:** A custom taxonomy (`special_article_category`) introduces a hierarchical structure for posts: `^articles/([^/]+)/([^/]+)/?$`.

### 🧠 4. Transient-Based Schema Caching

To reduce database queries, JSON-LD Schema markups are cached using WordPress Transients.

- **Math Logic Example:** A standard daily cache expires in $$60 \times 60 \times 24 = 86400$$ seconds. Weekly caches for Organization schema expire in $$86400 \times 7 = 604800$$ seconds.
- **Admin Integration:** Features a highly integrated Admin UI with Nonce-verified `GET/POST` requests (`foladmarket_clear_schema_cache()`) to manually flush transients without accessing the database directly.

### 🛡️ 5. SEO & Security Enhancements

- **Query String Sanitization:** A custom hook intercepts suspicious query strings (e.g., `_bd_prev_page`, `_gl`, `_ga`) on the frontend. If detected, it forces a hard `HTTP 404` status via `status_header(404)` and injects canonical links to prevent index bloat and duplicate content penalties.
- **Static Resource Clean-up:** Removes WordPress versioning (`?ver=`) from static assets to improve cacheability on CDNs.

### 🧠 6. UI/UX & Vanilla JavaScript Execution

The frontend interactions (`homepage.min.js`) are written entirely in **Vanilla ES6 JavaScript**, eliminating the need for jQuery and heavily optimized for the main thread:

- **DOM Write Batching (RAF):** Uses `requestAnimationFrame` to batch DOM updates, effectively eliminating layout thrashing and forced synchronous reflows.
- **Event Delegation:** Click, keydown, and touch events are delegated to parent containers (e.g., in USP sliders and FAQ accordions) to reduce the number of active event listeners and prevent memory leaks.
- **Intersection Observer API:** Dynamically pauses auto-playing carousels and triggers animations only when elements enter the viewport (`isIntersecting`).
- **Performance Utilities:** Implements custom `debounce` functions for resize handlers and passive event listeners for smooth touch/scroll tracking.
- **Accessibility (a11y):** Advanced ARIA states (`aria-expanded`, `aria-hidden`, `aria-selected`) and full keyboard navigation logic (listening for `ArrowKeys`, `Enter`, and `Space`) across accordions and pricing stack sliders.
- **Native Smooth Scrolling:** Uses native `scrollIntoView` coupled with event delegation for internal anchor links.
- **DOM Manipulation Optimization:** Replaces heavy DOM rewrites (like replacing `<img>` tags) with simple `src` attribute updates coupled with `transitionend` events for seamless rendering.

```javascript
// Example snippet: Batching DOM updates to avoid forced reflows
function setActiveFeature(index, shouldScroll = false) {
  if (index < 0 || index >= featureButtons.length || isAnimating) return;
  isAnimating = true;

  // Batch all DOM writes in a single RAF
  requestAnimationFrame(() => {
    featureButtons.forEach((btn, i) => {
      const isActive = i === index;
      btn.classList.toggle("active", isActive);
      btn.setAttribute("aria-selected", isActive ? "true" : "false");
    });

    contentItems.forEach((content, i) => {
      content.classList.toggle("active", i === index);
    });
    activeIndex = index;
  });

  setTimeout(() => {
    isAnimating = false;
  }, 400);
}
```

---

## 👨‍💻 Author Information

**Sina Sotoudeh**

- 🌐 **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- 🐙 **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- 💼 **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- ✉️ **Email:** s.sotoudeh1@gmail.com
