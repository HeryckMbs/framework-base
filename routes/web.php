<?php 
namespace app\http;
use app\http\Response;
use app\http\HomeController;


 
 $router::get('/',[HomeController::class, 'oi']);
 $router::post('/a',[HomeController::class, 'teste']);
 