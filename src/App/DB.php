<?php
namespace App;

use PDO;
use PDOException;

class DB
{
    private static $pdo;

    /**
     * Инициализация класса
     * @param $config
     */
    public static function init($config) {
        try{
            self::$pdo = new PDO("mysql:host={$config['host']};dbname={$config['database']}",
                                      $config['username'], $config['password']);
            self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            self::$pdo->query('SET NAMES utf8');
        }  catch (PDOException $e) {
            json_encode(['error' => $e->getMessage()]);
        }
    }

    /**
     * Получить объект в единственном экземпляре
     * @param $query
     * @return mixed
     */
    public static function selectOne($query) {
        $records = self::select($query);
        $result =  array_shift($records);
        return $result;
    }

    /**
     * Выполнить запрос
     * @param $query
     * @return mixed
     */
    public static function select($query) {
        $statement = self::$pdo->query($query);
        return $statement->fetchAll();
    }

    /**
     *  Получить колличество строк
     * @param $query
     * @return mixed
     */
    public static function affectingStatement($query) {
        $statement = self::$pdo->query($query);
        return $statement->rowCount();
    }

    /**
     * Обновление объекта
     * @param $table_name
     * @param array $params
     * @param $where
     */
    public static function update($table_name, array $params, $where) {
        if(!is_array($params)) return;
        foreach ($params as $key => $val)
        {
            $val = gettype($val) == 'string' ? "'".$val."'" : $val;
            $valstr[] = $key." = ".$val;
        }
        self::$pdo->query("UPDATE {$table_name} SET ".implode($valstr, ' ,')." where {$where}");
    }

    /**
     * Добавление объекта
     * @param string $table_name
     * @param array $params
     * @return null|void
     */
    public static function insert($table_name, array $params) {
        if(!is_array($params)) return;
        $query = "INSERT INTO {$table_name} (". implode(', ', array_keys($params)) .") 
                  VALUES (". implode(', ',array_values($params)) .")";
        self::$pdo->query($query);
        $id = (int)self::$pdo->lastInsertId();
        return $id ? self::getElementById($table_name, $id) : null;
    }

    /**
     * Получение объекта по его id
     * @param string $table_name
     * @param int $id
     * @return mixed
     */
    public static function getElementById($table_name,  $id) {
        return DB::selectOne("SELECT * FROM $table_name WHERE id = $id");
    }
}