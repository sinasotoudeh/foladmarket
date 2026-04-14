# 📂 Sina Subcategory Dropdown

![WordPress](https://img.shields.io/badge/WordPress-Plugin-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?logo=javascript)
![CSS3](https://img.shields.io/badge/CSS3-Styling-1572B6?logo=css3)
![Status](https://img.shields.io/badge/Status-Active-success)

**Sina Subcategory Dropdown** is a lightweight and context-aware WordPress plugin designed to improve navigation on category archive pages. It automatically detects the current category and dynamically renders an accessible, two-level deep subcategory dropdown menu, complete with aggregated post counts for both parent and child terms.

---

## ✨ Key Features

- 🌳 **Hierarchical Display:** Automatically queries and maps subcategories up to two levels (`depth = 2`) based on the current active category.
- 🧮 **Aggregated Counts:** Dynamically calculates and displays the total post count for parent categories by summing up their children's post counts.
- ♿ **Web Accessibility (a11y):** Built with accessibility in mind, utilizing `role="menu"`, `aria-haspopup="true"`, and dynamic `aria-expanded` toggles via JavaScript.
- ⚡ **Zero Dependencies:** Pure Vanilla JS DOM manipulation without the need for jQuery, ensuring maximum performance.
- 🎯 **Context-Aware Loading:** Assets (CSS/JS) and logic are strictly executed only on taxonomy archive pages (`is_category()`).

---

## 🛠️ User-Centric: Installation & Usage

### 📦 Installation

1. Upload the `Sina-subcategory-dropdown` folder to your `/wp-content/plugins/` directory.
2. Activate the plugin through the **Plugins** menu in WordPress.
3. Place the shortcode in your category archive template or widget area.

### 💻 Shortcodes & Parameters

| Shortcode           | Parameters | Default Context        | Description                                                                                             |
| :------------------ | :--------- | :--------------------- | :------------------------------------------------------------------------------------------------------ |
| `[subcat_dropdown]` | _None_     | `get_queried_object()` | Renders the subcategory dropdown. Must be used on a Category Archive page; otherwise, it returns empty. |

**Example Usage:**

```text
[subcat_dropdown]
```

---

## ⚙️ Developer-Centric: Architecture & Code Quality

This section outlines the technical decisions, algorithmic logic, and performance optimizations implemented within the plugin.

### 1. 🧮 Tree Algorithmic Logic & Math

The plugin avoids making multiple database queries for nested children. Instead, it queries all child terms simultaneously using `get_terms()` with `child_of` and `depth => 2`, then restructures them into a tree array in PHP.

To calculate the precise post count for the first-level categories, the plugin iterates through the tree and aggregates the counts. The mathematical model applied to each parent node $P$ is:
$Total_P = Count_P + \sum_{i=1}^{N} Count_{Child_i}$
This ensures users see the accurate total volume of posts contained within the parent and its direct descendants.

### 2. ⚡ Performance Optimizations

- **Targeted Enqueuing:** The `wp_enqueue_scripts` hook immediately returns if `! is_category()`, guaranteeing zero asset bloat on unrelated pages.
- **Cache Busting:** The CSS file utilizes dynamic versioning based on file modification time: `filemtime($css)`. This prevents browser caching issues during active development or updates.
- **Vanilla JS:** DOM queries like `document.addEventListener('click')` are handled without heavy libraries, ensuring quick parsing and minimal memory footprint.

### 3. 🛡️ Security & Output Sanitization

All outputs retrieved from the database are strictly escaped to prevent Cross-Site Scripting (XSS) vulnerabilities:

- URLs are validated using `esc_url( get_term_link( $t ) )`.
- Category names are safely rendered via `esc_html( $t->name )`.
- Numeric values (counts) are explicitly cast using `intval( $node['sum'] )` before output.

---

## 👨‍💻 Author Info

- **Name:** Sina Sotoudeh
- **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- **Email:** s.sotoudeh1@gmail.com
