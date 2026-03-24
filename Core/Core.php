<?php

require_once __DIR__.'/Request.php';
require_once __DIR__.'/Response.php';
require_once __DIR__.'/Router.php';

class Core {


    public static function run($controllerName) {

        $router = Router::getInstance($controllerName);
        return $router;
    }
}