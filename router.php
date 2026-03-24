<?php

class Router
{
    private static $instance = null;

    private $routes = [];
    private function __construct($controllerName)
    {
        require_once __DIR__ . "/$controllerName.php";
        $this->controller = new $controllerName();
    }

    public static function getInstance($controllerName): self
    {

        if (self::$instance === null) {
            self::$instance = new self($controllerName);
        }

        return self::$instance;
    }

    public function get($path, $method)
    {

        $this->routes[] = [
            'method' => 'GET',
            'path'   => $path,
            'action' => $method,
        ];
    }

    public function post($path, $method)
    {

        $this->routes[] = [
            'method' => 'POST',
            'path'   => $path,
            'action' => $method,
        ];
    }

    public function dispatch()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri    = $_SERVER['REQUEST_URI'];
        $path   = parse_url($uri, PHP_URL_PATH);
        foreach ($this->routes as $route) {
            if ($method === $route['method'] && $path === $route['path']) {
                return $this->execute($route['action']);
            }

        }

        http_response_code(404);
        echo "404 Not Found";
    }

    private function execute($methodName)
    {
        return $this->controller->$methodName();
    }

}
