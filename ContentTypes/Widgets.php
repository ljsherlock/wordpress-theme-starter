<?php

namespace ContentTypes;

class Widgets
{
    /**
     * Widget Instances
     * @var array
     */
    public $widgets = array();

    /**
     * Setup Widget
     * @param widgets (array)
     * @return null
     */
    public function __construct( $widgets = array() )
    {
        ( isset( $widgets ) ) ?: $this->widgets = merge_array( $this->widgets, $widgets );

        $this->default_widgets();
        $this->register_widgets( $this->widgets );
    }

    private function register_widgets()
    {
        foreach ($this->widgets as $key => $widget) {
            # code...
            add_action( 'widgets_init', array( 'ContentTypes\\' . $widget , 'register' ) );
        }
    }

    public function default_widgets()
    {
        $this->widgets = array(
            'Widget__Recent_Posts_All',
        ) ;
    }
}
