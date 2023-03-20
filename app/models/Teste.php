<?php
namespace app\models;

class Teste extends Model{
    public static $tableNameModel = 'funcionarios';
    public static $primaryKey = 'idfunc';

    public static $fillables = ['nomeFunc'];
    public function __construct(){
        $this->inicialize(self::$primaryKey,self::$tableNameModel,self::$fillables);
    }
    
}