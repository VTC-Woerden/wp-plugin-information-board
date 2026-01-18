<?php
/**
 * Sponsor Post Type Class
 * 
 * Handles registration and management of the Sponsor custom post type
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class VTC_Sponsor_Post_Type {
    
    /**
     * Single instance of the class
     */
    private static $instance = null;
    
    /**
     * Post type slug
     */
    const POST_TYPE = 'sponsor';
    
    /**
     * Taxonomy slug
     */
    const TAXONOMY = 'sponsor_type';
    
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
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_action('init', array($this, 'register_post_type'));
        add_action('init', array($this, 'register_taxonomy'));
    }
    
    /**
     * Register the Sponsor post type
     */
    public function register_post_type() {
        $labels = array(
            'name'                  => __('Sponsoren', 'vtc-informatiebord'),
            'singular_name'         => __('Sponsor', 'vtc-informatiebord'),
            'menu_name'             => __('Sponsoren', 'vtc-informatiebord'),
            'name_admin_bar'        => __('Sponsor', 'vtc-informatiebord'),
            'add_new'               => __('Nieuwe toevoegen', 'vtc-informatiebord'),
            'add_new_item'          => __('Nieuwe Sponsor toevoegen', 'vtc-informatiebord'),
            'new_item'              => __('Nieuwe Sponsor', 'vtc-informatiebord'),
            'edit_item'             => __('Sponsor bewerken', 'vtc-informatiebord'),
            'view_item'             => __('Sponsor bekijken', 'vtc-informatiebord'),
            'all_items'             => __('Alle Sponsoren', 'vtc-informatiebord'),
            'search_items'          => __('Sponsoren zoeken', 'vtc-informatiebord'),
            'not_found'             => __('Geen sponsoren gevonden', 'vtc-informatiebord'),
            'not_found_in_trash'    => __('Geen sponsoren gevonden in prullenbak', 'vtc-informatiebord')
        );
        
        $args = array(
            'labels'                => $labels,
            'public'                => true,
            'publicly_queryable'    => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'rewrite'               => array('slug' => 'sponsor'),
            'capability_type'       => 'post',
            'has_archive'           => true,
            'hierarchical'          => false,
            'menu_position'         => 5,
            'menu_icon'             => 'dashicons-awards',
            'supports'              => array('title', 'thumbnail'),
            'show_in_rest'          => true,
        );
        
        register_post_type(self::POST_TYPE, $args);
    }
    
    /**
     * Register the Sponsor Type taxonomy
     */
    public function register_taxonomy() {
        $labels = array(
            'name'              => __('Sponsor Types', 'vtc-informatiebord'),
            'singular_name'     => __('Sponsor Type', 'vtc-informatiebord'),
            'search_items'      => __('Zoek Sponsor Types', 'vtc-informatiebord'),
            'all_items'         => __('Alle Sponsor Types', 'vtc-informatiebord'),
            'parent_item'       => __('Parent Sponsor Type', 'vtc-informatiebord'),
            'parent_item_colon' => __('Parent Sponsor Type:', 'vtc-informatiebord'),
            'edit_item'         => __('Sponsor Type bewerken', 'vtc-informatiebord'),
            'update_item'       => __('Sponsor Type bijwerken', 'vtc-informatiebord'),
            'add_new_item'      => __('Nieuwe Sponsor Type toevoegen', 'vtc-informatiebord'),
            'new_item_name'     => __('Nieuwe Sponsor Type Naam', 'vtc-informatiebord'),
            'menu_name'         => __('Sponsor Types', 'vtc-informatiebord'),
        );
        
        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'sponsor-type'),
            'show_in_rest'      => true,
        );
        
        register_taxonomy(self::TAXONOMY, array(self::POST_TYPE), $args);
        
        // Create default terms
        $this->create_default_terms();
    }
    
    /**
     * Create default sponsor type terms
     */
    private function create_default_terms() {
        $terms = array(
            array(
                'name' => 'Normale Sponsor',
                'slug' => 'normale-sponsor'
            ),
            array(
                'name' => 'Hoofd Sponsor',
                'slug' => 'hoofd-sponsor'
            )
        );
        
        foreach ($terms as $term) {
            if (!term_exists($term['name'], self::TAXONOMY)) {
                wp_insert_term($term['name'], self::TAXONOMY, array(
                    'slug' => $term['slug']
                ));
            }
        }
    }
    

    
    /**
     * Get sponsor logo URL
     * 
     * @param int $post_id
     * @param string $size Image size (thumbnail, medium, large, full)
     * @return string|false
     */
    public static function get_logo_url($post_id, $size = 'full') {
        return get_the_post_thumbnail_url($post_id, $size);
    }
    
    /**
     * Get sponsors by type
     * 
     * @param string $type Taxonomy term slug (normale-sponsor or hoofd-sponsor)
     * @param array $args Additional query arguments
     * @return WP_Query
     */
    public static function get_sponsors_by_type($type = '', $args = array()) {
        $default_args = array(
            'post_type'      => self::POST_TYPE,
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC'
        );
        
        if (!empty($type)) {
            $default_args['tax_query'] = array(
                array(
                    'taxonomy' => self::TAXONOMY,
                    'field'    => 'slug',
                    'terms'    => $type,
                )
            );
        }
        
        $query_args = wp_parse_args($args, $default_args);
        
        return new WP_Query($query_args);
    }
}
