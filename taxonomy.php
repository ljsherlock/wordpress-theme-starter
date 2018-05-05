<?php
$args = array('query' => array('posts_per_page' => 16 ));
$archive = new Controllers\Archive($args);
$archive->template = array('pages/archive/work');
$archive->show();
