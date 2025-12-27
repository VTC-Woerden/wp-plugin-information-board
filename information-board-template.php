<?php
/**
 * Template Name: Information board
 * Template Post Type: page
 * Description: Template for information board
 * Version: 1.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Force block editor styles to load
add_action('wp_enqueue_scripts', function() {
    // Enqueue all necessary block styles
    wp_enqueue_style('wp-block-library');
    wp_enqueue_style('wp-block-library-theme');
    wp_enqueue_style('global-styles');
    
    // Enqueue theme styles
    if (wp_style_is('twentytwenty-style', 'registered')) {
        wp_enqueue_style('twentytwenty-style');
    }
}, 1);

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body id="top" <?php body_class(); ?>>
    <div id="wrap_all">

        <div id="main" class="all_colors">
            <div class="main_color">
                
                <main class="template-page content  av-content-full alpha units">
                    <?php
                    while (have_posts()) :
                        the_post();
                        
                        // Output the content
                        the_content();
                        
                    endwhile;
                    ?>

                </main>
            </div>
        </div>
        <?php wp_footer(); ?>
    </div>
</body>
</html>