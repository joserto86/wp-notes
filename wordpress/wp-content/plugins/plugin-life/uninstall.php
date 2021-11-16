<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    return;
}

//uninstall it
global $wpdb;

$tablename = $wpdb->prefix . 'hits';

if ($wpdb->get_var("SHOW TABLES LIKE '$tablename'") == $tablename) {
    $sql = "DROP TABLE ". $tablename . ";";
    $wpdb->query($sql);
}