# ⭐ Sina Product Rating

![WordPress](https://img.shields.io/badge/WordPress-Plugin-blue?logo=wordpress)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php)
![JavaScript](https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?logo=javascript)
![REST API](https://img.shields.io/badge/REST-API-FF69B4?logo=json)
![Status](https://img.shields.io/badge/Status-Active-success)

**Sina Product Rating** is a lightweight, high-performance custom rating system for WordPress products. It features a modern, JavaScript-driven 5-star UI, interacts seamlessly with a custom WordPress REST API endpoint, and stores data efficiently in a dedicated database table to ensure scalability and speed.

---

## ✨ Key Features

- 🚀 **Vanilla JS Frontend:** Fast, responsive UI without heavy jQuery dependencies. Features dynamic hover states and instant feedback.
- 🔌 **Custom REST API:** Uses native WordPress REST API (`/product-rating/v1/rate`) for secure, asynchronous rating submissions.
- 🗄️ **Optimized Database:** Utilizes a custom, indexed table (`wp_product_ratings`) instead of `postmeta` to prevent database bloat and ensure fast aggregation queries.
- ⚡ **Conditional Loading:** Assets (CSS/JS) are strictly loaded only on singular product pages to maximize overall site performance.
- 📱 **Responsive Design:** Auto-scaling star UI adapting seamlessly to mobile viewports (`max-width: 768px`).

---

## 🛠️ User-Centric: Installation & Usage

### 📦 Installation

1. Upload the plugin files to your `/wp-content/plugins/` directory.
2. First, locate and activate the **Product Ratings Installer** plugin from your dashboard. This generates the necessary custom database tables.
3. Next, activate the main **Sina Product Rating** plugin.

### 💻 Shortcodes

Use the following shortcode within your Elementor builder, Gutenberg blocks, or classic editor to render the rating UI.

| Shortcode       | Parameters | Default Context | Description                                                                            |
| :-------------- | :--------- | :-------------- | :------------------------------------------------------------------------------------- |
| `[page_rating]` | _None_     | Current Post ID | Renders the interactive 5-star rating UI, current average score, and total vote count. |

**Example Usage:**

```text
[page_rating]
```

---

## ⚙️ Developer-Centric: Architecture & Code Quality

This section is dedicated to technical reviewers, detailing the plugin's structural logic, database architecture, and API design.

### 1. 🗄️ Database Architecture & Math Logic

Instead of relying on WordPress `postmeta` which scales poorly for repetitive numeric data, the plugin creates a dedicated table via `dbDelta()`:

- **Table Name:** `{$wpdb->prefix}product_ratings`
- **Schema:** Features an auto-incrementing `id`, `post_id`, `rating_value`, and `date_registered`.
- **Indexing:** Utilizes a composite key `idx_post_row (post_id, row_index)` for lightning-fast read operations.

**Mathematical Aggregation:**
Aggregate statistics are computed directly at the SQL level rather than in PHP memory. The average calculation model applied across $N$ total votes is:
$Avg = \frac{\sum_{i=1}^{N} Rating_i}{N}$
The database calculates this using: `ROUND(SUM(rating_value)/COUNT(*), 1)` ensuring precision.

### 2. 🔌 REST API Design

The plugin registers custom endpoints under the `product-rating/v1` namespace.

| Endpoint   | Method | Payload                          | Response                                        |
| :--------- | :----- | :------------------------------- | :---------------------------------------------- |
| `/ratings` | `GET`  | `?post_id={id}`                  | Returns current `{ count, avg }`.               |
| `/rate`    | `POST` | `{"post_id": ID, "rating": 1-5}` | Inserts vote, returns updated `{ count, avg }`. |

### 3. 🛡️ Security & Performance Standards

- **Input Sanitization:** All incoming REST API parameters are strictly cast using `absint()` to prevent injection payloads. Ranges are strictly validated ($1 \le Rating \le 5$).
- **Database Security:** Queries bypass raw concatenation. `wpdb->prepare` is used extensively with `%d` placeholders to eliminate SQL injection vectors.
- **Frontend Optimization:** `wp_localize_script()` is utilized to securely pass configuration variables (like the dynamic REST URL and Post ID) to the Vanilla JS file, eliminating the need for inline `<script>` tags.

---

## 👨‍💻 Author Info

- **Name:** Sina Sotoudeh
- **Website:** [sinasotoudeh.ir](https://sinasotoudeh.ir)
- **GitHub:** [github.com/sinasotoudeh](https://github.com/sinasotoudeh)
- **LinkedIn:** [linkedin.com/in/sinasotoudeh](https://linkedin.com/in/sinasotoudeh)
- **Email:** s.sotoudeh1@gmail.com
