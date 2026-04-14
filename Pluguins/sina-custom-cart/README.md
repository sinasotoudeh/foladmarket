# 🛒 Sina Custom Cart

[![Version](https://img.shields.io/badge/Version-1.2.0-blue.svg?style=flat-square)](https://github.com/sinasotoudeh/sina-custom-cart)
[![WordPress](https://img.shields.io/badge/WordPress-5.8+-20c997.svg?style=flat-square&logo=wordpress)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4+-777bb4.svg?style=flat-square&logo=php)](https://php.net/)
[![Dependencies](https://img.shields.io/badge/Requires-ACF_PRO-red.svg?style=flat-square)](https://www.advancedcustomfields.com/)
[![License](https://img.shields.io/badge/License-GPLv2-green.svg?style=flat-square)](https://www.gnu.org/licenses/gpl-2.0.html)

A highly optimized, standalone **Session-Based Cart and Checkout System** for WordPress, designed specifically for non-WooCommerce environments (such as B2B inquiry/quote systems, raw material suppliers, and custom product catalogs). Built with strict adherence to WordPress coding standards, robust security protocols, and modern UI/UX principles.

This plugin operates entirely independent of user authentication, using secure cookies to track guest sessions seamlessly.

---

## 🌟 Core Features (User & Business Centric)

- **No Login Required:** Identifies users seamlessly via robust, secure cookies (`HttpOnly`, `Secure`, `SameSite=Lax`).
- **Data Snapshot Technology:** Preserves product specifications (size, thickness, alloy, etc.) at the exact time of inquiry. Future admin changes to products will not alter existing customer carts.
- **Modern, Responsive UI:** On screens narrower than $768\text{px}$, complex cart tables gracefully transform into highly readable, flex-based mobile cards.
- **Dark Mode & Accessibility (a11y):** Out-of-the-box support for Dark Mode (`@media (prefers-color-scheme: dark)`), High Contrast, and reduced motion.
- **AJAX-Driven:** Seamless addition, removal, and quantity updates without page reloads, providing a smooth SPA-like experience.

---

## 🚀 Installation & Requirements

### ⚠️ Dependencies

**CRITICAL:** This plugin heavily relies on the **Advanced Custom Fields (ACF)** plugin. Product variations and specifications are dynamically extracted from ACF repeater fields (specifically a field named `product_rows`).

### ⚙️ Setup

1. Upload the `sina-custom-cart` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Upon activation, the plugin automatically creates essential database tables and generates two pages:
   - `/price/` containing the `[sina_cart_page]` shortcode.
   - `/checkout/` containing the `[sina_checkout_page]` shortcode.

---

## 📝 Available Shortcodes

Use the following shortcodes to display cart elements anywhere on your site:

| Shortcode              | Attributes                | Default | Description                                                                |
| :--------------------- | :------------------------ | :------ | :------------------------------------------------------------------------- |
| `[sina_cart_page]`     | _None_                    | N/A     | Renders the main cart table/cards with dynamic specifications.             |
| `[sina_checkout_page]` | _None_                    | N/A     | Renders the checkout form. Auto-fills Name/Email if the user is logged in. |
| `[sina_mini_cart]`     | `show_icon`, `show_total` | `yes`   | Renders a mini-cart icon with a live item count badge (ideal for headers). |
| `[sina_cart_count]`    | `show_zero`               | `yes`   | Displays only the integer number of items currently in the cart.           |

---

## 🏗️ Architecture & Code Quality (Developer-Centric)

This section highlights the architectural decisions and design patterns applied, demonstrating enterprise-level WordPress plugin development.

### 1. 📐 Design Patterns & Modularity

- **Singleton Pattern:** Core classes strictly implement the Singleton pattern (`get_instance()`) with private `__construct`, `__clone`, and `__wakeup` methods to prevent memory leaks and serialization vulnerabilities.
- **Separation of Concerns (SoC):** Logic is strictly decoupled. Database operations, Session management, AJAX handling, and Shortcode rendering are isolated into dedicated classes.

### 2. 🗄️ Database Strategy & Performance

- **Custom Relational Tables:** Avoids `wp_postmeta` bloat by utilizing dedicated `wp_sina_cart` and `wp_sina_orders` tables via `dbDelta`. Composite unique keys ensure rapid $O(1)$ and $O(\log n)$ read/write operations.
- **Automated Garbage Collection:** A daily WP Cron job (`sina_cart_cleanup_cron`) automatically purges abandoned guest carts after $30$ days and logged-in user carts after $90$ days.

### 3. 🛡️ Security Measures

- **Strict AJAX Validation:** Every payload is verified using `check_ajax_referer`.
- **Pre-Flight Session Enforcement:** A custom hook validates the existence of a legitimate session cookie _before_ any CRUD operation is allowed, thwarting unauthorized payload injections.
- **Sanitization:** Aggressive sanitization on all I/O operations (`absint`, `sanitize_text_field`). Database queries strictly use `$wpdb->prepare()`. Form submission enforces a strict regex for Iranian mobile numbers (`/^09\d{9}$/`).

### 4. ⚡ Frontend Optimization

- **Debouncing:** Manual quantity updates utilize a debounce timer of $800\text{ms}$. Rapid user clicks are consolidated into a single server request, drastically reducing server load.
- **Event Delegation:** JS events are bound to the document to ensure DOM elements re-rendered via AJAX remain functional.

---

## 🔌 Extensibility (Hooks, Filters & APIs)

The plugin exposes several hooks for seamless integration with custom themes and third-party services (like SMS gateways).

### Action Hooks

- `sina_cart_item_added` / `sina_cart_item_updated` / `sina_cart_item_removed`
- `sina_cart_order_created`: Crucial for SMS/Email APIs. Passes `$order_id` and `$order_data`.
- `sina_checkout_billing_after_fields`: Allows injecting custom fields into the checkout form.

### Filters

- `sina_cart_item_specs`: Modify product attributes dynamically before rendering.
- `sina_cart_page_output`: Filter the complete HTML output of the cart page.

### Global Helper Functions

```php
// Get the main plugin instance
$cart = sina_cart();

// Check if plugin is active
$is_active = sina_cart_is_active();

// Get the user's 32-character secure session hash
$session_id = sina_cart_get_session_id();
```

---

## 🎨 Theming & Customization

The plugin uses a BEM-inspired, namespaced approach (`.sina-`) to prevent CSS bleeding. Styling is handled via CSS Custom Properties (`:root`), allowing easy white-labeling in your theme's stylesheet:

```css
:root {
  --sina-primary: #ffc107; /* Golden Yellow */
  --sina-secondary: #343a40; /* Dark Gray */
  --sina-border-radius: 8px;
}
```

---

## 👨‍💻 Author & Maintainer

**Sina Sotoudeh**

- 🌐 **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- 💻 **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- 🤝 **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- ✉️ **Email:** [s.sotoudeh1@gmail.com](mailto:s.sotoudeh1@gmail.com)
