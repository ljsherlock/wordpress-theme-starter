<?php

namespace Models;

use Includes\Utils\Utils as Utils;

use Includes\Classes\CMB2 as CMB2;

// base can be any kind of post type (page, post etc).
abstract class Base
{
    /**
    * Model args
    * @var object
    */
    protected $args = [];

    /**
    * Stores all model data
    * @var object
    */
    protected $data = array();

    /**
    * Twig worker
    * @var \wptwig\Workers\Twig
    */
    public $timber = null;

    /**
    * @method __construct
    *
    * @param Array $args Model arguments
    */
    public function __construct( $args )
    {
        $this->args = array_merge($this->args, $args);

        $this->add('args', $args);

        $this->add('device', Utils::isDevice());
    }

    /**
    * @method Stores all model data
    *
    * @param String $name key
    * @param Mixed $value array value
    */
    public function add( $name, $value )
    {
        $this->data[ $name ] = $value;
    }

    /**
    * @method get returns data to the controller
    *
    * @param void
    *
    * @return $this->data
    */
    public function get()
    {
      if (!isset($this->timber->context['request_body'])) {
          $this->timber->addContext( array('favourites' => \Timber::get_context()) );
      }

      wp_reset_query();

      $this->timber->addContext( array(


          'global' => array(
            'year' => date('Y'),
            'device' => $this->data['device'],
            'mustard' => (_MUSTARD != null && _MUSTARD == 'true') ? true : '',

            'vacancy_email' => CMB2::myprefix_get_option('settings-contact', CMB2::$prefix . 'vacancy_email'), // WordPress
            'social_media' => CMB2::myprefix_get_option('settings-company', CMB2::$prefix . 'social_media_links'), // WordPress
            'header' => array(
              'menu' => new \Timber\Menu('primary'), // WordPress
            ),
            'footerMenu' => new \TimberMenu('Footer'), // WordPress
            'company_address' => CMB2::myprefix_get_option('settings-contact', CMB2::$prefix . 'company_address'), // WordPress
            'company_name' => CMB2::myprefix_get_option('settings-contact', CMB2::$prefix . 'company_name'), // WordPress
            'email' => CMB2::myprefix_get_option( 'settings-contact', CMB2::$prefix . 'contact_email'), // WordPress
            'telephone' => CMB2::myprefix_get_option('settings-contact', CMB2::$prefix . 'contact_telephone'), // WordPress
            'copyright' => '&copy; ' . date('Y') . ' ' . CMB2::myprefix_get_option('settings-company', CMB2::$prefix . 'company_name'), // WordPress
            'menu' => new \TimberMenu('Footer'), // WordPress
          )
        ));

        // put timber context in the $data variable
        $this->context = $this->timber->context;

        // die(var_dump($this->context));

        // force array for twig
        return $this->context;
    }

    public function context()
    {
        // put timber context in the $data variable
        $this->data->context = $this->timber->context;

        // force array for twig
        return $this->data->content;
    }

    /**
    * @method forceArray
    * The WP widget class does have trouble
    * see Models/Widget->get()
    * so force the data to be an array.
    * @param mixed $data Array/object
    * @return array
    */
    protected function forceArray($data)
    {
        return json_decode(json_encode($data), true);
    }

    public function addSidebar($sidebars)
    {
        foreach ($sidebars as $key => $sidebar)
        {
            $this->timber->addContext( array( $sidebar => \Timber::get_widgets( $sidebar ) ) );
        }
    }

    public function terms($post, $args = array(), $output = 'names')
    {
        $terms = wp_get_object_terms( $post->ID, get_taxonomies($args, $output));

        $timberTerms = array();

        foreach ($terms as $key => $term)
        {
            $timberTerms[$key] = new \TimberTerm( $term->term_id );
        }

        return $timberTerms;
    }


    /**
    * get_hierachical_terms_by_post()
    * @var $post (Obj)
    * @var $tax (String)
    */
    public function get_hierachical_terms_by_post($post, $tax)
    {
        $terms = wp_get_post_terms( $post->ID, $tax, array( 'hide_empty' => true) );
        // Removed 'parent' => 0,
        $sorted_terms = [];

        $sorted_terms = $this->get_hierachical_terms_by_post_loop($post, $tax, $terms, $sorted_terms);

        unset( $sorted_terms['children'] );

        return $sorted_terms['sorted_terms'];
    }

