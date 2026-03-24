<?php

class Controller
{

    public function hello()
    {
        $name = $_GET['name'] ?? 'Guest';

        echo json_encode([
            "message" => "Hello $name",
        ]);
    }

    public function postHello()
    {
        $input = file_get_contents("php://input");
        $data  = json_decode($input, true);

        $name = $data['name'] ?? 'Guest';

        echo json_encode([
            "message" => "Hello (POST) $name",
        ]);
    }

}
