<?php
namespace App\Services;

class Buffer
{
    /**
     *  Массив с идентификаторами
     * @var array
     */
    private static $params = array();

    /**
     *  Массив результатов запроса
     * @var array
     */
    private static $results = array();

    /**
     * Функция для добавления в массив идентификаторы по которым будут строиться запрос
     * @param $key - уникальный ключ
     * @param $id - идентификаторы
     */
    public static function addId($key, $id) {
        // если id есть то ничего не делаем
        if(isset(self::$params[$key]) && in_array($key, self::$params[$key])) return;
        self::$params[$key][] = $id;
    }

    /**
     * Получение всех идентификаторов для построения запроса
     * @param $key - уникальный ключ
     * @return mixed|null
     */
    public static function getIds($key){
        if(!isset(self::$params[$key])) return null;
        return self::$params[$key];
    }

    /**
     * Добавление результатов в массив
     * @param $key - уникальный ключ
     * @param $result
     */
    public static function addResult($key, $result) {
        foreach ($result as $k => $v) {
            self::$results[$key][$k] = $v;
        }
    }

    /**
     * Получение результатов из массива по уникальному ключу
     * @param $key - уникальный ключ
     * @return mixed
     */
    public static function getResult($key) {
        return self::$results[$key];
    }

}