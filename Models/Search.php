<?php

namespace Models;

class Search extends Page
{
    public function __construct( $args )
    {
        $this->search_args = $this->search();

        parent::__construct( $args );
    }

    public function get()
    {
        if( isset( $this->search_args ) )
        {
            $this->timber->addContext( array( 'posts' => \Timber::get_posts($this->search_args) ) );
        }

        return parent::get();
    }

    public function search()
    {
        if( isset( $this->data['s'] ) )
        {
            return array( 'app' => array(
              'post_type' => get_post_types(),
              'post_status' => 'publish',
              's' => $this->data['s'],
            ));
        }
    }

}
