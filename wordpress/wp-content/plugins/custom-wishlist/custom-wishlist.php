<?php

/*
 * Plugin Name: Custom WhishList plugin
 * Plugin Uri:
 * Description: Add a wish list widget where registered users can save the posts of the production
 * Version: 1.0
 * Author: Jose Alberto Hernán
 * Author Uri:
 * License GPL2
 */

require_once ('CustomWishListWidget.php');

//load external files
function custom_init()
{
    wp_register_script('custom-wishlist-js', plugins_url('/custom-wishlist.js', __FILE__), ['jquery']);

    wp_enqueue_script('jquery');
    wp_enqueue_script('custom-wishlist-js');

    global $post;

    wp_localize_script('custom-wishlist-js', 'WishList', ['postId' => $post->ID, 'action' => 'add_wishlist']);
}
add_action('wp', 'custom_init');

// add custom ajax action using hook 'wp_ajax'
function add_wishlist()
{
    $postId = (int)$_POST['postId'];
    $user = wp_get_current_user();

    if (!isUserAddedPost($postId)) {
        add_user_meta($user->ID, 'wanted_posts', $postId);
    }

    exit();
}
//add_action('wp_ajax_nopriv_add_wishlist', 'add_wishlist');
add_action('wp_ajax_add_wishlist', 'add_wishlist');

// add widget
function custom_widget_init()
{
    register_widget('CustomWishListWidget');
}
add_action('widgets_init', 'custom_widget_init');

function isUserAddedPost($postId)
{
    $user = wp_get_current_user();
    $values = get_user_meta($user->ID, 'wanted_posts');
    foreach ($values as $value) {
        if ($value == $postId) {
            return true;
        }
    }

    return false;
}

//plugin backend configuration
function custom_plugin_menu()
{
    add_options_page(
        'Custom WhishList Options',
        'Custom WhishList',
        'manage_options',
        'custom-whishlist',
        'plugin_options'
    );
}
add_action('admin_menu', 'custom_plugin_menu');

function custom_admin_init()
{
    register_setting('custom-group', 'custom_dashboard_title');
    register_setting('custom-group', 'custom_number_of_items');
}
add_action('admin_init', 'custom_admin_init');

// add dashboard widget
function custom_create_dashboard_widget()
{
    $title = get_option('custom_dashboard_title') ? get_option('custom_dashboard_title') : 'Custom Wishlist';
    wp_add_dashboard_widget('css_id', $title, 'custom_show_dashboard_widget');
}
add_action('wp_dashboard_setup', 'custom_create_dashboard_widget');

// show dashboard widget
function custom_show_dashboard_widget()
{
    $user = wp_get_current_user();
    $values = get_user_meta($user->ID, 'wanted_posts');

    $limit = (int)get_option('custom_number_of_items') ? get_option('custom_number_of_items') : 4;

    echo '<ul>';
    $count = 0;
    foreach ($values as $value) {
        if ($count < $limit) {
            $currentPost = get_post($value);
            echo '<li>' . $currentPost->post_title. '</li>';
            $count ++;
        }
    }
    echo '</ul>';
}

function plugin_options()
{
    ?>
    <div class="wrap"><?php screen_icon(); ?>
        <h2>Custom WishList</h2>
        <form action="options.php" method="post">
            <?php settings_fields('custom-group') ?>
            <?php @do_settings_fields('custom-group', 'custom-whishlist' ) ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row"><label for="custom_dashboard_title">Dashboard Widget Title</label></th>
                    <td>
                        <input type="text" name="custom_dashboard_title" id="dashboard_title" value="<?php echo get_option('custom_dashboard_title')?>">
                        <br/><small>help text</small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row"><label for="custom_number_of_items">Number of items to show</label></th>
                    <td>
                        <input type="text" name="custom_number_of_items" id="dashboard_title" value="<?php echo get_option('custom_number_of_items')?>">
                        <br/><small>help text</small>
                    </td>
                </tr>
            </table><?php @submit_button() ?>
        </form>
    </div>
    <?php
}