# 🧮 Sina Cost Calculator

![Version](https://img.shields.io/badge/Version-1.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-Compatible-21759b.svg?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4.svg?logo=php)
![JavaScript](https://img.shields.io/badge/Vanilla-JS-F7DF1E.svg?logo=javascript)

An advanced, highly dynamic WordPress plugin designed for WooCommerce or custom product pages. It provides a real-time weight, price, and cutting-cost calculator for industrial metal products (like steel and iron round bars or rectangular plates). It leverages ACF (Advanced Custom Fields) data to compute complex manufacturing costs instantly on the frontend.

---

## ✨ Key Features

- **Real-Time Computation:** Instantly calculates weight, total price, and specific cutting costs as the user types, using Vanilla JavaScript.
- **Dynamic Shape Selection:** UI dynamically adapts input fields based on the selected profile shape (Round Bar vs. Rectangular/Flat Bar).
- **Complex Pricing Matrix:** Includes a multi-dimensional lookup table to determine cutting costs based on material type (`steel`, `iron`), shape, and precise millimeter dimensions.
- **Smart Mobile Placement:** Features a JS-driven DOM mover that automatically relocates the calculator widget to an optimal viewport position on mobile devices (`< 768px`).
- **Conditional Asset Loading:** Scripts and stylesheets are strictly loaded only on single product pages, achieving zero performance bloat on the rest of the site.

---

## 🛠️ User Guide (Installation & Usage)

### 1. Prerequisites

To fully utilize this calculator, your WordPress environment must have:

- A custom post type of `product` (e.g., WooCommerce).
- **Advanced Custom Fields (ACF)** configured with the following fields attached to your products:
  - `density` (Number): The material density (Defaults to $7840$ if empty).
  - `material` (Text): The material type. Accepted values: `Steel`, `Iron`, or `Metal`.

### 2. Usage & Shortcode

Place the following shortcode anywhere in your single product template (or page builder):

```text
[my_calculator]
```

### ⚙️ Input Parameters & Logic

| Input Field        | Unit  | Applicable Shape | Description                                                  |
| :----------------- | :---- | :--------------- | :----------------------------------------------------------- |
| **Diameter**       | mm    | Round Bar        | Used to calculate the circular cross-section area.           |
| **Width & Height** | mm    | Rectangular      | Used to calculate the rectangular cross-section area.        |
| **Length**         | mm    | Both             | Defines the total length of the piece to calculate volume.   |
| **Quantity**       | #     | Both             | Multiplier for the final weight, total price, and cut costs. |
| **Price per Kg**   | Toman | Both             | Base market price applied to the calculated weight.          |

---

## 🏗️ Architecture & Code Quality (For Reviewers)

This section details the internal engineering, mathematical logic, and optimizations applied to ensure high performance and maintainability.

### 🛡️ PHP & Data Integration

- **Localized Scripting:** The plugin elegantly bridges backend database values (ACF) with frontend logic using `wp_localize_script()`. The multidimensional `$costTable` and product `density` are passed directly into the JS `calcData` object, preventing unnecessary AJAX calls.
- **Output Buffering & Escaping:** The shortcode logic employs `ob_start()` for DOM placement integrity and `esc_attr()` when injecting PHP variables into HTML data attributes, mitigating XSS vulnerabilities.
- **Targeted Enqueuing:** Assets are wrapped in `if ( is_singular(['product']) )`. This $O(1)$ check ensures the calculator's payload is absent from the homepage and blog, improving overall site SEO and PageSpeed.
- **Cache-Busting:** The JavaScript file utilizes `filemtime($js_path)` as its version number, guaranteeing that users instantly receive updated logic without clearing their browser cache.

### 🧮 JavaScript Logic & Mathematical Models

The JavaScript engine computes complex volume metrics by converting millimeters to meters for standard metric density formulas ($kg/m^3$).

- **Round Bar Volume Formula:**
  $Volume = \pi \times (\frac{Diameter}{2000})^2 \times (\frac{Length}{1000})$
- **Round Bar Cutting Cost Formula:**
  $CutPrice = (\frac{Diameter}{20})^2 \times \pi \times CostPerCm$
- **Event Delegation & State:** Uses `oninput` event listeners for instantaneous feedback. The logic accounts for edge cases, safely returning `-` or specific warnings (e.g., "هوابرش") if the material is generic metal or inputs are `NaN`.

### 🎨 UI/UX & Responsive Engineering

- **Color Psychology & Hierarchy:** The UI utilizes a dark `#353535` base with high-contrast `#f3b50d` accents. Result frames use specific semantic background colors (Blue for Weight, Yellow for Price, Gray for Cutting) to help users scan data effortlessly.
- **DOM Repositioning (Mobile SEO):** Instead of relying on CSS `display: none` (which renders duplicate DOM nodes and hurts SEO), an IIFE (Immediately Invoked Function Expression) monitors the `window.innerWidth`. If the screen is `< 768px`, the JS physically relocates the `.product-weight-calculator` node into a mobile-friendly `#calculator-placeholder` container.

---

## 👨‍💻 Author Information

Created and maintained by **Sina Sotoudeh**.

- 🌐 **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- 🐙 **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- 💼 **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- 📧 **Email:** s.sotoudeh1@gmail.com
