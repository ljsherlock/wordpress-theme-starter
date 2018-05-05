<?php

namespace ContentTypes;

class Sidebar
{
    /**
     * Setup Widget
     */
    public function __construct()
    {
        add_action( 'widgets_init', array( __CLASS__, 'register_sidebars') );
    }

    public static function register_sidebars()
    {
        self::default_sidebars();
        self::theme_sidebars();
    }

    public static function theme_sidebars()
    {
        register_sidebar( array(
            'name'          => __( 'Post: After Content ', 'theme_text_domain' ),
            'id'            => 'sidebar_post_after',
            'description'   => 'This sidebar will appear after the Post content.',
            'before_widget' => '',
            'after_widget'  => '',
        ));

        register_sidebar( array(
            'name'          => __( 'Work: After Content ', 'theme_text_domain' ),
            'id'            => 'sidebar_work_after',
            'description'   => 'This sidebar will appear after the Work content.',
            'before_widget' => '',
            'after_widget'  => '',
        ));
    }

    public static function default_sidebars()
    {
        register_sidebar( array(
            'name'          => __( 'Sidebar', LJS_TEXT_DOMAIN ),
            'id'            => 'sidebar',
            'description'   => 'This sidebar will appear in the side of the page.',
            'before_widget' => '',
            'after_widget'  => '',
        ));

        register_sidebar( array(
            'name'          => __( 'Header Sidebar', LJS_TEXT_DOMAIN ),
            'id'            => 'sidebar__header',
            'description'   => 'This sidebar will appear in the header of the page.',
            'before_widget' => '',
            'after_widget'  => '',
        ));

        register_sidebar( array(
            'name'          => __( 'Footer Sidebar', LJS_TEXT_DOMAIN ),
            'id'            => 'sidebar__footer',
            'description'   => 'This sidebar will appear in the footer of the page.',
            'before_widget' => '',
            'after_widget'  => '',
        ));

        register_sidebar( array(
            'name'          => __( 'Home Page Main', LJS_TEXT_DOMAIN ),
            'id'            => 'sidebar__homepage_main',
            'description'   => 'This sidebar will appear after the content.',
            'before_widget' => '',
            'after_widget'  => '',
        ));

        register_sidebar( array(
            'name'          => __( 'Sidebar After App', LJS_TEXT_DOMAIN ),
            'id'            => 'sidebar__after_app',
            'description'   => 'This sidebar will appear after the main content.',
            'before_widget' => '',
            'after_widget'  => '',
        ));
    }
}
