<?php
/**
 * Template Name: Sponsorboard
 * Description: Full-screen digital sponsor board displaying sponsor logos
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get all sponsors
$sponsors = VTC_Sponsor_Post_Type::get_sponsors_by_type();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html(get_the_title()); ?></title>
    <?php wp_head(); ?>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: #ffffff;
            overflow: hidden;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, sans-serif;
        }
        
        .sponsorboard-header {
            width: 100%;
            text-align: center;
            padding: 30px 20px 0;
        }
        
        .sponsorboard-header h1 {
            margin: 0;
            font-size: 2.5rem;
            color: #333;
            font-weight: 600;
        }
        
        .sponsorboard-container {
            width: 100vw;
            min-height: 100vh;
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            padding: 40px;
            align-content: center;
            justify-content: center;
            align-items: center;
        }
        
        .sponsor-item {
            width: calc((100% - (5 * 30px)) / 6);
            max-width: 250px;
            min-width: 150px;
            height: 150px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .sponsor-logo {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
            transition: transform 0.3s ease;
        }
        
        .sponsor-item:hover .sponsor-logo {
            transform: scale(1.05);
        }
        
        /* No sponsors message */
        .no-sponsors {
            grid-column: 1 / -1;
            text-align: center;
            font-size: 24px;
            color: #666;
            padding: 40px;
        }
        
        /* Responsive adjustments */
        @media (max-width: 1024px) {
            .sponsor-item {
                width: calc((100% - (3 * 20px)) / 4);
                height: 120px;
            }
            
            .sponsorboard-container {
                gap: 20px;
                padding: 20px;
            }
        }
        
        @media (max-width: 768px) {
            .sponsor-item {
                width: calc((100% - (2 * 15px)) / 3);
                height: 100px;
            }
            
            .sponsorboard-container {
                gap: 15px;
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="sponsorboard-header">
        <h1>Sponsoren van VTC Woerden</h1>
    </div>
    <div class="sponsorboard-container">
        <?php if ($sponsors->have_posts()): ?>
            <?php while ($sponsors->have_posts()): $sponsors->the_post(); ?>
                <?php $logo_url = VTC_Sponsor_Post_Type::get_logo_url(get_the_ID(), 'large'); ?>
                
                <?php if ($logo_url): ?>
                    <div class="sponsor-item">
                        <img src="<?php echo esc_url($logo_url); ?>" 
                             alt="<?php echo esc_attr(get_the_title()); ?>" 
                             class="sponsor-logo" />
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
            <?php wp_reset_postdata(); ?>
        <?php else: ?>
            <div class="no-sponsors">
                <p>Geen sponsoreren beschikbaar</p>
            </div>
        <?php endif; ?>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
