# Instagram Stories for ACF

[![Version](https://img.shields.io/badge/version-1.2.0-blue.svg)](#)
[![WordPress](https://img.shields.io/badge/WordPress-Compatible-green.svg)](#)
[![ACF](https://img.shields.io/badge/ACF-Required-orange.svg)](#)

**Instagram Stories for ACF** is a lightweight, performance-optimized WordPress plugin that beautifully displays Advanced Custom Fields (ACF) gallery images as interactive Instagram-style stories via a customizable shortcode.

## 🚀 Features & Highlights

### User Experience (UX) & User Interface (UI)

- **Instagram-like Experience:** Familiar tap-to-advance (right/left side of the image) and progress bar visualization.
- **Touch & Swipe Support:** Fully responsive with native-feeling swipe gestures for mobile devices.
- **Keyboard Navigation:** Support for arrow keys (Right/Down for next, Left/Up for previous) for desktop accessibility.
- **Hover to Pause:** Automatically pauses the story progression when hovering over the container.
- **Zoom/Fullscreen Action:** Built-in zoom button to open the current story image in a new tab for a detailed view.
- **Responsive Design:** Automatically adjusts to screen sizes, ensuring optimal display on both desktop and mobile.

### Performance & Optimization

- **Lazy Loading:** Images utilize the native `loading="lazy"` attribute, ensuring fast page load times and reduced bandwidth consumption.
- **Lightweight Assets:** Minimal CSS and JS footprint. Assets are enqueued modularly.
- **Hardware Acceleration:** CSS transitions and transforms are optimized for smooth 60fps animations.
- **Object-Oriented PHP:** Clean, singleton-based PHP architecture prevents multiple instantiations and memory leaks.

### Flexibility & Fallbacks

- **Smart Data Handling:** The plugin intelligently handles both complete ACF image arrays (24-key arrays) and plain attachment IDs, extracting URLs and `alt` tags correctly.
- **SEO Friendly:** Properly outputs `alt` tags for all images extracted from the media library or ACF data, maintaining image SEO value.

---

## 🛠️ Installation

1. Download the plugin folder `instagram-stories-acf`.
2. Upload it to your WordPress `wp-content/plugins/` directory.
3. Go to the **Plugins** menu in WordPress and activate **Instagram Stories for ACF**.
4. Make sure you have the [Advanced Custom Fields (ACF)](https://www.advancedcustomfields.com/) plugin installed and activated.

---

## 💻 Usage

After activation, you can display stories anywhere on your site using the `[acf_stories]` shortcode.

### Basic Usage

Will attempt to pull images from an ACF field named `product_stories` on the current post.

```text
[acf_stories]

### Advanced Usage with Parameters
Customize the behavior and appearance of the stories using shortcode attributes:

text
[acf_stories field="product_stories" duration="10000" width="600px" show_controls="true" loop="true"]

### Shortcode Attributes Reference

| Parameter       | Default             | Description                                                                                             |
| :-------------- | :------------------ | :------------------------------------------------------------------------------------------------------ |
| `field`         | `product_stories`   | The name of the ACF Gallery or Repeater/Image field containing the stories.                             |
| `duration`      | `2500`              | Duration to show each story slide in milliseconds ($ms$). *(Note: JS default fallback is $15000$)*      |
| `autoplay`      | `true`              | Set to `false` to disable automatic progression of stories.                                             |
| `show_controls` | `true`              | Set to `false` to hide the next/prev navigation arrows and the zoom button.                             |
| `loop`          | `true`              | Set to `false` to stop at the last story instead of looping back to the beginning.                      |
| `width`         | `500px`             | Maximum width of the story container. Can use `px`, `%`, etc. (e.g., `100%`).                           |

**Example for a Product Page:**
Displaying a product gallery in a full-width mobile container with a 20-second delay per image:
text
[acf_stories field="product_gallery" duration="20000" width="100%"]

---

## 🏗️ Architecture & Code Quality (For Reviewers)

* **Singleton Pattern:** The core class `Instagram_Stories_ACF` utilizes the Singleton design pattern to ensure only one instance of the plugin runs, conserving resources.
* **Security:**
  * Strict `ABSPATH` check to prevent direct file access.
  * Robust output escaping using `esc_attr()`, `esc_html()`, and `esc_url()` to prevent XSS vulnerabilities.
* **Resilient Data Parsing:** The `$raw` data from ACF is evaluated cautiously. The plugin gracefully handles structural differences in ACF returns (IDs vs. Arrays), ensuring fatal errors are avoided if ACF settings change.
* **Modern JavaScript:** The frontend logic is written using ES6 Classes (`class InstagramStories`) for clean scope management and maintainability, encapsulated within an IIFE to avoid global namespace pollution.

---

## 👨‍💻 Author

**Sina Sotoudeh**
* Version: 1.2.0

---
*Note: This plugin requires a minimum of PHP 7.4+ (due to Null Coalescing operators `??`) and WordPress 5.0+.*
```
