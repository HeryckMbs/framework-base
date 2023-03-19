<?php
namespace app\models;
use app\database\Db;
use Exception;

abstract class Model extends Db{
   public static $tableNameModel;
   public static $primaryKey;
   public static $fillables = [];

    public function inicialize($primaryKey, $tableNameModel,$fillables){
        self::$primaryKey =    $primaryKey;
        self::$tableNameModel =     $tableNameModel;
        self::$fillables =     $fillables;
   }
   public static function create($columnsData){
        return self::insert(self::$tableNameModel, $columnsData);
   }

   public static function find($identifier){
        $result = Db::table(self::$tableNameModel)->where(self::$primaryKey,'=',$identifier)->get();
        return $result[array_key_first($result)];
   }
   public static function findOrFail($identifier){
        try{
            $result = Db::table(self::$tableNameModel)->where(self::$primaryKey,'=',$identifier)->get();
            if($result){
                return $result[array_key_first($result)];
            }else{
                throw new Exception("Resultado não encontrado", 500);
            }
        }catch(Exception $e){
            print_r("não rolou amigo");
        }
   }

   public static function deleteModel($identifierValue){
        $result = Db::delete(self::$tableNameModel,self::$primaryKey,$identifierValue);
        return $result;
   }

   

}