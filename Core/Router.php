<?php

class Router
{
    private $routes = [];
    private $controller;
    private $request;

    public function __construct($controller)
    {
        $this->controller = $controller;
        $this->request    = new Request();
    }

    public static function getInstance($controllerName): self
    {
        return new self($controllerName);
    }

    public function get($path, $method)
    {
        $this->addRoute('GET', $path, $method);
    }

    public function post($path, $method)
    {
        $this->addRoute('POST', $path, $method);
    }

    private function addRoute($httpMethod, $path, $method)
    {
        $this->routes[] = [
            'method' => $httpMethod,
            'path'   => $path,
            'action' => $method,
        ];
    }

    public function dispatch()
    {
        $method = $this->request->method();
        $path   = $this->request->path();
        foreach ($this->routes as $route) {
            if ($method === $route['method'] && $path === $route['path']) {
                return $this->execute($route['action']);
            }

        }

        return Response::json([
            "error" => "Not Found"
        ], 404);
    }

    private function execute($methodName)
    {
        return $this->controller->$methodName($this->request);
    }

}
