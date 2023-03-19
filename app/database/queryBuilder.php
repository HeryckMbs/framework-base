<?php 

namespace app\database;

use PDOException;

class QueryBuilder{
    private static $query = null;
    private static $where = " WHERE ";
    private static $select = "";
    private static $orderBy = " ORDER BY  ";
    private static $tableName = "";
    private static $join = "";

    public function get(){
        $issetWhere = self::$where != " WHERE ";
        if($issetWhere){
            self::$query .= self::$where ;
        }
        $issetOrderBy = self::$orderBy != " ORDER BY  ";
        if($issetOrderBy){
            self::$query .= self::$orderBy ;
        }
        $issetJoin = self::$join != "";
        if($issetJoin){
            self::$query .= self::$join ;
        }
        $issetSelect = self::$select != "";
        if($issetSelect){
           self::$query = str_replace('*', self::$select, self::$query);
        }
       return self::execute(self::$query);
    }
    
    public function select($fields = []){
            self::$select = implode(',',$fields);
        return $this;
    }

    public function where($column, $operator, $value){
        self::$where .= "$column $operator $value";
        return $this;
    }

    public function whereAnd($column, $operator, $value){
        self::$where .= "and $column $operator $value";
        return $this;
    }
    public function whereOr($column, $operator, $value){
        self::$where .= "or $column $operator $value";
        return $this;
    }
    public function whereRaw($where){
        if(self::$where != ''){
            self::$where .= $where;
        }else{
            self::$where .= $where;
        }
        return $this;
    }
    public function orderBy($column, $value){
        self::$orderBy .= " $column $value";
        return $this;
    }


    public function innerJoin($foreginTable, $foreignKey, $nativeKey){
        $join = " inner join $foreginTable on $foreginTable.$foreignKey  = ". self::$tableName . ".$nativeKey";
        if(self::$join){
            self::$join .= $join;
        }else{
            self::$join = $join;
        }
        return $this;
    }
    public static function setQuery($tableName){
        self::$tableName = $tableName;
        $sql = "SELECT * FROM ". $tableName . " ";
        self::$query = $sql;
    }

    private static function execute($query){
        try{
            $result = Db::queryRaw($query);
            return $result;
        }catch(PDOException $e){
            throw new PDOException($e->getMessage());
        }
    }
}