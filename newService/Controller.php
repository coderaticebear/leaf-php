    <?php

    class Controller
    {
        public function hello($request)
        {
            return Response::json([
                "message" => "Hello from $name",
            ]);
        }

        public function postHello($request)
        {
            $name = $request->input('name', 'Guest');
            return Response::json([
                "message" => "Hello (POST) $name from $name",
            ]);
        }
    }