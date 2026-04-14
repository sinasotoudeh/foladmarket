# 📞 Sina Contact-Us Box

![Version](https://img.shields.io/badge/Version-1.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-Compatible-21759b.svg?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4.svg?logo=php)
![CSS3](https://img.shields.io/badge/CSS3-Responsive-1572B6.svg?logo=css3)

A lightweight, visually appealing WordPress plugin that generates a highly converting "Contact Us" widget via a simple shortcode. Designed specifically for Persian (RTL) websites, it provides quick access to sales experts, direct phone lines, and internal extensions with a modern, dark-themed UI.

---

## ✨ Key Features

- **Plug-and-Play Shortcode:** Easily embed the contact box anywhere on your site (pages, posts, Elementor widgets).
- **Actionable Links:** Utilizes HTML5 `tel:` protocols so users can directly dial numbers via mobile devices with a single tap.
- **Dynamic Asset Loading:** CSS is only enqueued when the plugin is active, featuring automatic cache-busting.
- **Fully Responsive UI:** Flawless scaling from large desktop monitors down to mobile screens (`< 480px`).
- **Customizable Avatar:** Ability to override the default expert image dynamically via shortcode attributes.

---

## 🛠️ User Guide (Installation & Usage)

### 1. Installation

1. Download the plugin folder and ensure it is named `sina-contact-us-box`.
2. Upload the folder to your `/wp-content/plugins/` directory.
3. Navigate to **Plugins > Installed Plugins** in your WordPress dashboard.
4. Locate **Sina Contatct-us Box** and click **Activate**.

### 2. Usage & Shortcode

To display the contact box, simply insert the following shortcode into any text editor or page builder block:

```text
[contact-us-product]
```

To use a custom image for the sales expert, use the `image` attribute:

```text
[contact-us-product image="https://yoursite.com/wp-content/uploads/custom-avatar.jpg"]
```

### ⚙️ Shortcode Parameters

| Parameter | Default Value               | Description                                                                                                                      |
| :-------- | :-------------------------- | :------------------------------------------------------------------------------------------------------------------------------- |
| `image`   | `assets/images/expert.webp` | The absolute URL of the avatar image displayed inside the contact box. Optimally, a square image (rendered as a circle via CSS). |

---

## 🏗️ Architecture & Code Quality (For Reviewers)

This section highlights the technical foundation, UI/UX decisions, and WordPress best practices utilized in the plugin's development.

### 🛡️ PHP & WordPress Standards

- **Output Buffering:** The shortcode logic is wrapped inside `ob_start()` and `ob_get_clean()`. This guarantees the HTML renders exactly where the shortcode is placed within the DOM, preventing the common bug of shortcodes outputting at the top of the page.
- **Data Sanitization:** The custom image attribute is strictly sanitized during output using the `esc_url()` function to prevent XSS (Cross-Site Scripting) vulnerabilities.
- **Attribute Fallbacks:** Uses the native `shortcode_atts()` function to ensure safe extraction of user-defined attributes alongside predefined defaults.
- **Smart Cache-Busting:** Asset enqueuing leverages `filemtime($css)` as the version parameter. This ensures that whenever the CSS file is modified, the browser cache is automatically broken, delivering the latest styles without manual version bumping.

### 🎨 UI/UX & CSS Architecture

- **High-Contrast Theming:** Uses a dark mode palette (`#353535` and `#2a2a2a`) accented with a high-visibility gold (`#f3b50d`). This creates an immediate visual hierarchy that draws the user's eye to the call-to-action (CTA).
- **Flexbox Layouts:** The list elements (`.cup-list li`) utilize `display: flex; justify-content: space-between;` for precise, tabular alignment of labels and phone numbers without relying on outdated HTML tables.
- **Responsive Design:** Contains dedicated media queries (`@media (max-width: 480px)`) that dynamically scale down font sizes (from $1.2$rem to $1.0$rem) and image dimensions, ensuring a perfect fit on narrow viewports without horizontal scrolling.
- **Modern Image Formats:** The default asset is served as a `.webp` file, significantly reducing payload size and improving PageSpeed metrics (SEO performance).

---

## 👨‍💻 Author Information

Created and maintained by **Sina Sotoudeh**.

- 🌐 **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- 🐙 **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- 💼 **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- 📧 **Email:** s.sotoudeh1@gmail.com
