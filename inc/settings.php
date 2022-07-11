<?php

add_action( 'after_setup_theme', 'kryex_theme_support' );
function kryex_theme_support() {
    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );
    // Enables post and comment RSS feed links to head
    add_theme_support( 'automatic-feed-links' );
    // Add thumbnail theme support
    add_theme_support( 'post-thumbnails' );
    // JS TYPE ATTRİBUTE JS DELETE
    add_theme_support( 'html5', [ 'script', 'style' ] );
    // Declare support for navigation widgets markup.
    add_theme_support( 'html5', array( 'navigation-widgets' ) );
    //Remove widgets block editor
    remove_theme_support( 'widgets-block-editor' );
}

add_filter( 'posts_search', function( $search, \WP_Query $q ) {
    if( ! is_admin() && empty( $search ) && $q->is_search() && $q->is_main_query() )
        $search .=" AND 0=1 ";
    return $search;
}, 10, 2 );

add_filter( 'wp_lazy_loading_enabled', '__return_false' );

add_filter( 'script_loader_tag', function ( $tag, $handle ) {
    if( is_admin() ){ return $tag; }
    return str_replace( ' src', ' defer="defer" src', $tag );
}, 10, 2 );

add_filter( 'get_avatar', 'no_gravatars' );
function no_gravatars( $avatar ) {
    return preg_replace( "/http.*?gravatar\.com[^\']*/", DEFAULT_AVATAR_URL, $avatar );
}

remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

add_action('login_head', 'my_login_head');
function my_login_head() {
    remove_action('login_head', 'wp_shake_js', 12);
}

add_action('init', 'remove_global_styles_and_svg_filters');
function remove_global_styles_and_svg_filters() {
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
    remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );
}

add_action( 'init', 'kryex_register_menus' );
function kryex_register_menus() {
    register_nav_menus([
        'main-menu'     => 'Main menu',
        'mobile-menu'   => 'Mobile menu',
   ]);
}

add_action('after_setup_theme', 'kryex_setup_image_sizes');
function kryex_setup_image_sizes() {
    add_image_size( 'katalog', 300, 300,   true );
}

