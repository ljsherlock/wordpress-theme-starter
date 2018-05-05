<?php

namespace Controllers;

class Widget__Template extends Widget
{
    public $modelName = 'Widget__Template';

    public function get_widget()
    {
        $this->model->get_widget();
    }

    public function get_form()
    {
        $this->model->get_form();
    }
}
