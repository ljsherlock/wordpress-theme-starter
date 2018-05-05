<?php

namespace Models;

use Includes\Classes\CMB2 as CMB2;
use Includes\Classes\Images as Images;

class Archive extends Page
{
    /**
    *   @property Array $args archive query
    */
    public $args = array();

    public $term = '';

    public $post = null;

    public $posts = null;

    public $afterQueryCallback = null;

    /**
    * @method __construct
    *
    * @param Array $args Model arguments
    */
    public function __construct($args)
    {
      parent::__construct($args);

      /*
      *  If a custom query has not been created, populate with query_var
      */
      (!empty($this->args['query']['posts_per_page'])) ?: $this->args['query']['posts_per_page'] = get_query_var('posts_per_page');
      (!empty($this->args['query']['paged'])) ?: $this->args['query']['paged'] = get_query_var('paged');
      (!empty($this->args['query']['taxonomy'])) ?: $this->args['query']['taxonomy'] = get_query_var('taxonomy');
      (!empty($this->args['query']['term'])) ?: $this->args['query']['term'] = get_query_var('term');
      (!empty($this->args['query']['tag'])) ?: $this->args['query']['tag'] = get_query_var('tag');
      (!empty($this->args['query']['category_name'])) ?: $this->args['query']['category_name'] = get_query_var('category_name');
      (!empty($this->args['query']['post_type'])) ?: $this->args['query']['post_type'] = get_query_var('post_type');
      $this->args['query']['orderby'] = 'date';
      $this->args['query']['order'] = 'DESC';

      $this->posts = null;
    }

    /**
    * @method get returns data to the controller
    *
    * @param void
    *
    * @return $context array( $posts, $pagination )
    */
    public function get()
    {
      $this->addPostsData();

      $this->buildQuery();

      $this->posts['postsArray'] = $this->query($this->args);

			if (!isset($this->args['extra_data'])) {
				$this->addPostsDataAfterQuery();

	      $this->timber->addContext(array(
	        'hierachical_terms' => $this->get_hierachical_terms('type' , null, array('hide_empty' => false))
	      ));
			}

			$this->timber->addContext(array(
				'posts' => $this->posts,
				'post' => $this->post,
			));

      return parent::get();
    }

    /**
    * @method Add extra data to Posts Array
    *
    * @return NULL
    */
    public function addPostsData()
    {
      $this->posts['post_type_obj'] = get_post_type_object( $this->args['query']['post_type'] );
      $this->posts['taxonomy_obj'] = get_taxonomy( $this->args['query']['taxonomy'] );
      $this->posts['term_obj'] = new \TimberTerm($this->args['query']['term'], $this->args['query']['taxonomy'] );
      $this->posts['category_term_obj'] = new \TimberTerm($this->args['query']['category_name'], 'category' );
      $this->posts['tag_term_obj'] = new \TimberTerm($this->args['query']['tag'], 'post_tag' );
      $this->posts['posts_per_page'] = $this->args['query']['posts_per_page'];
      if ( isset($this->args['query']['ajax_posts_per_page']) ) {
        $this->posts['ajax_posts_per_page'] = $this->args['query']['ajax_posts_per_page'];
      }

    }

    /**
    * @method Add extra data to Posts Array after the query
    *
    * @return NULL
    */
    public function addPostsDataAfterQuery()
    {
      $this->addMorePostData();

      if( !empty($this->posts['postsArray']) ) {

        // Add Post Type Obj because if this is a term of taxonomy, it won't be
        // in the query Array.
        if(empty( $this->posts['post_type_obj'] )) {
          $this->posts['post_type_obj'] = get_post_type_object( $this->posts['postsArray'][0]->post_type );
        }

        array_walk($this->posts['postsArray'], function(&$post, $key) {
						$single = new Single(array( 'id' => $post->ID ));
						$single->additionalPostData();
            $single->addRelatedPosts();
						$post = $single->post;
        });
      }

      // Add pagination to the posts array.
      $this->posts['pagination'] = \Timber::get_pagination();

      // // call the passed callback if is a function
      // if( method_exists($this, 'afterQueryCallback') ) {
      //   $this->posts['postsArray'] = $this->afterQueryCallback($this->posts['postsArray']);
      // }
    }

    /**
    * @method Build the query
    *
    * @return NULL
    */
    public function buildQuery() {
      /*
      ** I think a lot of the below code here is overriding the above.
      */
      if( !empty($this->posts['post_type_obj']->name) ){
        $this->args['query']['post_type'] = $this->posts['post_type_obj']->name;
      }
      if( !empty($this->posts->category_term_obj->name) ) {
          $this->args['query']['category_name'] = $this->posts['category_term_obj']->name;
      }
      if( !empty( $this->posts['tag_term_obj']->name) ) {
          $this->args['query']['tax_query'] = array(
            array(
              'taxonomy' => 'post_tag',
              'field' => 'slug',
              'terms' => $this->posts['tag_term_obj']->name
            )
          );
      }
      if( !empty( $this->posts['taxonomy_obj']->name ) && empty( $this->args['query']['tax_query'] ) ) {
          $this->args['query']['tax_query'] = array(
              array( 'field' => 'slug' )
          );
          $this->args['query']['tax_query'][0]['taxonomy'] = $this->posts['taxonomy_obj']->name;
      }
      if( !empty( $this->posts['term_obj']->name ) ) {
          $this->args['query']['tax_query'][0]['terms'] = $this->posts['term_obj']->slug;
      }
    }

    /**
    * @method Add Load More data to the posts
    *
    * @return NULL
    */
    public function addMorePostData() {
      global $wp_query;
      $found_posts = $wp_query->found_posts;
      $count = [];

      if ($found_posts !== null && $this->args['query']['posts_per_page'] !== null ) {
        if( 0 < ((int)$found_posts - (int)$this->args['query']['posts_per_page']) ) {
          $more = 1;
        } else {
          $more = 0;
        }
      }

      $this->posts['more'] = $more;
      $this->posts['found_posts'] = $found_posts;
    }
}

// $this->posts->year = get_query_var('year');
// $this->posts->monthnum = get_query_var('monthnum');
// $this->posts->day = get_query_var('day');
//
// 'date_query' => array(
// 	array(
// 		'year'  => 2012,
// 		'month' => 12,
// 		'day'   => 12,
// 	),
// ),


/*
* Create the page title
* ## Is this actually necessary, or am I recreating the wheel??
* ## This is not being used for this project, but I was using it for
* ## my own site.
*/
// $this->posts['title'] = null;
// if ( !empty($this->posts['term_obj']->name) ) {
//   $this->posts['title'] = $this->posts['term_obj']->name;
//
// } elseif( !empty($this->posts['category_term_ob']->name) ) {
//   $this->posts['title'] = $this->posts['category_term_obj']->name;
//
// } elseif( !empty($this->posts['tag_term_obj']->name) ) {
//   $this->posts['title'] = $this->posts['tag_term_obj']->name;
//
// } elseif( !empty($this->posts['post_type_obj']) ) {
//   $this->posts['title'] = $this->posts['post_type_obj']->label;
//
// } elseif( !empty($this->posts['taxonomy_obj']) ) {
//   $this->posts['title'] = $this->posts['taxonomy_obj']->label;
//
// }
