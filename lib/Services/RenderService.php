<?php

namespace FriendsOfRedaxo\BePassword\Services;

class RenderService
{
    /** @api */
    public $pathes;
    /** @api */
    public $app;

    public function __construct()
    {
        $this->pathes = [
            __DIR__ . '/../../',
        ];
    }

    /**
     * @api
     * @param mixed $__view 
     * @param array $__params 
     * @return string|false 
     */
    public function render($__view, $__params = [])
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
