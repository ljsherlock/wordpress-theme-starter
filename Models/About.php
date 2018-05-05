<?php

namespace Models;

use Includes\Classes\CMB2 as CMB2;
use Includes\Classes\Images as Images;

class About extends Page
{
    public function __construct($args)
    {
        parent::__construct($args);
    }

    public function get()
    {
      $this->addToObj($this->post, 'grid_content', function($post) {
        $grid_content = get_post_meta( $this->post->ID, CMB2::$prefix . 'about_grid_content', true );

        foreach ($grid_content as &$item) {
          $item['content'] = wpautop($item['content']);

          if(isset( $item['images'] )) {
            array_walk($item['images'], function(&$image_url, $key) {
              $image_url = Images::get_all_image_sizes($key, get_intermediate_image_sizes());
            });
          }
        }

        return $grid_content;
      });

      return parent::get();
    }
}
