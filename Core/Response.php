<?php

class Response{

    public static function json($data, $status = 200) {

        http_response_code($status);
        header('Content-Type: application/json');

        echo json_encode($data);
        exit;

    }

    public static function text($data, $status = 200) {
        
        http_response_code($status);
        header('Content-Type: text/plain');

        echo $message;
        exit;
    }
}