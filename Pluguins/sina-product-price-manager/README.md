# 🌟 Sina Product Price Manager (Mahin Price)

![WordPress](https://img.shields.io/badge/WordPress-Plugin-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php)
![ACF](https://img.shields.io/badge/ACF-Integration-46B450?logo=wordpress)
![PhpSpreadsheet](https://img.shields.io/badge/PhpSpreadsheet-Excel-1D6F42?logo=microsoftexcel)
![Status](https://img.shields.io/badge/Status-Active-success)

**Sina Product Price Manager** (مهین پرایس) is a robust, custom-built WordPress plugin designed to automate and manage complex product pricing based on daily currency fluctuations (USD to IRR). It integrates seamlessly with Advanced Custom Fields (ACF) and provides powerful tools for bulk updates, custom price adjustments, and comprehensive Excel exporting.

---

## ✨ Key Features

- 💱 **Automated Currency Fetching:** Securely fetches daily USD rates via external REST APIs and logs historical data (Today vs. Yesterday).
- 🤖 **Algorithmic Price Updates:** Automatically calculates and applies price deltas to all product repeater fields based on currency trends.
- 🎯 **Granular Manual Control:** Allows administrators to apply custom mathematical deltas (positive or negative) to specific products.
- 📊 **Advanced Excel Exporting:** Utilizes `PhpSpreadsheet` to generate highly detailed, multi-sheet `.xlsx` files containing all product technical data and pricing.
- 🔒 **Highly Secure:** Strict capability checks, robust nonce verification, and comprehensive input sanitization for all admin actions.

---

## 🛠️ User-Centric: Installation & Usage

### 📦 Installation

1. Clone or download the repository into your `/wp-content/plugins/` directory.
2. Since this plugin relies on `PhpOffice\PhpSpreadsheet`, ensure you run Composer to install dependencies:
   ```bash
   composer install
   ```
3. Navigate to the **Plugins** screen in WordPress and activate **Sina Product Price Manager**.
4. A new menu named **مهین پرایس** (Mahin Price) will appear in your admin dashboard.

### 💻 Dashboard Submenus & Actions

| Menu Item              | Action / Functionality | Description                                                                                             |
| :--------------------- | :--------------------- | :------------------------------------------------------------------------------------------------------ |
| **آپدیت نرخ دلار**     | Update Dollar Rate     | Fetches the latest USD price from the API. Automatically shifts the current rate to "Yesterday's Rate". |
| **آپدیت قیمت محصولات** | Bulk Price Update      | Scans all products with ACF `product_rows` and applies the automated price delta.                       |
| **تغییر قیمت سفارشی**  | Custom Delta Update    | Select a specific product and apply a manual $\pm$ delta value to its prices.                           |
| **اکسپورت اکسل**       | Excel Export           | Compiles all product data into an `.xlsx` file, providing a direct download link.                       |

---

## ⚙️ Developer-Centric: Architecture & Code Quality

This section outlines the plugin's underlying architecture, algorithmic logic, and security measures for technical reviewers and maintainers.

### 1. 🔄 Algorithmic Pricing Logic & Math

The plugin uses a strict conditional mathematical model to prevent pricing errors during currency shifts. When running the bulk update, the system compares today's rate ($T$) with yesterday's rate ($Y$).
A fixed delta base ($D_{base}$) is set to $7500$. The actual applied delta ($\Delta$) is calculated as:

- If $T > Y$, then $\Delta = +7500$
- If $T < Y$, then $\Delta = -7500$
- If $T = Y$, then $\Delta = 0$

The calculation applied to each row's price ($P$) inside the ACF array is:
$$ P*{new} = P*{old} + \Delta $$
Example: If the old price is $150000$ and the currency goes up, the calculation is $150000 + 7500 = 157500$. This prevents floating-point inaccuracies using `number_format( $new, 0, '.', '' )`.

### 2. 📡 API & State Management

- **External Requests:** Uses native `wp_remote_get()` with a strict $10$-second timeout to fetch currency data.
- **Error Handling:** Implements comprehensive logging using `is_wp_error()`, HTTP status code verification, and JSON payload validation before writing to the database.
- **Data Persistence:** Uses the standard `update_option()` API for state management (`daily_dollar_today_rate` and `daily_dollar_yesterday_rate`).

### 3. 🛡️ Security & Performance Standards

- **Authorization:** Every admin page explicitly enforces `current_user_can('manage_options')` to restrict access to administrators.
- **CSRF Protection:** Form submissions are heavily guarded using `wp_nonce_field()` and verified via `check_admin_referer()`.
- **Data Sanitization:** Strict type-casting is used for inputs. IDs are sanitized via `absint()` and deltas via `intval()`.
- **Memory Management:** The Excel export script modifies large datasets. It intelligently creates separate worksheets for each product ($SheetTitle \le 31$ chars) to organize data efficiently and uses WP Filesystem constants (`wp_upload_dir`) to save the output securely.

### 4. 🧩 Dependency & Architecture Setup

The plugin relies on modern PHP standards and requires Composer (`vendor/autoload.php`) to load the `PhpSpreadsheet` library. It interacts deeply with **Advanced Custom Fields (ACF)**, directly manipulating complex `get_field` and `update_field` arrays by passing variables by reference (`&$row`) to ensure high-performance array mutations without memory duplication.

---

## 👨‍💻 Author Info

- **Name:** Sina Sotoudeh
- **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- **Email:** s.sotoudeh1@gmail.com
