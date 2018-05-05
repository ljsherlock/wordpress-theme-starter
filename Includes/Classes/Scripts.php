<?php

namespace Includes\Classes;

class Scripts
{

    public static function setup()
    {
        add_action('wp_enqueue_scripts', array(__CLASS__, 'load_scripts'));
        // add_action('admin_head', array(__CLASS__, 'admin_css'));
    }

    /**
    *  Register & Load CSS & Scripts
    *
    *  @todo   concatenize where needed when going live
    */
    public static function load_scripts()
    {

        /*----------------------------------------------------
        *   CSS
        ----------------------------------------------------*/

           wp_register_style( 'style' , LJS_THEME_URL . '/css/styles.min.css', false, '1.0.0' );
           wp_enqueue_style( 'style' );

         /*----------------------------------------------------
         *   JAVSCRIPT
         ----------------------------------------------------*/

             // Load up require project.

    }

    public static function admin_css()
    {
      echo '<style>
      li#menu-posts.menu-top-first {
          display: none;
      }
      </style>';
    }
}
