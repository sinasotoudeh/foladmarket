# 🏭 FoladMarket Enterprise E-Commerce Repository

![WordPress](https://img.shields.io/badge/WordPress-Enterprise-21759b.svg?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4%20%7C%208.0%2B-777bb4.svg?logo=php)
![JavaScript](https://img.shields.io/badge/Vanilla_JS-ES6-F7DF1E.svg?logo=javascript)
![Architecture](https://img.shields.io/badge/Architecture-Singleton%20%7C%20OOP-informational)
![Performance](https://img.shields.io/badge/Core_Web_Vitals-Optimized-success.svg)

Welcome to the **FoladMarket** master repository. This workspace houses a complete, highly optimized, and bespoke WordPress ecosystem engineered specifically for the industrial, metallurgical, and B2B steel market sectors.

Unlike standard WordPress builds, this repository is completely stripped of heavy page builders (e.g., Elementor) and relies on native, high-performance PHP template parts, Vanilla ES6 JavaScript, and robust Object-Oriented programming architectures.

---

## 📂 Repository Navigation & Structure (⚠️ Important)

This repository is modularly structured. **This document serves as the high-level executive overview.**

For detailed installation guides, shortcode parameters, mathematical models, and architectural deep-dives, **please navigate to the dedicated `README.md` files located within each specific directory:**

- 📁 **[`/Theme/`](./Theme/)** ➔ Contains the highly optimized, Elementor-free custom child theme. [Read Theme Documentation](./Theme/README.md)
- 📁 **[`/Plugins/`](./Pluguins/)** ➔ Contains 15+ bespoke, performance-driven WordPress plugins. [Read Plugins Documentation](./Pluguins/README.md)

---

## 🏗️ Core Engineering Philosophy (For Employers & Tech Leads)

This project strictly adheres to enterprise-level software development standards. The primary focus across both the theme and the plugins is **Performance**, **Security**, and **Scalability**.

### 1. Advanced Architecture & Design Patterns

- **OOP & Singleton Pattern:** Core logic across plugins relies on the Singleton pattern with private constructors to prevent memory leaks and global namespace pollution.
- **Strict Separation of Concerns (SoC):** Database queries, AJAX handlers, and UI rendering are heavily decoupled.
- **Complex Mathematical Modeling:** Heavy computations (e.g., algorithmic price deltas where $P_{new} = P_{old} + \Delta$, or volume calculations like $\pi \times r^2 \times h$) are processed with strict type enforcement to prevent floating-point inaccuracies.
- **Math Logic in Caching:** Utilizing exact time complexities for Transient expirations (e.g., daily caches set precisely to $60 \times 60 \times 24 = 86400$ seconds).

### 2. Extreme Performance Optimization

- **Zero-Bloat Asset Delivery:** JavaScript and CSS are strictly enqueued conditionally utilizing $O(1)$ complexity checks (e.g., `is_singular()`).
- **Vanilla JavaScript (ES6+):** Complete elimination of jQuery on the frontend. Utilizing native `requestAnimationFrame` for DOM write batching to maintain $60fps$ rendering.
- **Critical CSS & Native Lazy Loading:** Split CSS architecture (critical inline vs. deferred) and customized `<link rel="preload">` fallbacks to maximize Core Web Vitals scores.
- **Advanced Querying:** Opting for custom DB tables and optimized WordPress queries (e.g., utilizing $posts\_per\_page = -1$ carefully with transient caching) to reduce server load.

### 3. SEO, Data Structuring & Security

- **Dynamic Schema JSON-LD:** Highly integrated LocalBusiness and Organization schemas with dedicated transient caching panels.
- **Custom Rewrite Rules:** Sophisticated URL routing overriding native WordPress hierarchies (e.g., mapping to `/{product_slug}/{product_group_slug}/`).
- **Enterprise Security:** Strict adherence to WordPress data sanitization practices and prepared SQL statements (`$wpdb->prepare()`) to eliminate SQL Injection and XSS vulnerabilities. Asynchronous requests are secured with cryptographic nonces.

---

## 📦 System Components Overview

### 🎨 The Theme (`/Theme`)

A custom Astra-child theme engineered for maximum speed. It features a CSS-driven Mega Menu, hardware-accelerated animations, advanced responsive navigation, and dynamic SEO tools. It completely replaces heavy builder blocks with native CSS Grid/Flexbox layouts and custom Post Types (CPT) tailored for the steel industry.

### 🔌 The Plugins (`/Plugins`)

A collection of 15+ custom-built plugins. Highlights include:

- **Sina Grand Calculator:** Multi-stage weight & price calculator for metals with real-time WooCommerce sync.
- **Sina Custom Cart:** B2B Session-Based Cart system using secure cookies and custom DB tables with $O(1)$ queries.
- **Product Price Manager:** Automated algorithmic bulk price updater based on daily USD/IRR fluctuations.
- **Visual & UI Plugins:** Instagram-style stories via ACF, Horizontal Galleries, and Context-aware Subcategory Dropdowns utilizing Tree Math Aggregation ($\sum Count$).

_(Refer to the [Plugins Directory README](./Pluguins/README.md) for the full list of 15+ plugins)._

---

## 👨‍💻 About the Architect

This enterprise solution was designed, developed, and is maintained by **Sina Sotoudeh**.

- 🌐 **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- 🐙 **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- 💼 **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- 📧 **Email:** [s.sotoudeh1@gmail.com](mailto:s.sotoudeh1@gmail.com)
