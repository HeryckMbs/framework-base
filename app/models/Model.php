<?php
namespace app\models;
use app\database\Db;
use Exception;

abstract class Model extends Db{
   public static $tableNameModel;
   public static $primaryKey;

   public static function create($columnsData){
        return self::insert(self::$tableNameModel, $columnsData);
   }

   public static function find($identifier){
        $result = Db::table(self::$tableNameModel)->where(self::$primaryKey,'=',$identifier)->get();
        return $result;
   }
   public static function findOrFail($identifier){
        try{
            $result = Db::table(self::$tableNameModel)->where(self::$primaryKey,'=',$identifier)->get();
            if($result){
                return $result;
            }else{
                throw new Exception("Resultado não encontrado", 500);
            }
        }catch(Exception $e){
            print_r("não rolou amigo");
        }
   }

   public static function delete($identifier){

   }


}