<?php

/*
 * Plugin Name: My first plugin
 * Plugin Uri: localhost
 * Description: My first custom plugin in wordpress
 * Version: 1.0
 * Author: Jose Alberto Hernán
 * Author URI: localhost
 * License: GPL2
 */


add_action('add_meta_boxes', 'jahernan_metaboxes');
add_action('save_post', 'jahernan_metaboxes_save');


function jahernan_metaboxes()
{
    add_meta_box('custom_metabox', 'my custom box', 'jahernan_handler', 'post');
}

function jahernan_handler()
{
    $values = get_post_custom($post->ID);
    $contentValue = esc_attr($values['jahernan_field'][0]);
    echo '<label for="jahernan_field">jahernan custom field</label>' .
        '<input type="text" id="jahernan_field" name="jahernan_field" value="'.$contentValue.'"/>';
}

function jahernan_metaboxes_save($postId)
{
    // prevent autosave post editing
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // check if current user can edit post
    if (!current_user_can('edit_post')) {
        return;
    }

    if (isset($_POST['jahernan_field'])) {
        update_post_meta($postId, 'jahernan_field', ($_POST['jahernan_field']));
    }
}

function jahernan_widget_init()
{
    register_widget('JahernanWidget');
}
add_action('widgets_init', 'jahernan_widget_init');

/**
 * Widget Class
 */
class JahernanWidget extends WP_Widget
{
    public function __construct()
    {
        $widget_options = [
            'classname' => 'jahernan', //to css
            'description' => 'show a Youtube video from post meta data'
        ];

        parent::__construct('jahernan_id', 'Youtube videoxx', $widget_options);
    }
    

    /** Como se muestra el formulario de opciones dentro del backend */
    function form($instance)
    {
        $defaults = ['title' => 'YouTube videoxx'];
        $instance = wp_parse_args((array) $instance, $defaults);

        $title = esc_attr($instance['title']);

        echo '<p>Title: <input class="widefat" name="' . $this->get_field_name('title') . '" type="text" value="' . $title .'"/></p>';
    }

    /** Guardar el form */
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);

        return $instance;
    }

    /** show the widget */
    function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);

        //show only in single_post
        if (is_single()) {
            echo $before_widget;
            echo $before_title . $title . $after_title;

            $content = esc_url(get_post_meta(get_the_ID(), 'jahernan_field', true));

            //embed video
            echo '<iframe 
                    width="200" 
                    height="200" 
                    src="https://www.youtube.com/embed/' . $this->get_ytube_id($content). '"
                    frameborder=0
                    allowfullscren
                />';

            echo $after_widget;
        }
    }

    function get_ytube_id($youtubeLink)
    {
        parse_str(parse_url($youtubeLink, PHP_URL_QUERY), $result);
        return $result['v'];
    }
}