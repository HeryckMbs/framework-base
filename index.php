<?php
require __DIR__ . '/vendor/autoload.php';
use app\http\Router;
use app\http\HomeController;

const URL = 'http://localhost:8000/';
$t = new HomeController();

include __DIR__ . './routes/web.php';
$router = new Router(URL);
$router->run()->sendResponse();

