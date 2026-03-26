<?php

if (php_sapi_name() !== 'cli') {

    echo "This script can only be run in CLI";
    exit;
}

class CLI{

    public function run($argv) {
        
        if(!isset($argv[1])) {
            $this->help();
            exit;
        }

        $command = $argv[1];

        switch($command) {
            case 'create-service': 
                $this->createService($argv[2] ?? null);
                break;
            case 'help':
                $this->help();
                break;
            case 'serve':
                $this->serve($argv[2] ?? null, $argv[3] ?? null);
                break;
            default:
                echo "Unknown command: $command\n";
                $this->help();
                break;
        }
    }

    private function help() {

        echo "Leaf-PHP CLI \n";
        echo "Usage: \n";
        echo "php core create-service [service_name]\n";
    }

    private function createService($serviceName) {
        if(!$serviceName) {
            echo "Service name requried\n";
            exit;
        }

        $serviceDir = __DIR__."/../$serviceName";

        if(is_dir($serviceDir)) {
            echo "Service already exists!\n";
            exit;
        }

        //Create service folder

        mkdir($serviceDir, 0777, true);

        //Create index.php

        $indexData = <<<PHP

        <?php
        require_once __DIR__.'/../Core/Core.php';
        require_once __DIR__.'/Controller.php';

        \$controller = new Controller();
        \$router = Core::run(\$controller);

        \$router->get('/hello', 'hello');
        \$router->post('/hello','postHello');

        \$router->dispatch();

        PHP;

        file_put_contents("$serviceDir/index.php", $indexData);

        $controllerContent = <<<PHP
            <?php

            class Controller
            {
                public function hello(\$request)
                {
                    return Response::json([
                        "message" => "Hello from \$name",
                    ]);
                }

                public function postHello(\$request)
                {
                    \$name = \$request->input('name', 'Guest');
                    return Response::json([
                        "message" => "Hello (POST) \$name from \$name",
                    ]);
                }
            }
        PHP;

        file_put_contents("$serviceDir/Controller.php", $controllerContent);

        echo "Service $serviceName created successfully at $serviceDir\n";
    }

    private function serve($serviceName, $port) {
            
        if(!$serviceName) {
            echo "Service name requried\n";
            exit;
        }

        $serviceDir = __DIR__."/../$serviceName";

        if(!is_dir($serviceDir)) {
            echo "Service doesnot exists!\n";
            exit;
        }

        // shell_exec()
        $indexPath = $serviceDir . '/index.php';

        if(!file_exists($indexPath)) {
            echo "Index not found\n";
            exit;
        }

        $host = "localhost:$port";
        echo "$serviceName is running at $host\n";

        passthru("php -S $host -t $serviceDir");

       

    }
}

$cli = new CLI();

$cli->run($argv);