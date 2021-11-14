<?php

/*
 * Plugin Name: Cron email plugin
 * Plugin Uri: https://github.com/joserto86/wp-notes
 * Description: Create a simple cron job. More options: https://codex.wordpress.org/es:Referencia_de_Funciones
 * Version: 1.0
 * Author: Jose Alberto Hernán
 * Author URI: https://github.com/joserto86/
 * License GPL2
 */

function init_cron_job()
{
    if (!wp_next_scheduled('cron_send_mail_hook')) {
        wp_schedule_event(time(), 'hourly', 'cron_send_mail_hook');
    }
}
add_action('init', 'init_cron_job');

function sendmail()
{
    $email = get_bloginfo('admin_email');

    wp_mail($email, 'admin', 'hora de tu medicina!');
}
add_action('cron_send_mail_hook', 'sendmail');
