<?php

namespace Models;

use Includes\Classes\CMB2 as CMB2;
use Includes\Classes\Images as Images;

class Single__Work extends Single
{

    public $sidebars = array();

    /**
    *   @method __construct
    *   @return get
    **/
    public function __construct($args)
    {
        parent::__construct($args);

        $this->args = ['post_type' => 'work'];
    }

    /**
    *   @method get
    *
    *   @return parent::get()
    *
    **/
    public function get()
    {
      return parent::get();
    }

    /**
    * @method postDataCallback
    *
    * @param $postObj (Obj)
    *
    * @return Updated $postObj
    */
    public function postDataCallback($postObj)
    {
      // ** These are used often. I could create a method to prevent repetiton **
      $this->addToObj($postObj, 'projectGallery', function($post) {
        $grid_content = get_post_meta( $post->ID, CMB2::$prefix . 'project_gallery', true );
        if(is_array($grid_content)) {
          foreach ($grid_content as &$item) {
            array_walk($item['file_list'], function(&$list, $key) {
              $list = Images::get_all_image_sizes($key, get_intermediate_image_sizes());
            });
          }
        }
        return $grid_content;
      });

      // Get the video field
      // Create all supporting URLs for the srcs
      $this->addToObj($postObj, 'projectVideo', function($post) {
        $videoURL = get_post_meta( $post->ID, CMB2::$prefix . 'project_video', true );
        return $this->createVideoURLs($videoURL);
      });

      // add the Testimonial field to the post Obj
      $this->addToObj($postObj, 'projectTestimonial', function($post) {
        return get_post_meta( $post->ID, CMB2::$prefix . 'project_testimonial', true );
      });

      return $postObj;
    }
}
