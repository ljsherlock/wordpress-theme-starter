<?php

namespace ContentTypes;

class Ajax
{
    public function setup()
    {
      add_action( 'wp_ajax_nopriv_load_more', array(__CLASS__, 'load_more' ));
      add_action( 'wp_ajax_load_more', array(__CLASS__, 'load_more' ));
    }

    public static function load_more()
    {
      $archiveData = $_GET;

      // build a query with data
      $query = array();
      if(!empty( $archiveData['post_type'] )) {
        $query['post_type'] = $archiveData['post_type'];
      }
      if(!empty( $archiveData['taxonomy'] )) {
        $query['tax_query']['taxonomy'] = $archiveData['taxonomy'];
        if(!empty( $archiveData['term'] )) {
          $query['tax_query']['field'] = 'slug';
          $query['tax_query']['term'] = $archiveData['term'];
        }
      }
      if(!empty( $archiveData['posts_per_page'] )) {
        $query['posts_per_page'] = $archiveData['posts_per_page'];
      }
      if(!empty( $archiveData['category'] )) {
        $query['category_name'] = $archiveData['category'];
      }
      if(!empty( $archiveData['offset'] )) {
        $query['offset'] = $archiveData['offset'];
      }

      $args = array('query' => $query);
      if($query['post_type'] == 'staff') {
        $archive = new \Controllers\People($args);
      } else {
        $archive = new \Controllers\Archive($args);
      }

      // compile the data
      $posts = $archive->returnData('posts');
      $context = array('posts' => $posts, 'bem' => array(array( 'block' => 'article--ajax-loaded' )));
      $template = \Timber::compile( array( 'components/'.$query['post_type'].'-loop/'.$query['post_type'].'-loop.twig'), $context);

      echo $template;

      die();
    }

}
