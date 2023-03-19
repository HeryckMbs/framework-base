<?php
namespace app\database;
use PDO;
use PDOException;
use app\database\QueryBuilder;
/**
 * 
 * Classe do Banco de Dados, responsável pelas operações SELECT, INSERT,UPDATE E DELETE
 * 
 * Métodos que alteram registro estão restritos apenas a classe do Banco e por herança para a classe de Model
 * 
 * Para buscas mais avançadas é utilizada a classe QueryBuilder, onde o SELECT é maior aprofundado
 */
class Db {



    private static $host = 'localhost';
    private static $database = 'postgres';
    private static $user = 'postgres';
    private static $password  = '12345';
    /**
     * 
     * Url de conexão ao banco
     */
    private static $urlConnection;
    /**
     * 
     * Guarda a conexão do banco
     */
    private static $connection;


    /**
     * Realiza a conexão com o banco de dados e atualiza a variável connection
     */
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
    
    /**
     * 
     * Encerra a conexão com o banco de dados
     */
    private static function killConnection(){
        self::$connection = null;    
    }

    /**
     * Executa uma query já pronta
     */
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
    /**
     * Inicia o queryBuilder informando a tabela para começar a montar a query
     */
    public static function table($tableName){
        QueryBuilder::setQuery($tableName);
        return new QueryBuilder;
    }
    /**
     * Monta a query de inserção com bind parameters
     */
    protected static function insert($tableName, $data){
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
    protected static function delete($tableName, $identifier, $value){
        $delete = "DELETE FROM $tableName WHERE $identifier = $value";
        self::makeConnection();
        $resultado = self::$connection->prepare($delete)->execute();
        self::killConnection();
        return $resultado;
    }

}