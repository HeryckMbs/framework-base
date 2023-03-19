<?php
namespace app\database;
use PDO;
use PDOException;
use app\database\QueryBuilder;
class Db {
    private static $host = 'localhost';
    private static $database = 'postgres';
    private static $user = 'postgres';
    private static $password  = '12345';

    private static $urlConnection;

    private static $connection;



    private static function makeConnection(){
        self::$urlConnection = "pgsql:host=".self::$host.";port=5432;dbname=".
                    self::$database. ";user=".self::$user.";password=".self::$password."";
        try{
            self::$connection = new PDO(self::$urlConnection);
        }catch(PDOException $e){
            echo '<pre>';
                print_r($e->getMessage());
            echo '</pre>';
        }
    }
    

    private static function killConnection(){
        self::$connection = null;    
    }

    // public static function select($tableName,$whereAnd = [], $orderBy = []){
    //     self::makeConnection();
    //     $query = "SELECT * FROM ". $tableName . " ";
    //     if(count($whereAnd) > 0){
    //         $whereSql = 'WHERE ';
    //         foreach($whereAnd as $variavel => $valor){
    //             $whereSql .=  "$variavel=:$variavel";
    //         }
    //         $query .= $whereSql;
    //     }
    //     $database = self::$connection->prepare($query);
    //     if($database->execute($whereAnd)){
    //         $result = $database->fetchAll(PDO::FETCH_ASSOC);
    //         self::killConnection();
    //         return $result;
    //     }else{
    //         self::killConnection();
    //         return 'Resultado não encontrado';
    //     }

    // }

    public static function queryRaw($query){
       try{
            self::makeConnection();
            $database = self::$connection->prepare($query);
            if($database->execute()){
                $result = $database->fetchAll(PDO::FETCH_ASSOC);
                self::killConnection();
                return $result;
            }else{
                self::killConnection();
                return false;
            }
       }catch(PDOException $e){

       }
    }

    public static function table($tableName){
        QueryBuilder::setQuery($tableName);
        return new QueryBuilder;
    }

    public static function insert($tableName, $data){
        $insert = "INSERT INTO $tableName (";
        $bindParameters = '';
        $insertColumns = [];
        foreach($data as $coluna => $valor){
            $insertColumns[] = $coluna;
        }

        $insert .= implode(",",$insertColumns). ") VALUES(";
        $insertColumns = [];
        foreach($data as $coluna => $valor){
            $insertColumns[] = ":".$coluna;
        }
        $insert .= implode(",",$insertColumns) . ")";
        try{
            self::makeConnection();
            $database = self::$connection->prepare($insert);
            if($database->execute($data)){
                self::killConnection();
                return true;
            }else{
                throw new PDOException("Não foi possivel inserir o registro");
            }
        }catch(PDOException $e){
            echo '<pre>';
            print_r($e->getMessage());
            echo '</pre>';
        }
       
    }

}