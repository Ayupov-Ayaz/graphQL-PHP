<?php
namespace App\Models;
use App\DB;
class User
{
    /**
     * Получить количество друзей пользователя
     * @param int $id
     * @return mixed
     */
    public function getCountFriends(int $id) {
        return DB::affectingStatement("SELECT u.* FROM friendships f  JOIN users u ON u.id = f.friend_id 
                                             WHERE f.user_id = ".$id);
    }

    /**
     *  Получить друзей пользователя
     * @param int $id
     * @return mixed
     */
    public function getfriends(int $id) {
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
}