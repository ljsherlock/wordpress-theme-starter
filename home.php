<?php

$args = array(
  'query' => array(
    'post_type' => 'post',
    'category_name' => 'news',
    'posts_per_page' => 7
   )
 );

$archive = new Controllers\Archive( $args );
$archive->show();
