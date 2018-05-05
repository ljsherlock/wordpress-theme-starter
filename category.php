<?php

$args = array( 'query' => array(
    'post_type' => 'post',
    'category_name' => get_query_var('category_name'),
    'posts_per_page' => 7,
    'ajax_posts_per_page' => 6
   )
);

$archive = new Controllers\Archive( $args );
$archive->template = 'archive--category';
$archive->show();
