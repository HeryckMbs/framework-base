<?php 
namespace app\http;
use \Closure;
use Exception;
use \Db;
use \ReflectionFunction;

class Router{
    private $url = '';
    private $prefix = '';

    private static $routes = [];

    private $request;

    public function __construct($url){
        $this->url = $url;
        $this->request = new Request();
        $this->setPrefix();
    }

    private function setPrefix(){
        $parseUrl = parse_url($this->url);
        $this->prefix = $parseUrl['path'] ?? '';
    }

    private static function addRoute($method, $route, $params = []){
        $params['controller'] = $params[0];
        $params['function'] = $params[1];
        unset($params[0]);
        unset($params[1]);            
        $params['variables'] = [];

        $paternVariable = "/{(.*?)}/";
        if(preg_match_all($paternVariable,$route, $matches)){
            $route  = preg_replace($paternVariable, '(.*?)' , $route);
            $params['variables'] = $matches[1];
        }
        
        $patternRoute = '/^'.str_replace('/', '\/',$route). '$/';
        self::$routes[$patternRoute][$method] = $params;
    }

    private function getUri(){
        $uri = $this->request->getUri();
        return $uri;
    }
    private function getRoute(){
        $uri = $this->getUri();

        $httpMethod = $this->request->getHttpMethod();
        foreach(self::$routes as $pattern => $methods){
            if(preg_match($pattern, $uri, $matches)){
                if(isset($methods[$httpMethod])){
                    unset($matches[0]);
                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $matches);
                    if(in_array($httpMethod,['POST','UPDATE'])){
                        $methods[$httpMethod]['variables']['request'] = $this->request->getPostVars();
                    }
                    return $methods[$httpMethod];
                }
                throw new Exception('Método não permitido', 405);
            }
        }
        throw new Exception('Página não encontrada', 404);
    }
    public function run (){
        try{
            $route = $this->getRoute();
           
            if(!isset($route['controller'])){
                throw new Exception('A URL não pode ser processada');
            }
            
            $args = [];
            
            foreach($route['variables'] as $nome => $variaveis){
                $args[$nome] = $route['variables'][$nome] ?? '';
            }
            return call_user_func_array([$route['controller'], $route['function']], $args);
         }catch(Exception $e){
            return new Response($e->getCode(), $e->getMessage());
        }
    }

    public static function get($route, $params= []){
        return self::addRoute('GET', $route, $params);
    }
    public static function post($route, $params= []){
        return self::addRoute('POST', $route, $params);
    } 
    public static function put($route, $params= []){
        return self::addRoute('PUT', $route, $params);
    }
    public static function delete($route, $params= []){
        return self::addRoute('DELETE', $route, $params);
    }
}