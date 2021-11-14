<?php

/*
 * Plugin Name: Jahernan Related posts
 * Plugin Uri: https://localhost
 * Description: Second custom plugin for related posts
 * Version: 1.0
 * Author: Jose Alberto Hernán
 * Author URI: https://localhost
 * License: GPL2
 */

add_filter('the_content', 'jahernan_related_posts');

function jahernan_related_posts(string $content)
{
    if (!is_singular('post')) {
        return $content;
    }

    $categories = get_the_terms(get_the_ID(), 'category');
    $categoriesIds = [];

    foreach ($categories as $category) {
        $categoriesIds[] = $category->term_id;
    }

    $loop = new WP_Query([
        'category_in' => $categoriesIds,
        'posts_per_page' => 4,
        'posts__not_in' => [get_the_ID()],
        'order_by' => 'rand'
    ]);

    if ($loop->have_posts()) {
        $content .= 'RELATED POSTS:<br/><ul>';

        while ($loop->have_posts()) {
            $loop->the_post();
            $content .= '<li><a href="'. get_the_permalink().'" >' . get_the_title() . '</a></li>';
        }
        $content .= '</ul>';
    }
    wp_reset_query();

    return $content;
}