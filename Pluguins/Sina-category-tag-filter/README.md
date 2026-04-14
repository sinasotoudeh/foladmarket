# 🏷️ Sina Category-Tag Filter

![Version](https://img.shields.io/badge/Version-1.0-blue.svg)
![WordPress](https://img.shields.io/badge/WordPress-Compatible-21759b.svg?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777bb4.svg?logo=php)
![Elementor](https://img.shields.io/badge/Elementor-Ready-D50057.svg?logo=elementor)

A specialized WordPress plugin designed to seamlessly map product tags to specific category identifiers (`coid`) and provide a dynamic, URL-based tag filtering system for Elementor product archives.

---

## ✨ Key Features

- **Dynamic Tag Mapping:** Automatically maps a custom field (`category_coid`) to a predefined set of product tags.
- **Elementor Integration:** Directly hooks into Elementor's custom query system (`elementor/query/product_tag`) to manipulate product loops.
- **Frontend Filter Buttons:** Generates clean, clickable HTML filter buttons that append query arguments (`?tag_filter=...`) to the URL.
- **Persian Localization:** Built-in dictionary for mapping English tag slugs to Persian frontend labels (e.g., `hotwork` → `فولادهای ابزاری گرمکار`).
- **Infinite Loading:** Forces the query to return all matching products ($posts\_per\_page = -1$).

---

## 🛠️ User Guide (Installation & Usage)

### 1. Prerequisites

Ensure you have the following active on your WordPress site:

- **WooCommerce** (for `product` post type).
- **Elementor Pro** (to utilize the custom query hook).
- A custom meta field named `category_coid` assigned to your category pages/archives.

### 2. Usage & Shortcode

To display the filter buttons on your frontend Elementor template, use the following shortcode:

```text
[category_tag_filter]
```

### ⚙️ Shortcode Context & Mapping Rules

The plugin relies on the meta value of `category_coid` of the current page. Below is a simplified representation of the mapping structure:

| `category_coid` Value | Associated Tag Slugs             | Persian Output Example |
| :-------------------- | :------------------------------- | :--------------------- |
| `abzari`              | `hotwork`, `coldwork`, `hss`     | فولادهای ابزاری گرمکار |
| `rangi`               | `aluminum`, `brass`, `copper`... | آلومینیوم, برنج        |
| `sakhtemani`          | `s-profile`, `s-tube`, `s-plate` | پروفیل های گالوانیزه   |

When a user clicks a filter, the URL updates (e.g., `?tag_filter=hotwork`), and the Elementor widget displaying the products will refresh to show only items matching that specific tag.

---

## 🏗️ Architecture & Code Quality (For Reviewers)

This section outlines the technical implementation, security practices, and performance characteristics of the codebase.

### 🛡️ Security & Data Sanitization

- **Input Sanitization:** The plugin strictly sanitizes URL parameters using `sanitize_text_field( $_GET['tag_filter'] )` to prevent XSS and injection attacks.
- **Output Escaping:** All dynamically generated HTML attributes and visible texts are secured using native WordPress functions (`esc_url()` and `esc_html()`).
- **Validation:** The code verifies the existence of the mapping array keys using `isset()` and `in_array()` before modifying database queries, preventing fatal errors on undefined `coid` variables.

### ⚡ Performance Optimization

- **Centralized Mapping Array:** The dictionary function `fm_get_tag_mapping()` utilizes native PHP associative arrays, guaranteeing an $O(1)$ lookup time complexity when retrieving tags for a specific category ID.
- **Direct Query Manipulation:** Instead of running secondary loops or filtering via JavaScript, the plugin modifies the primary Elementor `WP_Query` arguments before database execution (`$query->set( 'tag_slug__in', $filter )`).
- **Mathematical Query Limit:** The query enforces $posts\_per\_page = -1$ to ensure all relevant items within the subset are fetched in a single database trip, bypassing pagination complexities for specific product groupings.

### 🧩 Elementor Hook System

The logic binds to `elementor/query/product_tag`. By assigning this specific query ID (`product_tag`) to the Elementor Posts/Products widget in the UI, the plugin safely isolates its query alterations from affecting other standard WordPress loops on the page.

---

## 👨‍💻 Author Information

Created and maintained by **Sina Sotoudeh**.

- 🌐 **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- 🐙 **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- 💼 **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- 📧 **Email:** s.sotoudeh1@gmail.com
