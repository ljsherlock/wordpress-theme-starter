<?php

namespace Models;

class Widget__Recent_Posts_All extends Widget
{
    public function get()
    {
        // get posts. add them to the context.
        $posts = \Timber::get_posts(array(
            'post_type' => $this->widget->selected_post_type,
            'posts_per_page' => $this->widget->posts_per_page
        ));

        $this->timber->addContext( array( 'posts' => $posts ) );

        return parent::get();
    }
}
