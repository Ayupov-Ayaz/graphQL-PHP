<?php
/**
 * Created by PhpStorm.
 * User: tommy
 * Date: 31.07.18
 * Time: 23:57
 */

namespace App;
use PDO;
use PDOException;

class DB
{
    private static $pdo;

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
    public static function selectOne($query) {
        $records = self::select($query);
        return array_shift($records);
    }
    public static function select($query) {
        $statement = self::$pdo->query($query);
        return $statement->fetchAll();
    }
    public static function affectingStatement($query) {
        $statement = self::$pdo->query($query);
        return $statement->rowCount();
    }
    public static function insert($table, $params) {
        $statement = self::$pdo->query($query);
        $success = $statement->execute();
        return $success ? self::$pdo->lastInsertId() : null;
    }
    public static function update($table_name, array $params, $where, $limit) {

        $statement = self::$pdo->query("UPDATE {$table_name} SET ".implode($valstr, ' ,')." where {$where}");
        $statement->execute();
    }
    private static function prepareParams(array $params) {
        foreach ($params as $key => $val)
        {
            $val = gettype($val) == 'string' ? "'".$val."'" : $val;
            $valstr[] = $key." = ".$val;
        }
        return $valstr;
    }
}