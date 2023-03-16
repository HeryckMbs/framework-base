<?php
require __DIR__ . '/vendor/autoload.php';
use app\http\Router;
use app\http\HomeController;

const URL = 'http://localhost:8000/oi';
$t = new HomeController();

$router = new Router(URL);

include __DIR__ . './routes/web.php';

$router->run()->sendResponse();

