<?php

namespace Includes;

class App
{
    public static function init()
    {
        $work = new \ContentTypes\Work();
        $work->args['supports'] = array_merge($work->args['supports'], array('excerpt'));
        $work->register();

        $people = new \ContentTypes\People();
        $people->register();

        $job = new \ContentTypes\Job();
        $job->register();

        $ajax = new \ContentTypes\Ajax();
        $ajax->setup();
    }
}
