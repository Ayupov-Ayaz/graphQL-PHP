<?php
namespace App\Models;
use App\DB;
use App\Services\Buffer;
use App\Services\BufferedType;
use GraphQL\Deferred;

class User implements BufferedType
{
    /**
     * Ключи для определения функционала для использования буфера
     * @var array
     */
    private $keys = [
        'getCountFriends' => 'ufc'
    ];
    /**
     * Получить количество друзей пользователя
     * @param array $args
     * @return mixed
     */
    public function getCountFriends($args) {
        Buffer::addId($this->keys['getCountFriends'], $args->id);

        return new Deferred(function() use ($args){
            $this->loadData($this->keys['getCountFriends']);
            $result = Buffer::getResult($this->keys['getCountFriends']);
            return $result[$args->id];
        });
    }

    /**
     *  Получить друзей пользователя
     * @param int $id
     * @return mixed
     */
    public function getfriends($id) {
        return DB::select("SELECT u.* FROM friendships f JOIN users u ON u.id = f.friend_id 
                                 WHERE f.user_id = ".$id);
    }

    /**
     *  Добавление пользователя
     * @param array $user
     * @return mixed
     */
    public function addUser(array $userParams) {
        $userParams = $this->saveUserParams($userParams);
        return DB::insert('users', $userParams);
    }

    /**
     * Обновление  пользователя
     * @param array $user
     * @return mixed
     */
    public function updateUser(array $userParams) {
        $userParams = $this->saveUserParams($userParams);
        DB::update('users', $userParams, "id=" . $userParams['id']);
        return DB::getElementById('users', $userParams['id']);
    }

    /**
     *  Обработка переменных пользователя
     * @param $params
     * @return array
     */
    private function saveUserParams($params) {
        // числовые типы
        $intParams = array('id');
        // строковые типы
        $strParams = array('email', 'name');

        $newParams = array();
        foreach ($params as $key => $val) {
            if(in_array($key, $intParams)) {
                $newParams[$key] = (int) $val;
            } else if(in_array($key, $strParams)) {
                $newParams[$key] = "'".trim(htmlentities($val))."'" ;
            }
        }
        return $newParams;
    }

    /**
     * Загрузка данных в буфер
     * @param $key
     */
    public function loadData($key) {
        $ids = Buffer::getIds($key);

        $countFriends = function  ($key, $ids){
            $rows = DB::select("SELECT u.id, COUNT(f.friend_id) AS count 
                                     FROM users u LEFT JOIN friendships f ON f.user_id = u.id 
                                     WHERE u.id IN (" . implode(',', $ids) . ") GROUP BY u.id");
            foreach($rows as $row) {
                $param = [
                    $row->id => (int)$row->count
                ];
                Buffer::addResult($key, $param);
            }
        };

        switch ($key) {
            case $this->keys['getCountFriends']:
                $countFriends($key, $ids); break;
            // другие функции использующие буфер
        }
    }
}