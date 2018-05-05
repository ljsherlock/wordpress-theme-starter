<?php

namespace Models;

use Includes\Classes\CMB2 as CMB2;

class Services extends Page
{
    public function __construct($args)
    {
        parent::__construct($args);
    }

    public function get()
    {
      // Get a Multidimensional Array of the Type terms
      $type_terms = $this->get_hierachical_terms('type', 'all', array(), array('hide_empty' => false));

      // Add recent related posts to the services Objs
      $terms = $this->addToObj($type_terms['all']->children, 'recent_posts', function( $term ) {
        $this->args = array(
          'query' => array(
            'tax_query' => array(
              array(
                'taxonomy' => $term->taxonomy,
                'field' => 'slug',
                'terms' => $term->slug
              ),
            ),
						'posts_per_page' => 3,
        ));
        $archive = new \Controllers\Archive($this->args);
        return $this->posts = $archive->returnData('posts')['postsArray'];
      });

      $this->timber->addContext(array(
        'posts' => array( 'postsArray' =>  $terms )
      ));

      return parent::get();
    }
}
