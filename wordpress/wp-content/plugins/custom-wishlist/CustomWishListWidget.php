<?php

class CustomWishListWidget extends WP_Widget
{

    public function __construct()
    {
        $widget_options = [
            'className' => 'custom_class',
            'description' => 'Add Items to WishList'
        ];


        parent::__construct('custom_id', 'Custom WishList', $widget_options);
    }

    function form($instance)
    {
        $defaults = ['title' => 'WishList'];
        $instance = wp_parse_args((array)$instance, $defaults);
        $title = esc_attr($instance['title']);
        echo '<p>Title <input class="widefat" name="' . $this->get_field_name('title'). '" type="text" value="' . $title . '"/></p>';
    }

    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    function widget($args, $instance)
    {
        extract($args);
        $title = apply_filters('widget_title', $instance['title']);

        if (is_single()) {
            echo $before_widget;
            echo $before_title . $title . $after_title;

            if (!is_user_logged_in()) {
                echo 'Please sign in to use this widget';
            } else {
                global $post;
                if (isUserAddedPost($post->ID)) {
                    echo 'You want this';
                } else {
                    echo '<span id="custom_add_wishlist_div"><a id="custom_add_wishlist" href="">Add to wishlist</a></span>';
                }
            }

            echo $after_widget;
        }
    }
}