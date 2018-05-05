<?php

namespace Models;

use Includes\Classes\CMB2 as CMB2;

class Jobs extends Archive
{
    public function __construct($args)
    {
        parent::__construct($args);

        $this->args['query']['posts_per_page'] = 6;
        $this->args['query']['post_type'] = 'vacancy';
    }
}
