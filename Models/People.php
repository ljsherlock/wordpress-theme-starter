<?php

namespace Models;

use Includes\Classes\CMB2 as CMB2;
use Includes\Utils\Utils as Utils;

class People extends Archive
{
    public function __construct($args)
    {
        parent::__construct($args);

        // Custom query
        // We will use this for ajax so we want to be able override the posts_per_page if required.
        $this->args['query']['posts_per_page'] = 12;
        $this->args['query']['post_type'] = 'staff';
        add_filter( 'posts_orderby' , array('Includes\Utils\Utils', 'posts_orderby_lastname') );
    }

    public function get()
    {
      return parent::get();
    }

    /**
    * @method afterQueryCallback
    *
    * @return Updated PostsArray
    */
    public function afterQueryCallback($postsArray)
    {
      remove_filter( 'posts_orderby' , array('Utils', 'posts_orderby_lastname') );
      // Add position term to
      $this->addToObj( $postsArray, 'position', function($post) {
          return wp_get_object_terms($post->ID, 'position');
      });

      // Loop through key projects (limit 4) and return Timber Post Obj to Person
      $this->addToObj( $postsArray, 'key_projects', function($post) {
        $keyProjectsArray = [];
        $keyProjects = get_post_meta( $post->ID, CMB2::$prefix . 'staff_key_projects', true );

        if ( !empty($keyProjects) ) {
          foreach( $keyProjects as $key => $keyProject ) {
            $keyProjectsArray[] = new \TimberPost( $keyProject );
          }
          return $keyProjectsArray;
        }
        return null;
      });

      return $postsArray;
    }
}
