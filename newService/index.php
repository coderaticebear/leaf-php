
<?php
require_once __DIR__.'/../Core/Core.php';
require_once __DIR__.'/Controller.php';

$controller = new Controller();
$router = Core::run($controller);

$router->get('/hello', 'hello');
$router->post('/hello','postHello');

$router->dispatch();