    /**
    * get_hierachical_terms_by_post_loop()
    * @var $post (Obj)
    * @var $tax (String)
    * @var $terms (Array)
    * @var $sorted_terms (Array)
    */
    public function get_hierachical_terms_by_post_loop($post, $tax, $terms, $sorted_terms = array())
    {
        foreach ($terms as $key => &$term)
        {
            // get children at current level.
            // $children = wp_get_post_terms($tax, array( 'parent' => $term->term_id, 'hide_empty' => true) );
            $children = wp_get_post_terms( $post->ID, $tax, array( 'parent' => $term->term_id, 'hide_empty' => true) );
            $term->term_permalink = get_term_link( $term->term_id, $tax );

            if( count($children) > 0 )
            {
                // loop through indefinite children (scary).
                $loop = $this->get_hierachical_terms_by_post_loop($post, $tax, $children, $sorted_terms);

                // add returned children to current term.
                $term->children = $loop['children'];
            }
            // Add the current term to final array.
            $sorted_terms[$term->slug] = $term;
        }

        return array('children' => $terms, 'sorted_terms' => $sorted_terms);
    }

    /**
    * get_hierachical_terms()
    * @var $post (Obj)
    * @var $tax (String)
    */
    public function get_hierachical_terms($tax, $term = null, $args = array(), $args_loop = array())
    {
      $sorted_terms = [];
      $args['parent'] = 0;

      if(isset($term)) {
        $args['slug'] = $term;
      }

      $terms = get_terms($tax, $args );

      if(!empty($terms)) {

        $sorted_terms = $this->get_hierachical_terms_loop($tax, $terms, $sorted_terms, $args_loop);
        unset( $sorted_terms['children'] );

        return $sorted_terms['sorted_terms'];
      }
      return null;
    }

    /**
    * get_hierachical_terms_loop()
    * @var $post (Obj)
    * @var $tax (String)
    * @var $terms (Array)
    * @var $sorted_terms (Array)
    */
    public function get_hierachical_terms_loop($tax, $terms, $sorted_terms = array(), $args_loop = array( 'hide_empty' => true) )
    {
        foreach ($terms as $key => &$term)
        {
            // get children at current level.
            // $children = wp_get_post_terms($tax, array( 'parent' => $term->term_id, 'hide_empty' => true) );
            $args_loop['child_of'] = $term->term_id;
            $children = get_terms( $tax, $args_loop, $args_loop  );
            $term->term_permalink = get_term_link( $term->term_id, $tax);

            if( count($children) > 0 )
            {
                // loop through indefinite children (scary).
                $loop = $this->get_hierachical_terms_loop($tax, $children, $sorted_terms);

                // add returned children to current term.
                $term->children = $loop['children'];
            }
            // Add the current term to final array.
            $sorted_terms[$term->slug] = $term;
        }

        return array('children' => $terms, 'sorted_terms' => $sorted_terms);
    }

    public function addToObj($objs = array(), $propertyName, $propertyValue)
    {
        if( is_array($objs) ) {
          foreach ($objs as $key => &$obj)
          {
            if (is_object($obj)) {
              $obj->{$propertyName} = $propertyValue($obj);
            } else {
              return null;
            }
          }
        } else {
          if (is_object($objs)) {
            $objs->{$propertyName} = $propertyValue($objs);
          } else {
            return null;
          }
        }

        return $objs;
    }

    /**
    * @method Query posts grab pagination data
    *
    * @param Array $args
    *
    * @return Array of Objs $posts
    */
    public function query($args)
    {
        if( isset($args['query']) )
        {
            $query = $args['query'];
        }

        query_posts( $query );

        return \Timber::get_posts( $query );

        // return new Timber\PostQuery($query);
        // return new \TimberArchives( $query );

    }

    /**
    * @method Create video URLS
    *
    * @param videoURL
    *
    * @return Updated videoURL
    */
    public function createVideoURLs($videoURL)
    {
      if( !empty( $videoURL ) ) {
        $videoURL = substr($videoURL, 0, -3);
        $sources = [];
        $sources['3gp'] = $videoURL . '3gp';
        $sources['mp4'] = $videoURL . 'mp4';
        $sources['ogv'] = $videoURL . 'ogv';
        $sources['webm'] = $videoURL . 'webm';

        return $sources;
      }
      return null;
    }

}
