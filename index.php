<?php
require_once 'router.php';

$router = Router::getInstance('Controller');

$router->get('/hello', 'hello');
$router->post('/hello','postHello');

$router->dispatch();
