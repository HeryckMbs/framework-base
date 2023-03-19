<?php
namespace app\http;
use app\database\Db;
use app\models\Teste;

class HomeController{
    public static function oi(){
        $t = new Teste();
        $ta = $t->findOrFail(1);
        dd($ta);
        return new Response(200, 'oi');
    }

    public static function teste($request){
        dd($request['a']);
    }

    public static function teste2($id, $request){
        dd($request['a'], $id);
    }

}
