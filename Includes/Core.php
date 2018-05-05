<?php

namespace Includes;

class Core
{
    public static function init()
    {
        require_once 'constants.php';

        // Required by The SEO Framework
        add_theme_support( 'title-tag' );

        add_filter('show_admin_bar', '__return_false');

        // Widgets
        $sidebars = new \ContentTypes\Sidebar();
        $widgets = new \ContentTypes\Widgets();

        add_action('init', array( '\ContentTypes\Shortcodes', 'setup' ));

        // REMOVE WP EMOJI
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');

        remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
        remove_action( 'admin_print_styles', 'print_emoji_styles' );

        // POSTS
        //$content_block = new \ContentTypes\Content_Block();
        //$content_block->register();

        /**
        * Setup ACF
        * Add options pages
        */
        Classes\Scripts::setup();

        /**
        * Setup CMB2
        * Add options pages
        */
        Classes\CMB2::init();

        /**
        * WP Images
        * Add images sizes
        * Add theme support
        */
        Classes\Images::setup();

        /**
        * WP Navigation
        * Register Navigation Menus
        * Add current nav class action
        */
        Classes\Nav::setup();

        /**
        * WP Navigation
        * Register Navigation Menus
        * Add current nav class action
        */
        Classes\Map::setup();

    }
}
