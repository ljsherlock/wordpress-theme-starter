<?php

namespace Controllers;

class Search extends Page
{
    public $modelName = "Search";
    public $template = "search";

    public $request = '';

    public function __construct()
    {
        parent::__construct();

        if( $s = get_query_var('s') !== '' )
        {
            $this->model->add('s', $s);
        }
    }
}