function kryex_breadcrumbs(){
    // breadcrumbs
    $delimiter   = '<i class="feather-icon icon-chevrons-right"></i>';
    $delimiter   = '<em class="delimiter">'. $delimiter .'</em>';
    $home_icon   = '<i class="feather-icon icon-home"></i>';
    $home_text   = esc_html__( 'Anasayfa', K_TEXT );
    $before      = '<span class="current">';
    $after       = '</span>';
    $breadcrumbs = array();

    if  ( ! is_home() && ! is_front_page() || is_paged() ){

        $post     = get_post();
        $home_url = esc_url(home_url( '/' ));

        // Home
        $breadcrumbs[] = array(
            'url'   => $home_url,
            'name'  => $home_text,
            'icon'  => $home_icon,
        );

        // Category
        if ( is_category() ){
            $category = get_query_var( 'cat' );
            $category = get_category( $category );

            if( $category->parent !== 0 ){

                $parent_categories = array_reverse( get_ancestors( $category->cat_ID, 'category' ) );

                foreach ( $parent_categories as $parent_category ) {
                    $breadcrumbs[] = array(
                        'url'  => get_term_link( $parent_category, 'category' ),
                        'name' => get_cat_name( $parent_category ),
                    );
                }
            }

            $breadcrumbs[] = array(
                'name' => get_cat_name( $category->cat_ID ),
            );
        }

        // Day
        elseif ( is_day() ){
            $breadcrumbs[] = array(
                'url'  => get_year_link( get_the_time( 'Y' ) ),
                'name' => get_the_time( 'Y' ),
            );

            $breadcrumbs[] = array(
                'url'  => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
                'name' => get_the_time( 'F' ),
            );

            $breadcrumbs[] = array(
                'name' => get_the_time( 'd' ),
            );
        }

        // Month
        elseif ( is_month() ){
            $breadcrumbs[] = array(
                'url'  => get_year_link( get_the_time( 'Y' ) ),
                'name' => get_the_time( 'Y' ),
            );

            $breadcrumbs[] = array(
                'name' => get_the_time( 'F' ),
            );
        }

        // Year
        elseif ( is_year() ){
            $breadcrumbs[] = array(
                'name' => get_the_time( 'Y' ),
            );
        }

        // Tag
        elseif ( is_tag() ){
            $breadcrumbs[] = array(
                'name' => get_the_archive_title(),
            );
        }

        // Author
        elseif ( is_author() ){
            global $author;
            $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));

            $breadcrumbs[] = array(
                'name' => '<span class="br-title">'.$curauth->display_name.'</span>',
            );
        }

        // Search
        elseif ( is_search() ){
            $breadcrumbs[] = array(
                'name' => sprintf( esc_html__( 'Arama Sonuçları: %s', K_TEXT ),  get_search_query() ),
            );
        }

        // 404
        elseif ( is_404() ){
            $breadcrumbs[] = array(
                'name' => esc_html__( 'Hiçbirşey Bulunamadı', K_TEXT ),
            );
        }

        // BuddyPress
        elseif ( function_exists('bp_current_component') && bp_current_component() ){
            $breadcrumbs[] = array(
                'name' => get_the_title(),
            );
        }

        // Pages
        elseif ( is_page() ){
            if ( $post->post_parent ){
                $parent_id   = $post->post_parent;
                $page_parents = array();

                while ( $parent_id ){
                    $get_page  = get_page( $parent_id );
                    $parent_id = $get_page->post_parent;

                    $page_parents[] = array(
                        'url'  => get_permalink( $get_page->ID ),
                        'name' => get_the_title( $get_page->ID ),
                    );
                }

                $page_parents = array_reverse( $page_parents );

                foreach( $page_parents as $single_page ){
                    $breadcrumbs[] = array(
                        'url'  => $single_page['url'],
                        'name' => $single_page['name'],
                    );
                }
            }

            $breadcrumbs[] = array(
                'name' => get_the_title(),
            );
        }

        // Attachment
        elseif ( is_attachment() ){
            if( ! empty( $post->post_parent ) ){
                $parent = get_post( $post->post_parent );

                $breadcrumbs[] = array(
                    'url'  => get_permalink( $parent ),
                    'name' => $parent->post_title,
                );
            }

            $breadcrumbs[] = array(
                'name' => get_the_title(),
            );
        }

        // Single Posts
        elseif ( is_singular() ){
            // Single Post
            if ( get_post_type() == 'post' ){

                $category = get_the_category();
                $useCatLink = true;
                // If post has a category assigned.
                if ($category){
                    $category_display = '';
                    $category_link = '';
                    if ( class_exists('WPSEO_Primary_Term') ) {
                        // Show the post's 'Primary' category, if this Yoast feature is available, & one is set
                        $wpseo_primary_term = new WPSEO_Primary_Term( 'category', get_the_id() );
                        $wpseo_primary_term = $wpseo_primary_term->get_primary_term();
                        $term = get_term( $wpseo_primary_term );
                        if (is_wp_error($term)) {
                            // Default to first category (not Yoast) if an error is returned
                            $category_display = $category[0]->name;
                            $category_link = get_category_link( $category[0]->term_id );
                        } else {
                            // Yoast Primary category
                            $category_display = $term->name;
                            $category_link = get_category_link( $term->term_id );
                        }
                    }
                    else {
                        // Default, display the first category in WP's list of assigned categories
                        $category_display = $category[0]->name;
                        $category_link = get_category_link( $category[0]->term_id );
                    }

                }

                if( ! empty( $category ) ){

                    $category = get_category( $category );

                    if( $category->parent !== 0 ){
                        $parent_categories = array_reverse( get_ancestors( $category->term_id, 'category' ) );

                        foreach ( $parent_categories as $parent_category ) {
                            $breadcrumbs[] = array(
                                'url'  => $category_link,
                                'name' => htmlspecialchars($category_display),
                            );
                        }
                    }

                    $breadcrumbs[] = array(
                        'url'  => $category_link,
                        'name' => htmlspecialchars($category_display),
                    );
                }
            }

            // Custom Post Type
            else{
                // Get the main Post type archive link
                if( $archive_link = get_post_type_archive_link( get_post_type() ) ){

                    $post_type = get_post_type_object( get_post_type() );

                    $breadcrumbs[] = array(
                        'url'  => $archive_link,
                        'name' => $post_type->labels->singular_name,
                    );
                }

                // Get custom Post Types taxonomies
                $taxonomies = get_object_taxonomies( $post, 'objects' );

                if( ! empty( $taxonomies ) && is_array( $taxonomies ) ){
                    foreach( $taxonomies as $taxonomy ){
                        if( $taxonomy->hierarchical ){
                            $taxonomy_name = $taxonomy->name;
                            break;
                        }
                    }
                }

                if( ! empty( $taxonomy_name ) ){
                    $custom_terms = get_the_terms( $post, $taxonomy_name );

                    if( ! empty( $custom_terms ) && ! is_wp_error( $custom_terms )){

                        foreach ( $custom_terms as $term ){

                            $breadcrumbs[] = array(
                                'url'  => get_term_link( $term ),
                                'name' => $term->name,
                            );

                            break;
                        }
                    }
                }
            }

            $breadcrumbs[] = array(
                'name' => get_the_title(),
            );
        }


        // Print the BreadCrumb
        if( ! empty( $breadcrumbs ) ){
            $counter = 0;
            $item_list_elements = array();
            $breadcrumbs_schema = array(
                '@context' => 'http://schema.org',
                '@type'    => 'BreadcrumbList',
                '@id'      => '#Breadcrumb',
            );

            echo '<nav class="breadcrumb">';

            foreach( $breadcrumbs as $item ) {

                $counter++;

                if( ! empty( $item['url'] )){
                    $icon = ! empty( $item['icon'] ) ? $item['icon'] .' ' : '';
                    echo '<a href="'. esc_url( $item['url'] ) .'">'. $icon . $item['name'] .'</a>'. $delimiter;
                }
                else{
                    echo ( $before . $item['name'] . $after );

                    global $wp;
                    $item['url'] = esc_url(home_url(add_query_arg(array(),$wp->request)));
                }

                $item_list_elements[] = array(
                    '@type'    => 'ListItem',
                    'position' => $counter,
                    'item'     => array(
                        'name' => str_replace( '<i class="feather-icon icon-home"></i> ', '', $item['name']),
                        '@id'  => $item['url'],
                    )
                );

            }

            echo '</nav>';
        }
    }

    wp_reset_postdata();
}
