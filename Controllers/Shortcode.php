<?php

namespace Controllers;

/**
* @uses Controller\Base
*/

class Shortcode extends Base
{
    public $modelName = 'Shortcode';

    /**
     * Controller construct: adds attributes sent to context.
     *
     * @return void.
     */
    public function __construct( $atts, $content, $tag )
    {
        // run parent construct because we depend on it.
        parent::__construct();

        // Add the attrs array to Model
        $this->model->attrs = $atts;

        $this->model->add_attributes();
    }

    /**
    * Gather model data and render twig template
    * @return null
    */
    public function show()
    {
        // get data
        $data = $this->model->get();

        // render data
        return $this->timber->compile( $this->template, $data );
    }

}
