<?php

namespace Includes\Classes;

class Nav
{

    public static function setup()
    {
        self::navSetup();

      //  add_action('nav_menu_css_class', array(__CLASS__, 'add_current_nav_class'), 10, 2 );

        // add filter for wp_nav_menu so you can pass sub_menu true to only show children
        add_filter('wp_nav_menu_objects', array('WPNav', 'subNavFilter'), 10, 2);
    }

    /**
     *  Register Navigation areas
     */
    public static function navSetup()
    {
        register_nav_menus(
            array(
                'primary' => 'Primary',
                'secondary' => 'Secondary',
                'footer' => 'Footer'
            )
        );
    }

    /**
     * Works out what section we are in and returns a class name
     * we can use in CSS to colour all the links correctly
     */
     public static function add_current_nav_class($classes, $item) {

         // Getting the current post details
         global $post;

         // Get post ID, if nothing found set to NULL
         $id = ( isset( $post->ID ) ? get_the_ID() : NULL );

         // Checking if post ID exist...
         if (isset( $id ))
         {
             // Getting the post type of the current post
             $current_post_type = get_post_type_object(get_post_type($post->ID));

             // Getting the rewrite slug containing the post type's ancestors
             $ancestor_slug = $current_post_type->rewrite['slug'];

             // Split the slug into an array of ancestors and then slice off the direct parent.
             $ancestors = explode('/',$ancestor_slug);
             $parent = array_pop($ancestors);

             // Getting the URL of the menu item
             $menu_slug = strtolower(trim($item->url));

             // If the menu item URL contains the post type's parent
            if (!empty($menu_slug) && !empty($parent) && strpos($menu_slug,$parent) !== false) {
                $classes[] = 'current-menu-item';
            }

             // If the menu item URL contains any of the post type's ancestors
             foreach ( $ancestors as $ancestor ) {
                 if (strpos($menu_slug,$ancestor) !== false) {
                     $classes[] = 'current-page-ancestor';
                 }
             }
         }
         // Return the corrected set of classes to be added to the menu item
         return $classes;

     }
}
