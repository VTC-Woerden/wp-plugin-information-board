<?php

/*
 * Plugin Name: VTC Informatiebord
 */

register_activation_hook(__FILE__, 'create_information_board_page');
register_deactivation_hook(__FILE__, 'disable_information_board_page');
add_filter('page_template', 'load_custom_informationboard_plugin_template');

function create_information_board_page() {
    // Check if page already exists
    $page_title = 'informatiebord';
    $page_slug = 'informatiebord';
    
    if (!get_page_by_path($page_slug)) {
        $page_id = wp_insert_post([
            'post_title'    => $page_title,
            'post_name'     => $page_slug,
            'post_content'  => '', // Can be empty
            'post_status'   => 'publish',
            'post_type'     => 'page',
            'meta_input'    => [
                '_wp_page_template' => 'information-board-template.php'
            ]
        ]);
        
        // Store template file
        if ($page_id) {
            update_option('information_board_page_id', $page_id);
            update_post_meta($page_id, '_wp_page_template', 'information-board-template.php');
        }
    }
}

function disable_information_board_page() {
    // Optional: Remove the page when plugin is deactivated
    $page_id = get_option('information_board_page_id');
    if ($page_id) {
        wp_delete_post($page_id, true);
        delete_option('information_board_page_id');
    }
}

function load_custom_informationboard_plugin_template($template) {
    global $post;
    
    $custom_template = plugin_dir_path(__FILE__) . 'information-board-template.php';
    
    if ($post && $post->ID == get_option('information_board_page_id') && file_exists($custom_template)) {
        return $custom_template;
    }
    
    return $template;
}
