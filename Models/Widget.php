<?php

namespace Models;

class Widget extends Base
{
    public function __construct( $args )
    {
        $this->args = $args;

        $this->widget = $args['widget'];

        if( isset( $this->args['context'] ) )
        {
            $context = $this->args['context'];
            $this->timber->addContext( $context );
        }
    }

    public function get()
    {
        $this->timber->addContext( array('widget' => $this->widget ) );

        // put timber context in the $data variable
        $this->data = array(
            "context" => $this->timber->context,
        );

        // force array for widgets (form does not work otherwise).
        if( $this->widget->type == 'form' )
        {
            return $this->forceArray( $this->data['context'] );
        } else {
            return $this->data['context'];
        }

    }
}
