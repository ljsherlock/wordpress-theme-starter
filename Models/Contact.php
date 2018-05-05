<?php

namespace Models;

use Includes\Classes\CMB2 as CMB2;
use Includes\Classes\Images as Images;

class Contact extends Page
{
    public function __construct($args)
    {
        parent::__construct($args);
    }

    public function get()
    {
      $this->timber->addContext(array(
          // this can be replaced by the global featured_images Array
          'contact_grid_content' => get_post_meta( $this->post->ID, CMB2::$prefix . 'contact_grid_content', true ),
          'jobsArray' => $this->query(array(
            'query' => array(
              'posts_per_page' => 10,
              'post_type' => 'vacancy'
            ),
          )),
      ));

      // For images using file_list type
      $this->addToObj($this->post, 'grid_images', function($post) {
        $grid_contact = get_post_meta( $this->post->ID, CMB2::$prefix . 'contact_grid_content', true );
        foreach ($grid_contact as &$item) {
          if(isset( $item['images'] )) {
            array_walk($item['images'], function(&$image_url, $key) {
              $image_url = Images::get_all_image_sizes($key, get_intermediate_image_sizes());
            });
          }
        }
        return $grid_contact;
      });

      return parent::get();
    }
}
