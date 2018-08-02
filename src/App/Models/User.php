<?php
namespace App\Models;
use App\Types;
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
}