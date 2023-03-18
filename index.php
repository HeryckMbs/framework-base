<?php
require __DIR__ . '/vendor/autoload.php';
use app\http\Router;

const URL = 'http://localhost:8000/';

$router = new Router(URL);
include __DIR__ . './routes/web.php';
$router->run()->sendResponse();

