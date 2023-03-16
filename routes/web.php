<?php 
namespace app\http;
use app\http\Response;
use app\http\HomeController;

$router->get('/a',[function(){
    dd(HomeController::class);
    return new Response(200, '<form action="/av" method="POST"><input type="text" name="a"><button type="submit">oi</button></form>');
 }]);
 
 $router->post('/av',[function(){
    return new Response(200, 'olAAAAAAAAa');
 }]);
 
 $router->get('/af/{idPagina}',[
    function($idPagina){
    return new Response(200, 'asdas '.$idPagina);
 }]);
 