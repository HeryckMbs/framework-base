<?php
namespace app\http;
use app\database\Db;

class HomeController{
    public static function oi(){
        DB::insert('funcionarios', ['nomeFunc' => 'Dodge']);
        return new Response(200, 'oi');
    }

    public static function teste($request){
        dd($request['a']);
    }

    public static function teste2($id, $request){
        dd($request['a'], $id);
    }

}
