<?php

define('K_VER', '1.0.0');
define('K_TEXT', 'mangas');
define('K_DIR', get_template_directory());
define('K_URI', get_template_directory_uri());
define('DEFAULT_AVATAR_URL', K_URI . '/assets/img/default_avatar.jpg');

require K_DIR . '/inc/settings.php';

add_action( 'wp_enqueue_scripts', 'kryex_scripts' );
function kryex_scripts() {
    wp_deregister_script('jquery');
    wp_dequeue_style( 'wp-block-library' );
    wp_enqueue_style('style', get_stylesheet_uri());
    wp_enqueue_style('fonts', 'https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap', [], null);
    wp_enqueue_style('main', K_URI . '/assets/css/main.css', [], K_VER, 'all');
    
    wp_enqueue_script('lazysizes', K_URI . '/assets/js/lazysizes.js', [], '5.1.2', true);
    wp_enqueue_script('global', K_URI . '/assets/js/global.js', [], K_VER, true);

    if ( !is_single() ) {
        wp_enqueue_script('front', K_URI . '/assets/js/front.js', [], K_VER, true);
    } else {
        wp_enqueue_script('single', K_URI . '/assets/js/single.js', [], K_VER, true);
    }
}
