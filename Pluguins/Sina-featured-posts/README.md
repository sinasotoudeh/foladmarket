# 🌟 Sina Featured Posts

![WordPress](https://img.shields.io/badge/WordPress-Plugin-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php)
![JavaScript](https://img.shields.io/badge/JS-Vanilla-F7DF1E?logo=javascript)
![CSS3](https://img.shields.io/badge/CSS3-Responsive-1572B6?logo=css3)
![Status](https://img.shields.io/badge/Status-Active-success)

**Sina Featured Posts** is a lightweight, highly optimized WordPress plugin that provides a dynamic, auto-rotating featured posts slider via a simple shortcode. Designed with both performance and user experience in mind, it seamlessly integrates into any theme.

---

## ✨ Key Features

- 🚀 **Performance Optimized:** Only loads assets (CSS/JS) when the shortcode is actually present on the page.
- 📱 **Fully Responsive:** Flexbox-based layout that adapts gracefully to mobile devices.
- 🔄 **Smart Auto-Rotation:** Automatically cycles through posts, but intuitively pauses on hover to improve readability.
- 🧩 **Multi-Instance Support:** Can be used multiple times on the same page without conflicts.
- 🖼️ **SEO Friendly:** The initial post is rendered server-side in the HTML DOM for search engine crawlers.

---

## 🛠️ User-Centric: Installation & Usage

### 📦 Installation

1. Download the plugin files and place them in your `/wp-content/plugins/` directory.
2. Navigate to the **Plugins** screen in your WordPress dashboard.
3. Activate the **Sina Featured Posts** plugin.

### 💻 Usage

To display the featured posts slider, insert the following shortcode into any post, page, or widget:

```text
[fm_featured_posts posts="5" rotation="3000" image_size="large"]
```

### 📋 Shortcode Parameters

You can customize the slider behavior using the following attributes:

| Parameter    | Default Value | Description                                                                    |
| :----------- | :------------ | :----------------------------------------------------------------------------- |
| `posts`      | `5`           | The maximum number of posts to retrieve and display in the list.               |
| `rotation`   | `3000`        | The delay between auto-rotations in milliseconds (e.g., $3000$ = $3$ seconds). |
| `image_size` | `large`       | The registered WordPress image size slug to use for the featured image.        |

> _Note: The plugin automatically targets posts from the `special_article_category` taxonomy with the `blogs` term._

---

## ⚙️ Developer-Centric: Architecture & Code Quality

This section is strictly for developers, maintainers, or code reviewers analyzing the architectural decisions, performance metrics, and security implementations of the plugin.

### 1. 🏗️ Component Architecture & Multi-Instance Logic

To prevent global variable pollution and ensure multiple shortcodes can coexist on a single page, the plugin utilizes a **Unique Identifier (UID) Strategy**.

- Each shortcode instance generates a unique ID via PHP's `uniqid()`.
- Data payloads are pushed into a global array using `wp_add_inline_script()`: `window.fmFeaturedInit.push(...)`.
- The Vanilla JS iterates over `window.fmFeaturedInit` on `DOMContentLoaded` and initializes isolated functional closures for each slider, ensuring local scope encapsulation.

### 2. ⚡ Performance & DOM Rendering

The plugin adopts a **Hybrid Rendering Strategy**:

- **Server-Side Rendering (SSR):** The _first_ post is rendered purely in PHP. This guarantees that search engines and users on slow networks immediately see the main content without waiting for JS execution.
- **Client-Side Hydration:** The rest of the posts' data is serialized via `wp_json_encode()` and managed by JavaScript. This significantly reduces the initial DOM size by avoiding duplicate HTML structures.

### 3. ⏱️ Mathematical & Timing Logic

The JavaScript logic relies on a standard modulo operation to cycle through the array of posts indefinitely. The rotation interval is defined in milliseconds. If a user defines `rotation="4500"`, the time calculation for a full cycle of $5$ posts would be:
`$$ 4500 \times 5 = 22500 $$` milliseconds ($22.5$ seconds) before returning to the first post.
The auto-rotation utilizes `setInterval()` and is intelligently cleared (`clearInterval`) via `mouseenter` and restarted on `mouseleave` to respect user interaction (UX).

### 4. 🛡️ Security & Quality Standards

- **Data Sanitization:** Strict enforcement of escaping functions (`esc_attr()`, `esc_html()`, `esc_url()`) when outputting variables to the DOM.
- **Asset Management:** The CSS and JS files are dynamically registered (`wp_register_...`) and only enqueued (`wp_enqueue_...`) inside the shortcode callback. This ensures zero payload overhead on pages where the shortcode is absent.
- **Vanilla Stack:** Complete reliance on modern Vanilla JS and CSS3 Flexbox. Zero dependencies on heavy libraries like jQuery or Bootstrap.

---

## 👨‍💻 Author Info

- **Name:** Sina Sotoudeh
- **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- **Email:** s.sotoudeh1@gmail.com
