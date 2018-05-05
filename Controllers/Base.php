<?php

namespace Controllers;

abstract class Base
{
    /**
    * Controller args
    * @var object
    */
    protected $args = array();

    /**
    * Element attributes
    * @var object
    */
    protected $attrs = array();

    /**
    * Timber worker
    * @var \wptwig\Workers\Twig
    */
    protected $timber = null;

    /**
    * Model object
    * @var object
    */
    public $model = null;

    /**
    * Name of model to be used in Controller;
    * to be set in classes that extend from Base
    * @var string
    */
    public $modelName = '';

    /**
    * Location of template; to be set in classes
    * that extend from Base
    * @var string
    */
    public $template = 'templates/dump';

    /**
    * Setup model and timber/twig
    * @param object args Controller arguments.
    * @return null
    */
    public function __construct($args = array())
    {
        // Allows for a custom template string to be defined in the Controller call argument.
        $this->args = $args;

        // initialize the Timber Workers
        // Pass base template locations
        $this->timber = new \Workers\Timber( array(
            get_template_directory() . "/templates",
            get_template_directory() . "/templates/components",
            get_template_directory() . "/templates/base",
            get_template_directory() . "/templates/macros",
            get_template_directory() . "/templates/pages",
            get_template_directory() . "/templates/templates",
            get_template_directory() . "/src/templates",
            get_template_directory() . "/src/templates/components",
            get_template_directory() . "/src/templates/base",
            get_template_directory() . "/src/templates/macros",
            get_template_directory() . "/src/templates/pages",
            get_template_directory() . "/src/templates/templates",
        ));

        // Create the dynamic model name
        $modelName = "\\Models\\{$this->modelName}";

        // Initialize Model
        $this->model = new $modelName( $args );

        // Add the timber object to Model
        $this->model->timber = $this->timber;
    }

    /**
    * Gather model data and render twig template
    * @return null
    */
    public function show()
    {
        // get data
        $data = $this->model->get();

        // Allows for a custom template string to be defined in the Controller call argument.
        $this->template = ( isset( $args['template'] ) ) ? $args['template'] : $this->template;

        // render data
        $this->timber->render($this->template, $data);
    }

    /**
    * Gather model data and return
    * @return null
    */
    public function returnData($key = null)
    {
      if(isset($key)) {
        return $this->model->get()[$key];
      }
        return $this->model->get();
    }

    /**
    * Gather model data and return
    * @return null
    */
    public function dumpData($key = null)
    {
      if(isset($key)) {
         die(var_dump( $this->model->get()[$key] ));
      }
      die(var_dump( $this->model->get() ));
    }

}
