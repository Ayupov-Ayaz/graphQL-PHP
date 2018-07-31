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
}