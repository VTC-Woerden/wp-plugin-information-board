<?php
/**
 * Sponsor Shortcodes Class
 * 
 * Handles shortcodes for displaying sponsors
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

class VTC_Sponsor_Shortcodes {
    
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
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }
    
    /**
     * Initialize WordPress hooks
     */
    private function init_hooks() {
        add_shortcode('sponsors_grid', array($this, 'render_sponsors_grid'));
        add_shortcode('sponsors_rotating', array($this, 'render_sponsors_rotating'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }
    
    /**
     * Enqueue frontend scripts and styles
     */
    public function enqueue_scripts() {
        if ($this->has_sponsor_shortcode()) {
            wp_enqueue_style(
                'vtc-sponsors-styles',
                VTC_INFORMATIEBORD_URL . 'assets/css/sponsors.css',
                array(),
                VTC_INFORMATIEBORD_VERSION
            );
            
            wp_enqueue_script(
                'vtc-sponsors-rotating',
                VTC_INFORMATIEBORD_URL . 'assets/js/sponsors-rotating.js',
                array('jquery'),
                VTC_INFORMATIEBORD_VERSION,
                true
            );
        }
    }
    
    /**
     * Check if current page has sponsor shortcode
     */
    private function has_sponsor_shortcode() {
        global $post;
        
        if (is_a($post, 'WP_Post')) {
            return has_shortcode($post->post_content, 'sponsors_grid') || 
                   has_shortcode($post->post_content, 'sponsors_rotating');
        }
        
        return false;
    }
    
    /**
     * Render sponsors grid shortcode
     * Usage: [sponsors_grid columns="6" type=""]
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function render_sponsors_grid($atts) {
        $atts = shortcode_atts(array(
            'columns' => '6',
            'type' => '', // normale-sponsor, hoofd-sponsor, or empty for all
        ), $atts);
        
        $sponsors = VTC_Sponsor_Post_Type::get_sponsors_by_type($atts['type']);
        
        if (!$sponsors->have_posts()) {
            return '<p>' . esc_html__('Geen sponsors beschikbaar', 'vtc-informatiebord') . '</p>';
        }
        
        ob_start();
        ?>
        <div class="vtc-sponsors-grid" data-columns="<?php echo esc_attr($atts['columns']); ?>">
            <?php while ($sponsors->have_posts()): $sponsors->the_post(); ?>
                <?php $logo_url = VTC_Sponsor_Post_Type::get_logo_url(get_the_ID(), 'medium'); ?>
                
                <?php if ($logo_url): ?>
                    <div class="vtc-sponsor-item">
                        <img src="<?php echo esc_url($logo_url); ?>" 
                             alt="<?php echo esc_attr(get_the_title()); ?>" 
                             class="vtc-sponsor-logo" />
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        </div>
        <?php
        
        return ob_get_clean();
    }
    
    /**
     * Render rotating sponsors shortcode
     * Usage: [sponsors_rotating per_row="6" interval="5" type=""]
     * 
     * @param array $atts Shortcode attributes
     * @return string HTML output
     */
    public function render_sponsors_rotating($atts) {
        $atts = shortcode_atts(array(
            'per_row' => '6',
            'interval' => '5', // seconds
            'type' => '', // normale-sponsor, hoofd-sponsor, or empty for all
        ), $atts);
        
        $sponsors = VTC_Sponsor_Post_Type::get_sponsors_by_type($atts['type']);
        
        if (!$sponsors->have_posts()) {
            return '<p>' . esc_html__('Geen sponsors beschikbaar', 'vtc-informatiebord') . '</p>';
        }
        
        // Group sponsors into rows
        $per_row = intval($atts['per_row']);
        $all_sponsors = array();
        
        while ($sponsors->have_posts()) {
            $sponsors->the_post();
            $logo_url = VTC_Sponsor_Post_Type::get_logo_url(get_the_ID(), 'medium');
            
            if ($logo_url) {
                $all_sponsors[] = array(
                    'logo' => $logo_url,
                    'title' => get_the_title()
                );
            }
        }
        wp_reset_postdata();
        
        // Split into rows
        $rows = array_chunk($all_sponsors, $per_row);
        
        ob_start();
        ?>
        <div class="vtc-sponsors-rotating" 
             data-interval="<?php echo esc_attr($atts['interval'] * 1000); ?>"
             data-per-row="<?php echo esc_attr($atts['per_row']); ?>">
            <?php foreach ($rows as $index => $row): ?>
                <div class="vtc-sponsors-row <?php echo $index === 0 ? 'active' : ''; ?>">
                    <?php foreach ($row as $sponsor): ?>
                        <div class="vtc-sponsor-item">
                            <img src="<?php echo esc_url($sponsor['logo']); ?>" 
                                 alt="<?php echo esc_attr($sponsor['title']); ?>" 
                                 class="vtc-sponsor-logo" />
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        </div>
        <?php
        
        return ob_get_clean();
    }
}
