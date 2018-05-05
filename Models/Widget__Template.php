<?php

namespace Models;

class Widget__Template extends Widget
{
    public $options = [];

    public function get_widget()
    {
        $res = file_get_contents( LJS_URL .'/' . $this->widget->template );

        $this->timber->addContext( array(
            'template' => $res
        ) );
    }

    public function get_form()
    {
        $this->widget->instances->template->options = $this->get_options();
    }

    public function get_options()
    {
        foreach( get_page_templates() as $key => $template )
        {
            $this->options[$key]['value'] = $template;
            $this->options[$key]['text'] = $key;
            $this->options[$key]['selected'] = ( $template == $this->widget->instances->template->value ) ? true : false;
        }
        return $this->options;
    }

}
