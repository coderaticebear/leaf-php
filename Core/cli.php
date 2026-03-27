<?php

if (php_sapi_name() !== 'cli') {

    echo "This script can only be run in CLI";
    exit;
}

class CLI{
    private $configData;

    public function __construct() {
        $this->configData = $this->validateLeafConfig();
    }
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
            case 'version':
                $this->version();
                break;
            case 'validate':
                $this->validateLeafConfig();
                break;
            default:
                echo "Unknown command: $command\n";
                $this->help();
                break;
        }
    }

    private function help() {


        // Colors
        $green  = "\033[32m";
        $cyan   = "\033[36m";
        $yellow = "\033[33m";
        $white  = "\033[37m";
        $bold   = "\033[1m";
        $reset  = "\033[0m";

        echo "\n";

        // ASCII Banner (Green + Bold)
        echo $green . $bold;
        echo "██╗     ███████╗ █████╗ ███████╗    ██████╗ ██╗  ██╗██████╗ \n";
        echo "██║     ██╔════╝██╔══██╗██╔════╝    ██╔══██╗██║  ██║██╔══██╗\n";
        echo "██║     █████╗  ███████║█████╗      ██████╔╝███████║██████╔╝\n";
        echo "██║     ██╔══╝  ██╔══██║██╔══╝      ██╔═══╝ ██╔══██║██╔═══╝ \n";
        echo "███████╗███████╗██║  ██║██║         ██║     ██║  ██║██║     \n";
        echo "╚══════╝╚══════╝╚═╝  ╚═╝╚═╝         ╚═╝     ╚═╝  ╚═╝╚═╝     \n";
        echo $reset;

        echo "\n";

        // Title
        echo $bold . $white . "Leaf-PHP CLI\n" . $reset;
        echo $white . "──────────────────────────────────────────────\n" . $reset;

        // Commands
        echo "\n" . $yellow . "Commands:\n\n" . $reset;

        echo "  " . $cyan . "create-service" . $reset . "   Create a new microservice\n";
        echo "                   → " . $green . "php core create-service <service_name>" . $reset . "\n\n";

        echo "  " . $cyan . "serve" . $reset . "            Run a service locally\n";
        echo "                   → " . $green . "php core serve <service_name> [port]" . $reset . "\n\n";

        echo "  " . $cyan . "version" . $reset . "          Find Version\n";
        echo "                   → " . $green . "php core version" . $reset . "\n\n";

        echo $white . "──────────────────────────────────────────────\n" . $reset;

        // Example
        echo $yellow . "Example:\n" . $reset;
        echo "   " . $green . "php core create-service user-service\n" . $reset;
        echo "   " . $green . "php core serve user-service 8000\n" . $reset;

        echo "\n";
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
        echo "Creating Index";
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
        echo "Creating controller";
        $controllerContent = <<<PHP
            <?php

            class Controller
            {
                public function hello(\$request)
                {
                    \$name = \$request->query('name', '$serviceName');
                    return Response::json([
                        "message" => "Hello from \$name",
                    ]);
                }

                public function postHello(\$request)
                {
                    \$name = \$request->input('name', '$serviceName');
                    return Response::json([
                        "message" => "Hello (POST) \$name from \$name",
                    ]);
                }
            }
        PHP;

        file_put_contents("$serviceDir/Controller.php", $controllerContent);

        //update config file

        $leafServices[] = $leafConfig->services;



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

    private function version() {

        echo $this->configData['version']."\n";
    }

    private function validateLeafConfig() {

        //Color code
        $green  = "\033[32m";
        $red    = "\033[31m";

        $reset  = "\033[0m";

        if (!file_exists(__DIR__.'/../leaf.json')) {
            echo "$red Couldnot read config file! Please check if file exists! $reset\n";
            exit;
        }

        $json = json_decode(file_get_contents(__DIR__.'/../leaf.json'), true);

        if(json_last_error() == JSON_ERROR_NONE) {
            


            if(!array_key_exists('version', $json)) {
                echo "$red Unable to read configuration file $reset\n";
                exit;
            }

            if(array_key_exists('services', $json)) {
                foreach($json['services'] as $index => $service) {
                   
                    $serviceName = $service['name'];
                    $serviceDir = __DIR__."/../$serviceName";

                    if(!is_dir($serviceDir)) {
                        echo "$red$serviceName not found$reset\n";
                        unset($json['services'][$index]);
                    } else {
                        echo "$serviceName found\n";
                    }
                }
            }
            $json['services'] = array_values($json['services']);
            file_put_contents(__DIR__.'/../leaf.json', json_encode($json, JSON_PRETTY_PRINT));
            return $json;
        } 
        
        echo "$red Unable to read configuration file $reset\n";
        exit;
        
    }
}

$cli = new CLI();

$cli->run($argv);