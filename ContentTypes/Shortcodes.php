<?php

namespace ContentTypes;

class Shortcodes
{
    /**
     * Widget Instances
     * @var array
     */
    public $shortcodes = array();

    /**
     * Setup Widget
     * @param shortcodes (array)
     * @return null
     */
    public static function setup( $shortcodes = array() )
    {
        self::register_shortcodes( self::default_shortcodes() );

        //( isset( $shortcodes ) ) ?: $this->shortcodes = merge_array( $this->shortcodes, $shortcodes );

        // self::register_shortcodes( $this->shortcodes );
    }

    private static function register_shortcodes($s)
    {
        foreach ($s as $key => $shortcode) {
            # code...
            add_shortcode( $shortcode['name'], array( 'ContentTypes\\' . $shortcode['class'], 'shortcode' ) );
        }
    }

    private static function default_shortcodes()
    {
        return array(
            array( 'class' =>'Shortcode__Button', 'name' => 'button' ),
        );
    }
}
