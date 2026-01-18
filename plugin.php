<?php
/**
 * Plugin Name: VTC Informatiebord
 * Description: Information board plugin with sponsor management
 * Version: 1.0.0
 * Author: VTC
 * Text Domain: vtc-informatiebord
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('VTC_INFORMATIEBORD_VERSION', '1.0.0');
define('VTC_INFORMATIEBORD_PATH', plugin_dir_path(__FILE__));
define('VTC_INFORMATIEBORD_URL', plugin_dir_url(__FILE__));

/**
 * Main plugin class
 */
class VTC_Informatiebord_Plugin {
    
    /**
     * Single instance of the class
     */
    private static $instance = null;
    
    /**
     * Get singleton instance
     */
    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor - Initialize plugin
     */
    private function __construct() {
        $this->init_hooks();
        $this->init_classes();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        add_filter('page_template', array($this, 'load_custom_template'));
    }
    
    /**
     * Initialize plugin classes
     */
    private function init_classes() {
        require_once VTC_INFORMATIEBORD_PATH . 'includes/class-sponsor-post-type.php';
        VTC_Sponsor_Post_Type::get_instance();
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        $this->create_information_board_page();
        $this->create_sponsorboard_page();
        
        // Flush rewrite rules after registering post types
        flush_rewrite_rules();
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        $this->disable_information_board_page();
        $this->disable_sponsorboard_page();
        
        // Flush rewrite rules
        flush_rewrite_rules();
    }
    
    /**
     * Create information board page
     */
    private function create_information_board_page() {
        $page_title = 'informatiebord';
        $page_slug = 'informatiebord';
        
        if (!get_page_by_path($page_slug)) {
            $page_id = wp_insert_post(array(
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'meta_input'    => array(
                    '_wp_page_template' => 'information-board-template.php'
                )
            ));
            
            if ($page_id && !is_wp_error($page_id)) {
                update_option('information_board_page_id', $page_id);
                update_post_meta($page_id, '_wp_page_template', 'information-board-template.php');
            }
        }
    }
    
    /**
     * Remove information board page
     */
    private function disable_information_board_page() {
        $page_id = get_option('information_board_page_id');
        if ($page_id) {
            wp_delete_post($page_id, true);
            delete_option('information_board_page_id');
        }
    }
    
    /**
     * Create sponsorboard page
     */
    private function create_sponsorboard_page() {
        $page_title = 'Sponsorboard';
        $page_slug = 'sponsorboard';
        
        if (!get_page_by_path($page_slug)) {
            $page_id = wp_insert_post(array(
                'post_title'    => $page_title,
                'post_name'     => $page_slug,
                'post_content'  => '',
                'post_status'   => 'publish',
                'post_type'     => 'page',
                'meta_input'    => array(
                    '_wp_page_template' => 'sponsorboard-template.php'
                )
            ));
            
            if ($page_id && !is_wp_error($page_id)) {
                update_option('sponsorboard_page_id', $page_id);
                update_post_meta($page_id, '_wp_page_template', 'sponsorboard-template.php');
            }
        }
    }
    
    /**
     * Remove sponsorboard page
     */
    private function disable_sponsorboard_page() {
        $page_id = get_option('sponsorboard_page_id');
        if ($page_id) {
            wp_delete_post($page_id, true);
            delete_option('sponsorboard_page_id');
        }
    }
    
    /**
     * Load custom template for information board page
     */
    public function load_custom_template($template) {
        global $post;
        
        // Load information board template
        $info_board_template = VTC_INFORMATIEBORD_PATH . 'information-board-template.php';
        if ($post && $post->ID == get_option('information_board_page_id') && file_exists($info_board_template)) {
            return $info_board_template;
        }
        
        // Load sponsorboard template
        $sponsorboard_template = VTC_INFORMATIEBORD_PATH . 'sponsorboard-template.php';
        if ($post && $post->ID == get_option('sponsorboard_page_id') && file_exists($sponsorboard_template)) {
            return $sponsorboard_template;
        }
        
        return $template;
    }
}

// Initialize the plugin
VTC_Informatiebord_Plugin::get_instance();
