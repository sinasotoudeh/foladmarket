# 🏗️ Sina Steel Comparison Table

![WordPress](https://img.shields.io/badge/WordPress-Plugin-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-Data_Processing-777BB4?logo=php)
![jQuery](https://img.shields.io/badge/jQuery-Dynamic_DOM-0769AD?logo=jquery)
![JSON](https://img.shields.io/badge/Dataset-JSON-lightgray?logo=json)
![Status](https://img.shields.io/badge/Status-Active-success)

**Sina Steel Comparison Table** is a comprehensive, high-performance WordPress plugin designed for industrial and metallurgical websites. It allows users to dynamically compare the chemical and physical properties of various steel grades. Powered by a localized JSON dataset and a dynamic jQuery-driven UI, it offers a seamless, fast, and interactive user experience.

---

## ✨ Key Features

- 📊 **Dynamic Comparison UI:** Interactive popup modal allowing users to select and compare up to 10 distinct steel grades simultaneously.
- 🗄️ **Decoupled Data Source:** Utilizes a structured `steels.json` file for data storage, eliminating heavy database queries and allowing easy mass-updates.
- 📱 **Responsive UX/UI:** Horizontal scrolling (`overflow-x: auto`) for large comparison tables, CSS grid for selection buttons, and zebra-striping for optimal readability.
- 🧩 **Modular Shortcodes:** Four distinct shortcodes providing granular control over data presentation (comparison app, full properties, chemical composition, and physical properties).
- 🧠 **Smart Data Formatting:** Automated Regex parsing to format raw numeric ranges into user-friendly localized text (e.g., converting "1.9-2.2" to "بین 1.9 تا 2.2 درصد").

---

## 🛠️ User-Centric: Installation & Usage

### 📦 Installation

1. Upload the `Sina-steel-comparison` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Ensure the `steels.json` file remains in the plugin's root directory, as it acts as the primary database.

### 💻 Shortcodes & Parameters

You can use the following shortcodes in any Page, Post, or Page Builder (like Elementor).

| Shortcode             | Parameters       | Default | Description                                                                                              |
| :-------------------- | :--------------- | :------ | :------------------------------------------------------------------------------------------------------- |
| `[steel_comparison]`  | _None_           | _None_  | Renders the main interactive comparison application, including the selection modal and dynamic table.    |
| `[steel_properties]`  | `grade` (String) | `""`    | Displays a static, complete HTML table of all properties for a specific steel grade.                     |
| `[steel_composition]` | `grade` (String) | `""`    | Renders an unordered list of purely **chemical elements** (C, Si, Mn, etc.) for the specified grade.     |
| `[steel_physical]`    | `grade` (String) | `""`    | Renders an unordered list of **physical and performance properties** (toughness, wear resistance, etc.). |

**Example Usage:**

```text
[steel_comparison]

[steel_composition grade="1.2080"]
```

---

## ⚙️ Developer-Centric: Architecture & Code Quality

This section outlines the technical decisions, algorithmic logic, and performance optimizations implemented within the plugin, intended for technical reviewers and senior developers.

### 1. 🗄️ Data Architecture & JavaScript Localization

To prevent unnecessary AJAX overhead, the plugin reads the `steels.json` dataset directly during the PHP compilation phase.
Instead of rendering inline `<script>` tags, the dataset is securely bridged to the frontend using `wp_localize_script()`. This binds the entire dataset to the `window.steelData` object, ensuring immediate data availability for the jQuery application without network latency.

### 2. 🧮 Algorithmic Data Formatting & Regex

The plugin employs intelligent parsing logic in `sc_format_chem_value()` to transform raw JSON strings into human-readable formats.
It uses a strict Regular Expression: `/^\s*([0-9]+(?:\.[0-9]+)?)\s*-\s*([0-9]+(?:\.[0-9]+)?)\s*$/u`
Mathematically, if a chemical range is provided as string $S$, the regex extracts $V_{min}$ and $V_{max}$. The output is then formatted into a localized string representing the range $V_{min} \le x \le V_{max}$.

Similarly, the frontend JS enforces a strict selection limit to maintain UI integrity and prevent horizontal layout breaking. The logic guarantees that the set of selected grades $G$ always adheres to the condition $|G| \le 10$.

### 3. 🛡️ Security & Output Escaping

- **Data Sanitization:** All extracted data from the JSON file is treated as untrusted input. The plugin utilizes `esc_html()` extensively before rendering any value to the DOM (e.g., in `sc_format_value()` and `sc_render_properties()`).
- **Shortcode Sandboxing:** All shortcode rendering relies strictly on output buffering (`ob_start()` and `ob_get_clean()`). This prevents premature output execution and ensures shortcodes render exactly where placed in the content flow.

### 4. ⚡ UI/UX Performance Optimization

- **Smart Rendering:** The `steel_composition` and `steel_physical` functions only render list items if the data actually exists (`$formatted !== null && $formatted !== ''`). Empty or null nodes are entirely omitted from the DOM tree.
- **CSS Architecture:** The table uses `border-collapse: separate` combined with `border-radius` and precise `nth-child(even)` pseudo-selectors to create a modern UI without relying on heavy frontend frameworks like Bootstrap.

---

## 👨‍💻 Author Info

- **Name:** Sina Sotoudeh
- **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- **Email:** s.sotoudeh1@gmail.com
