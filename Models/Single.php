<?php

namespace Models;

use Includes\Classes\CMB2 as CMB2;
use Includes\Classes\Images as Images;

class Single extends Base
{
    public $sidebars = array();

    public $postDataCallback = null;

    /**
    *   @method __construct
    *   @return get
    **/
    public function __construct($args)
    {
        parent::__construct($args);

        // Set Sidebars and merge
        $this->sidebars = array(
            'sidebar',
            'sidebar__header',
            'sidebar__footer',
            'sidebar__homepage_main',
            'sidebar__after_app',
        );

        if( isset( $this->args['sidebars'] ) )
        {
            array_merge( $this->sidebars, $this->args['sidebars'] ) ;
        }

        // Accept slug and id as parameters for getting the Post Obj
        if (isset( $this->args['slug'] )) {
          $this->post = new \TimberPost( $this->args['slug'] );
        } else if( isset($this->args['id']) ) {
          $this->post = new \TimberPost( $this->args['id'] );
        } else {
          $this->post = new \TimberPost();
        }

        // Early check for is_single (for use in Twig templates).
        $this->addToObj($this->post, 'is_single', function($post) {
            return is_single($post->ID);
        });
    }

    /**
    *   @method get
    *   @return parent::get()
    *
    *
    **/
    public function get()
    {
      // Add all to context
      $this->timber->addContext( array(
          'post' => $this->post,
      ));

      return parent::get();
    }

    /**
    * @method additionalPostData
    * Add additional data to the post Obj
    *
    * @param Post Obj
    *
    * @return Updated Post Obj
    *
    * postObj {
    *   post_type_obj
    *   taxonomies
    *   taxonomyObjs [
    *     [taxononmy] [
    *         terms
    *         termsObjs
    *         hierarchicalTermObjs
    *      ]
    *   ]
    *   post_thumbnails [
    *     size [
    *       width
    *       height
    *       crop
    *       img
    *       url
    *     ]
    *   ]
    */
    public function additionalPostData() {
      // get post type obj
      $this->post->post_type_obj = get_post_type_object( $this->post->post_type );

      // Retrieve Array of Taxonomy names
      $this->post->taxonomies = get_post_taxonomies( $this->post );

      $this->post->taxonomyObjs = [];

      foreach ($this->post->taxonomies as $key => $taxonomy) {
        // Retrieve Taxonomy object
        $this->post->taxonomyObjs[$taxonomy] = get_taxonomy($taxonomy);

        // Retrieve Array of Term Objects attached Post that are not emmpty.
        $this->post->taxonomyObjs[$taxonomy]->termObjs = wp_get_post_terms( $this->post->ID, $taxonomy, array('hide_empty' => true) );

        // Get hierarchically sorted Array of Term obj
        $this->post->taxonomyObjs[$taxonomy]->hierarchicalTermObjs = $this->get_hierachical_terms_by_post($this->post, $taxonomy);

        // Create terms Array and fill with the term slugs.
        $this->post->taxonomyObjs[$taxonomy]->terms = [];
        foreach ($this->post->taxonomyObjs[$taxonomy]->termObjs as $key => $term) {
          $this->post->taxonomyObjs[$taxonomy]->terms[$key] = $term->term_id;
        }
      }

      // Get all registered image sizes for the featured image
      $this->thumbnails();

      // call the passed callback if is a function
      if( method_exists($this, 'postDataCallback') )
      {
        $this->post = $this->postDataCallback($this->post);
      }
    }

		public function thumbnails() {
			// Get all registered image sizes for the featured image
			$this->addToObj($this->post, 'post_thumbnails', function( $postObj ) {
				return Images::get_all_image_sizes(get_post_thumbnail_id($postObj->ID), get_intermediate_image_sizes());
			});
		}

    /**
    * @method addRelatedPosts
    * Add related posts to the postObj
    *
    * @param Post Obj
    *
    * @return Updated Post Obj
    *
    * relatedPosts [
    *   postObj
    * ]
    */
    public function addRelatedPosts()
    {
      // Base args
      $this->relatedPostsArgs = array(
       'posts_per_page' => 3,
       'ignore_sticky_posts' => 1
      );

      // Build arguments with Category or Taxonomy info.
      if ($this->post->post_type == 'post') {
           $this->relatedPostsArgs['category__in'] = $this->post->taxonomyObjs['category']->terms;
           $this->relatedPostsArgs['post__not_in'] = array( $this->post->ID );
      } else {
        $this->relatedPostsArgs['tax_query'] = [];
        foreach ($this->post->taxonomyObjs as $key => $taxonomyObj) {
          $this->relatedPostsArgs['tax_query'][$key] = array('field' => 'id');
          $this->relatedPostsArgs['tax_query'][$key]['taxonomy'] = $taxonomyObj->name;
          $this->relatedPostsArgs['tax_query'][$key]['terms'] = $taxonomyObj->terms;
        }
      }

      // Run the query and add to the Post Obj
      $this->addToObj($this->post, 'relatedPosts', function( $postObj ) {
        if( !empty($this->relatedPostsArgs) ) {
          return $this->query(array('query' => $this->relatedPostsArgs));
        }
        return null;
      });

      // Add additional post Data
      if(is_array( $this->post->relatedPosts )) {
        array_walk($this->post->relatedPosts, function($post, $key, $single) {
            $post = $single->additionalPostData($post);
        }, $this);
      }
    }

}
