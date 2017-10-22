<?php

namespace BePassword\Services;

class RenderService
{
    public $pathes;
    public $app;

    public function __construct()
    {
        $this->pathes = array(
            __DIR__ . '/../../',
        );
    }

    public function render($__view, $__params = array())
    {
        foreach ($this->pathes as $__path) {
            if (file_exists($__path . '/' . $__view)) {
                ob_start();
                extract($__params);
                include $__path . '/' . $__view;
                return ob_get_clean();
            }
        }
        return $__view . ' not found';
    }
}
