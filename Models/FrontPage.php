<?php

namespace Models;

use Includes\Classes\CMB2 as CMB2;
use Includes\Classes\Images as Images;

class Frontpage extends Page
{
    /**
    * __construct
    * @param array $args Model arguments
    */
    public function __construct( $args )
    {
        parent::__construct( $args );
    }

    public function get()
    {
        $this->frontpagePosts = get_post_meta( $this->post->ID, CMB2::$prefix . 'front_page_posts_group', true );

        array_walk($this->frontpagePosts, function(&$post, $key) {

          if ( !empty($post['post']) ) {

            $single = new Single( array( 'id' => $post['post'] ) );
						$single->additionalPostData();
            $post['post'] = $single->post;

            $videoURL = get_post_meta( $post['post']->ID, CMB2::$prefix . 'project_video', true );
            $post['post']->projectVideo = $this->createVideoURLs($videoURL);

            if ( !empty( $post['customImage'] ) ) {
                $post['customImage'] = Images::get_all_image_sizes($post['customImage_id'], get_intermediate_image_sizes());
            }

          // if link we want to turn into a term link
          } else if( isset($post['link']) ) {

            $post['link'] = new \TimberTerm( $post['link'] );
            $post['link']->permalink = get_term_link( $post['link']->term_id, 'type');
          }

          return $post;
        });

        $this->timber->addContext( array(
            'homepagePosts' => $this->frontpagePosts,
        ) );

        return parent::get();
    }

}
