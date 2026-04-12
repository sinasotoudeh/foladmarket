<?php
/**
 * Plugin Name: Product Ratings Installer
 * Description: Creates the product_ratings table on activation.
 * Version: 1.0
 * Author: Sina Sotoudeh
 */
register_activation_hook(__FILE__, 'create_product_ratings_table');
function create_product_ratings_table() {
    global $wpdb;
    $table_name      = $wpdb->prefix . 'product_ratings';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE {$table_name} (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        post_id BIGINT(20) UNSIGNED NOT NULL,
        row_index INT(11) NOT NULL,
        rating_value TINYINT(1) NOT NULL,
        date_registered DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY idx_post_row (post_id, row_index)
    ) {$charset_collate};";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}
