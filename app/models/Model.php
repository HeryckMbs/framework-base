<?php

namespace app\models;

use app\database\Db;
use Exception;

/**
 * Classe Abstrada das Models
 * Se comunica com o banco de dados de forma através de herança da classe Db
 * Os atributos referente as tabelas do banco devem ser escritas em letras minusculas
 * 
 */
abstract class Model extends Db
{
    public static $tableNameModel;
    public static $primaryKey;
    public static $fillables = [];
    public $object = null;


    public function inicialize($primaryKey, $tableNameModel, $fillables)
    {
        self::$primaryKey =    $primaryKey;
        self::$tableNameModel =     $tableNameModel;
        self::$fillables =     $fillables;
    }
    public function __construct()
    {
        $this->inicialize(self::$primaryKey, self::$tableNameModel, self::$fillables);
    }
    public static function create($columnsData)
    {
        return self::insert(self::$tableNameModel, $columnsData);
    }

    public  function find($identifier)
    {
        $result = Db::table(self::$tableNameModel)->where(self::$primaryKey, '=', $identifier)->get();
        $this->object = $result[array_key_first($result)];
        return $this;
    }
    public function findOrFail($identifier)
    {
        try {
            $result = Db::table(self::$tableNameModel)->where(self::$primaryKey, '=', $identifier)->get();
            if ($result) {
                $this->object = $result[array_key_first($result)];
                return $this;
            } else {
                throw new Exception("Resultado não encontrado", 500);
            }
        } catch (Exception $e) {
            print_r("não rolou amigo");
        }
    }

    public static function deleteModel($identifierValue)
    {
        $result = Db::delete(self::$tableNameModel, self::$primaryKey, $identifierValue);
        return $result;
    }


    public function save()
    {
        self::update(self::$tableNameModel, $this->object, self::$primaryKey);
    }
}
