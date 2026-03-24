<?php

class Controller
{
    public function hello($request)
    {
        $name = $request->query('name', 'Guest');

        return Response::json([
            "message" => "Hello $name",
        ]);
    }

    public function postHello($request)
    {
        $name = $request->input('name', 'Guest');

        return Response::json([
            "message" => "Hello (POST) $name",
        ]);
    }
}