# 🖼️ Sina ACF Horizontal Gallery

![Version](https://img.shields.io/badge/Version-1.3-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-Compatible-21759b.svg?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4.svg?logo=php)
![ACF](https://img.shields.io/badge/ACF-Required-00E676.svg)
![JavaScript](https://img.shields.io/badge/Vanilla_JS-ES6-F7DF1E.svg?logo=javascript)

A lightweight, high-performance WordPress plugin designed to render a horizontal, single-row image gallery from an **Advanced Custom Fields (ACF)** gallery field (`product_gallery`). It features interactive navigation arrows, a dynamic image counter, and seamless synchronization with Elementor's main featured image widget.

---

## ✨ Key Features

- **Touch-Optimized Scrolling:** Utilizes native CSS `scroll-snap` for smooth, mobile-friendly swipe interactions.
- **Dynamic Featured Image Sync:** Automatically updates the main Elementor product image (`.elementor-widget-theme-post-featured-image img`) when a thumbnail is clicked or scrolled.
- **Live Counter:** Displays a real-time index indicator (e.g., 1 / 5).
- **Smart Auto-Detection:** Automatically detects the closest centered image during manual horizontal scrolling.
- **Zero Dependencies:** Built entirely with **Vanilla JavaScript** and native CSS (No jQuery required).

---

## 🛠️ User Guide (Installation & Usage)

### 1. Prerequisites

Ensure you have the following installed and activated:

- **Advanced Custom Fields (ACF)** (Free or Pro).
- An ACF Gallery or Image Array field configured with the name `product_gallery`.
- _(Optional but recommended)_ **Elementor**, if you wish to utilize the featured image replacement feature.

### 2. Installation

1. Download the `sina-acf-horizontal-gallery.php` file.
2. Place it inside a new directory named `sina-acf-horizontal-gallery` in your `/wp-content/plugins/` folder.
3. Navigate to **Plugins > Installed Plugins** in your WordPress dashboard.
4. Locate **Sina ACF Horizontal Gallery** and click **Activate**.

### 3. Usage & Shortcode

To display the gallery on any single post or product template, use the following shortcode:

```text
[sina_acf_gallery]
```

### ⚙️ Configuration Parameters

Currently, the plugin relies on implicit data structures. Below is the data configuration table for proper operation:

| Dependency           | Required Value                                    | Description                                                                                                |
| :------------------- | :------------------------------------------------ | :--------------------------------------------------------------------------------------------------------- |
| **ACF Field Name**   | `product_gallery`                                 | The shortcode automatically fetches data from this specific ACF field associated with the current post ID. |
| **Elementor Target** | `.elementor-widget-theme-post-featured-image img` | The CSS selector targeted by the JS script to replace the main product image dynamically.                  |
| **Image Size**       | `medium_large`                                    | The WP image size fetched for thumbnails to ensure optimal loading speed.                                  |

---

## 🏗️ Architecture & Code Quality (For Reviewers)

This section details the technical decisions, performance optimizations, and security measures implemented within the codebase.

### 🛡️ Security & PHP Best Practices

- **Data Sanitization:** All outputted image URLs are rigorously sanitized using WordPress's native `esc_url()` function to prevent XSS vulnerabilities.
- **Output Buffering:** The shortcode utilizes `ob_start()` and `ob_get_clean()`. This ensures that the HTML is rendered in the correct sequence within the DOM hierarchy, rather than prematurely outputting at the top of the page.
- **Conditional Rendering:** The code performs strict validation (`if (!$post_id)` and `if (empty($images))`) before executing DOM logic, preventing PHP warnings on pages lacking the ACF field.

### ⚡ Performance & UI/UX Optimization

- **Optimized Image Loading:** Instead of requesting full-resolution images, the PHP script extracts the `medium_large` array key. This dramatically reduces payload size and improves the **LCP (Largest Contentful Paint)** score for SEO.
- **Hardware-Accelerated CSS:** Uses `-webkit-overflow-scrolling: touch` and `scroll-snap-type: x mandatory` to offload scroll animations to the browser's compositor thread, ensuring a buttery-smooth 60fps experience on mobile devices.
- **Hidden Scrollbars:** Implements `::-webkit-scrollbar { display: none; }` to maintain a clean UI while preserving native scroll functionality.

### 🧠 JavaScript Logic & Mathematical Modeling

The Vanilla JS script handles complex DOM interactions without relying on heavy libraries.

**Smart Scroll Calculation:**
To identify which image should be highlighted as "active" during a manual user scroll, the script continuously calculates the absolute distance between each item's left offset and the gallery's current scroll position.

The mathematical logic is based on finding the minimum distance:
$$D = \min_{i=0}^{n-1} \left| x_{i} - S_{left} \right|$$
Where $x_{i}$ is the offset of the item, and $S_{left}$ is the current scroll position. The script iterates through the DOM elements and updates the active class where $D$ is the smallest (`smallestDistance = Infinity`), ensuring pinpoint accuracy even if the user stops scrolling halfway between two images.

**Event Delegation & Memory Management:**

- Uses `DOMContentLoaded` to ensure the DOM is fully parsed before execution.
- Uses `querySelectorAll` within a `.forEach` loop, encapsulating variables block-scope (`const`, `let`) to prevent global namespace pollution.
- Updates main image attributes dynamically (`removeAttribute("srcset")`) to override responsive image fallbacks when injecting the new source.

---

## 👨‍💻 Author Information

Created and maintained by **Sina Sotoudeh**.

- 🌐 **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- 🐙 **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- 💼 **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- 📧 **Email:** s.sotoudeh1@gmail.com
