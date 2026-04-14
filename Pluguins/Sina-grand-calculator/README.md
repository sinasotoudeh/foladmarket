# 🧮 Sina Grand Calculator

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-Compatible-informational.svg?logo=wordpress)
![WooCommerce](https://img.shields.io/badge/WooCommerce-Required-success.svg?logo=woocommerce)
![License](https://img.shields.io/badge/license-MIT-green.svg)

**Sina Grand Calculator** is an advanced, specialized WordPress plugin designed for calculating the weight and real-time price of metallic and polymeric sections. Featuring a multi-stage wizard, dynamic input generation, and seamless **WooCommerce integration**, it provides a highly interactive and professional experience for industrial and commercial websites.

## 🧑‍💻 1. User-Centric Documentation

### ✨ Key Features

- **Multi-Stage Wizard UI:** A step-by-step process preventing user confusion (Material Selection ➔ Dimensions ➔ Results).
- **Dynamic Inputs:** Input fields (length, width, thickness, etc.) are generated dynamically based on the selected geometric shape.
- **Vast Material Support:** Supports metals (Iron, Steel, Aluminum) and polymers (Teflon, Polyethylene) across 8 standard industrial shapes (bars, sheets, pipes, etc.).
- **Real-Time WooCommerce Pricing:** Fetches live product prices directly from the WooCommerce database.
- **User Preference Memory:** Saves the user's last selected material and shape locally in the browser.

### 🛑 Prerequisites

To prevent fatal errors during AJAX price calculations, the following plugin **must** be installed and activated:

- **WooCommerce**

### 🚀 Installation

1. Download the plugin folder `Sina-grand-calculator`.
2. Upload the folder to your WordPress plugins directory: `wp-content/plugins/`.
3. Navigate to the **Plugins** menu in your WordPress dashboard.
4. Locate **Sina Grand Calculator** and click **Activate**.

### 💻 Usage & Shortcodes

To display the calculator on any page, post, or widget, simply use the following shortcode:

```text
[grand_calculator]
```

**Shortcode Parameters:**
Currently, the shortcode operates without additional parameters, rendering the full UI directly via the template file.

| Shortcode            | Default Output     | Description                                                                 |
| :------------------- | :----------------- | :-------------------------------------------------------------------------- |
| `[grand_calculator]` | Full Calculator UI | Renders the 3-stage wizard for material, dimensions, and price calculation. |

---

## 🏗️ 2. Developer/Reviewer-Centric: Architecture & Code Quality

This section is dedicated to technical reviewers and senior developers evaluating the codebase for object-oriented design, security, performance, and maintainability.

### 📂 System Architecture

The plugin follows a modular WordPress architecture:

- `Sina-grand-calculator.php`: The **Core Controller**. Handles asset enqueuing, shortcode registration (`ob_start()` / `ob_get_clean()` for safe loading), and registers WooCommerce AJAX endpoints.
- `templates/grand-calculator-view.php`: The **View Layer**. Contains the multi-stage HTML structure utilizing `<section id="stage-X">`.
- `assets/js/grand-calculator.js`: The **Logic Layer**. Handles DOM events, dynamic inputs, math formulas, and AJAX requests.
- `assets/css/grand-calculator.css`: The **Presentation Layer**. A pure Vanilla CSS dark-themed styling system.

### ⚡ Performance Optimization

- **Conditional Asset Loading:** JS and CSS files are _only_ enqueued when the shortcode is present on the page.
- **Cache Busting:** Uses `filemtime()` in `wp_enqueue_script` and `wp_enqueue_style` to prevent browser caching issues during updates.
- **In-Memory Caching:** The JS utilizes a `productPriceCache` object to prevent redundant AJAX calls to the server, significantly boosting performance.

### 🧮 JavaScript & Mathematical Logic

The mathematical core operates in real-time. Calculations are based on specific business rules:

1.  **Unit Conversion:** Inputs (cm, inches, feet) are converted to millimeters natively.
2.  **Volume Calculation:** Example for a cylinder:
    $$V = \pi \times r^2 \times h$$
    The volume is then converted to cubic meters (divided by $1 \times 10^9$).
3.  **Raw Weight:** Volume is multiplied by the specific material's density ($\rho$):
    $$Weight_{raw} = V \times \rho$$
4.  **Business Rule (Weight Reduction):** The script applies a strict `reductionPercent = 2` (2% reduction) to the final weight:
    $$Weight_{final} = Weight_{raw} \times (1 - 0.02)$$

### 🛒 WooCommerce Integration (AJAX)

The communication between JS and WP backend is handled via standard `wp_ajax_` hooks.

- **Data Mapping:** The `productMap` array maps `Material|Shape` combinations to specific WooCommerce Product IDs.
- **Price Calculation:**
  $$TotalPrice = Weight_{final} \times UnitPrice$$
  _Note:_ Prices fetched from the database are assumed to be in **Rial** and are converted to **Toman** on the frontend by dividing by $10$.
- **Regex Processing:** Uses regex (`\d+\.\d{3,4}`) to dynamically extract alloy standard names (e.g., _1.2080_) from WooCommerce product titles.

### 🎨 UI/UX & Design System

- **Responsive Fluid Layout:**
  - **Mobile ($< 768px$):** Stacked, multi-stage wizard with navigation buttons.
  - **Desktop ($> 1024px$):** Unifies into a seamless three-column layout utilizing $28\%$ and $72\%$ width splits.
- **Smart Stage Locking (`.disabled-stage`):** Prevents users from accessing dimension inputs before selecting a material. Uses `grayscale(60%)` and `blur(3px)` with a lock pseudo-element overlay.
- **High Contrast Dark Theme:** Built on the `IRANSans` font family, utilizing `#0b0b0b` as the base and `#EEC92E` as the primary accent color.
- **Scope Isolation:** All CSS rules are encapsulated within `.calculator-container` to prevent theme conflicts.

---

## 👨‍💼 Author Info

This plugin was designed and developed with a focus on performance, standard coding practices, and exceptional user experience.

- **Author:** Sina Sotoudeh
- **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- **Email:** [s.sotoudeh1@gmail.com](mailto:s.sotoudeh1@gmail.com)
