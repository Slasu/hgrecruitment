<?php
//optimization cleanup, who needs emojis anyway ;)
show_admin_bar( false );
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );

function hg_register_menu() {
    register_nav_menu( 'main-menu', __( 'Main menu', 'heyguys' ) );
}
add_action( 'init', 'hg_register_menu' );

function enqueue_hg_scripts() {
    wp_enqueue_style( 'hg-styles', get_stylesheet_directory_uri() . '/style.css' );
    wp_enqueue_script( 'hg-scripts', get_stylesheet_directory_uri() . '/assets/js/scripts.js', array('jquery') );

//    wp_enqueue_script( 'ajax-script', get_template_directory_uri() . '/js/my-ajax-script.js', array('jquery') );
//    wp_localize_script( 'ajax-script', 'my_ajax_object',
//        array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    //    wp_enqueue_style();
//    wp_enqueue_style();
}
add_action( 'wp_enqueue_scripts', 'enqueue_hg_scripts' );