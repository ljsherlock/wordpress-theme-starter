<?php

namespace ContentTypes;

abstract class Widget extends \WP_Widget
{
    /**
     * Widget Instance
     * @var Object
     */
    protected $instance = '';

    /**
     * Widget Instances
     * @var Object
     */
    public $instances = '';

    /**
     * Widget name
     * @var string
     */
    public $name = '';

    /**
     * Description
     * @var string
     */
    public $desc = '';

    /**
     * Twig template location (.twig added by the Timber worker).
     * @var string
     */
    public $template = '';

    /**
     * Registers the widget with the WordPress Widget API.
     *
     * @return void.
     */
    public static function register() {
        register_widget( __CLASS__ );
    }

    /**
     * Setup Widget
     */
    public function __construct()
    {
        $widget_ops = array(
          'classname' => 'wp_widget_' . $this->className,
          'description' => __( $this->desc ),
          'customize_selective_refresh' => true,
        );
        parent::__construct( $this->className, __( $this->name ), $widget_ops );
        $this->alt_option_name = 'wp_widget_' . $this->className;
    }

    public function widget( $args, $instance ) {
        if ( ! isset( $args['widget_id'] ) ) {
            $args['widget_id'] = $this->id;
        }
    }


    public function update( $new_instance, $old_instance )
    {
        // Updates a particular instance of a widget
        $instance = $old_instance;

        return $instance;
    }

    public function form( $instance ) {

    }

}
