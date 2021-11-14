<?php

/*
 * Plugin Name: Custom shortcode
 * Plugin Uri: https://localhost:8080
 * Description: My first plugin for custom shortcode
 * Version 1.0
 * Author: Jose Alberto Hernnán
 * Author URI: https://localhost:8080
 * License: GPL2
 */

add_action('init', 'jahernan_register_shortcodes');

function jahernan_register_shortcodes()
{
    add_shortcode('rate', 'jahernan_rate');
//    add_shortcode('convert', 'jahernan_convert');
}

function jahernan_rate($args, $content)
{
    return '<b style="color:'.$args['color'].'">' . strtoupper($content) . '</b>';
}

function jahernan_convert($args, $content)
{
    $result = wp_remote_get('https://finance.yahoo.com/d/quotes.csv/s='. $args['from'].$args['to'].'');
    return $result['body'] . ' ' .esc_attr($content) ;
}