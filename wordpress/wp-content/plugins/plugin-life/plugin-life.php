<?php

/*
 * Plugin name: Custom Tables
 * Plugin URI: https://github.com/joserto86/
 * Description: Create and use custom tables
 * Version: 1.0
 * Author: Jose Alberto Hernán
 * Author URI: GPL2
 */

// 1. activate
function custom_activation_function()
{
    global $wpdb;

    $tablename = $wpdb->prefix . 'hits';
    if ($wpdb->get_var("SHOW TABLES LIKE '" . $tablename . "'" ) != $tablename) {
        $sql = "CREATE TABLE " . $tablename . "(
            hit_id INT NOT NULL AUTO_INCREMENT,
            hit_ip VARCHAR(50) NOT NULL,
            hit_post_id INT NOT NULL,
            hit_date DATETIME,
            PRIMARY KEY (hit_id)
        );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
register_activation_hook(__FILE__, 'custom_activation_function');

function save_hit($content)
{
    if (!is_single()) {
        return $content;
    }

    $postId = get_the_ID();
    $ip = $_SERVER['REMOTE_ADDR'];

    global $wpdb;
    $table = $wpdb->prefix . 'hits';

    $visit = [
        'hit_ip' => $ip,
        'hit_date' => current_time('mysql'),
        'hit_post_id' => $postId
    ];

    $wpdb->insert($table, $visit);
}
add_filter('the_content', 'save_hit');

// 2. deactivate
function custom_deactivation_function()
{
    //do something
}
register_deactivation_hook(__FILE__, 'custom_deactivation_function');

// 3. uninstall
// create file called unistall.php in same path

