<?php


class Request {

    private $get;
    private $post;
    private $server;
    private $body;



    public function __construct() {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->server = $_SERVER;
        $this->body = file_get_contents("php://input");
    }


    public function method() {
        return $this->server['REQUEST_METHOD'] ?? 'GET';
    }

    public function path() {

        $uri = $this->server['REQUEST_URI'] ?? '/';

        return parse_url($uri, PHP_URL_PATH);
    }


    public function query($key, $default = null) {
        return $this->get[$key] ?? $default;
    }

    public function input($key, $default = null) {
        $data = json_decode($this->body, true);

        if(json_last_error() == JSON_ERROR_NONE) {

            return $data[$key] ?? $default;
        }

        return $this->body[$key] ?? $default;
    }

    public function all() {
        $data = json_decode($this->body, true);

        if(json_last_error() == JSON_ERROR_NONE) {

            return $data;
        }

        return $this->body;
    }
}