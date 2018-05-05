<?php

namespace Models;

class Shortcode__Button extends Shortcode
{
    public function __construct( $args = array() )
    {
        parent::__construct( $args );

    }

    public function add_attributes()
    {
        $context = array(
            'attrs' => array(
                'href' => $this->attrs['href'],
            ),
            'bem' => array(
                array( 'block' => 'btn btn--primary' )
            ),
            'text' => array( 'value' => $this->attrs['title'] ),
        );

        if(isset( $this->attrs['icon'] ))
            $context['icon']['name'] = $this->attrs['icon'];

        $this->timber->addContext( $context );

    }

}
