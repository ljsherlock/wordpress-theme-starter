<?php

namespace Workers;

class Timber
{
    public $timber = null;

    public $context = null;

    public function __construct($templateDir, $config = array())
    {
        // set config property
        $this->config = $config;

        //initialize Timber
        $this->timber = new \Timber\Timber;

        //required to get ajax request input.
        $request_body = file_get_contents('php://input');
        $data = json_decode($request_body);
        $this->addContext( array( 'request_body' => $data ));

        // set template location
        \Timber::$locations = $templateDir;

        // public static $locations;
        // public static $dirname = 'views';
        // public static $twig_cache = false;
        // public static $cache = false;
        // public static $auto_meta = true;
        // public static $autoescape = false;
    }

  public function render($path, $data)
  {
      if( is_array( $path ) ) {
          $path = $this->createPath($path);
      } else {
          $path .= '.twig';
      }
    \Timber::render($path, (array)$data);
  }

  private function createPath($paths)
  {
      $tempPaths = array();
      foreach ($paths as $key => $path)
      {
          array_push( $tempPaths, $path . '.twig' );
      }
      return $tempPaths;
  }

  public function compile($path, $data)
  {
    return \Timber::compile($path . ".twig", (array)$data);
  }

  public function addExtension($ext)
  {
    return $this->timber->addExtension($ext);
  }

  public function addFilter($filter)
  {
    return $this->timber->addFilter($filter);
  }

  public function addFunction($filter)
  {
    return $this->timber->addFunction($filter);
  }

  public function addContext($context)
  {
      if( isset( $this->context ) )
      {
          $this->context = array_merge( $this->context, $context );
          return;
      }
      $this->context = $context;
  }

}
