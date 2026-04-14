# 🚀 Sina Custom Menu

![WordPress](https://img.shields.io/badge/WordPress-Plugin-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php)
![JavaScript](https://img.shields.io/badge/JS-Vanilla-F7DF1E?logo=javascript)
![CSS3](https://img.shields.io/badge/CSS3-Responsive-1572B6?logo=css3)
![Status](https://img.shields.io/badge/Status-Active-success)

**Sina Custom Menu** is a high-performance, lightweight WordPress plugin designed to automatically generate and render a hierarchical menu of categories and products. It utilizes a hybrid rendering approach (Server-Side PHP + Client-Side JSON/JS) to minimize the initial DOM size and maximize page load speed.

---

## ✨ Key Features

- **Dynamic Tree Generation:** Automatically fetches and maps categories and their associated products.
- **Lazy DOM Injection:** Submenus are not printed in the HTML payload. They are generated via vanilla JavaScript only upon user interaction.
- **Smart Caching:** Uses WordPress Transients to cache the heavy database queries.
- **Responsive UI:** Seamless experience across desktop (hover-based fly-outs) and mobile (click-based accordions).
- **Visual Cues:** Automatically appends relevant semantic emojis (📁 for categories, 🛒 for products) to submenu items.

---

## 🛠️ User-Centric: Installation & Usage

### 📦 Installation

1. Download the plugin folder and upload it to your `/wp-content/plugins/` directory.
2. Navigate to the **Plugins** menu in WordPress.
3. Activate **Sina Custom Menu**.

### 💻 Usage

To display the menu, simply use the following shortcode wherever you need it (e.g., in your header template or a widget):

```text
[cmenu_ui]
```

### 📋 Shortcode Parameters

Currently, the plugin uses a unified shortcode with no required attributes. The logic is handled globally via the plugin's core functions.

| Shortcode    | Parameters | Default Value | Description                                                                    |
| :----------- | :--------- | :------------ | :----------------------------------------------------------------------------- |
| `[cmenu_ui]` | _None_     | `N/A`         | Renders the root menu items and embeds the JSON data payload for lazy loading. |

> _Note: To clear the menu cache manually as an admin, simply visit any page; the `init` hook clears the transient automatically for `manage_options` capabilities._

---

## ⚙️ Developer-Centric: Architecture & Code Quality

This section is dedicated to code reviewers and senior developers analyzing the plugin's underlying architecture, performance, and UI/UX implementation.

### 1. Performance & SEO Optimization

The plugin solves a major SEO and performance bottleneck common in massive e-commerce menus (Mega Menus) by preventing "DOM Bloat":

- **Initial Load:** The server outputs _only_ the root elements in HTML.
- **Data Payload:** The entire menu tree structure is serialized using `wp_json_encode()` and stored in a hidden `<script type="application/json">` tag.
- **On-Demand DOM Creation:** The JS script parses the JSON. If a user hovers (desktop) or clicks (mobile), the script dynamically creates the `<ul>` and `<li>` elements using `document.createElement()`.

### 2. Caching Strategy & Math

The plugin uses the WordPress Transients API to cache the result of `cmenu_build_full_tree()`.
The cache duration is set to `DAY_IN_SECONDS`. Mathematically, this evaluates to:
‍`$$ 60 \times 60 \times 24 = 86400 $$` seconds.
Cache invalidation is strictly hooked to CRUD operations on categories and products (`save_post_product`, `edited_category`, etc.), ensuring the UI is always synchronized with the database without manual intervention.

### 3. Algorithmic Sorting

The plugin utilizes `usort` to organize root categories based on a hardcoded `$desired_order` array `[33, 65, 153, 67, 244, 71, 243, 245, 246]`.
Categories not present in the custom order array are safely pushed to the end by assigning them `PHP_INT_MAX`. The time complexity of this sorting operation is $O(n \log n)$.

### 4. Code Quality & Security

- **Sanitization:** Strict usage of WordPress escaping functions (`esc_attr()`, `esc_url()`, `esc_html()`) when rendering the root nodes in PHP.
- **JSON Security:** Uses `JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES` to ensure the JSON payload is clean and properly formatted without unnecessary escaping overhead.
- **Vanilla JS:** Zero dependencies on jQuery. The client-side logic is written entirely in modern Vanilla JS, reducing the network load.

---

## 👨‍💻 Author Info

- **Name:** Sina Sotoudeh
- **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- **Email:** s.sotoudeh1@gmail.com
