<?php

$single = new Controllers\Single();
$single->model->additionalPostData();
$single->model->addRelatedPosts();
$single->show();
