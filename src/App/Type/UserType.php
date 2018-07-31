<?php
namespace App\Type;
use App\DB;
use App\Types;
use GraphQL\Type\Definition\ObjectType;
class UserType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'description' => 'Пользователь',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::string(),
                        'description' => 'Идентификатор пользователя'
                    ],
                    'name' => [
                        'type' => Types::string(),
                        'description' => 'Имя пользователя'
                    ],
                    'email' => [
                        'type' => Types::string(),
                        'description' => 'E-mail пользователя'
                    ],
                    'friends' => [
                        'type' => Types::listOf(Types::user()),
                        'description' => 'Друзья пользователя',
                        'resolve' => function ($args) {
                            $id = (int)$args->id;
                            return DB::select("SELECT u.* 
                                                     FROM friendships f 
                                                     JOIN users u ON u.id = f.friend_id 
                                                     WHERE f.user_id = ".$id);
                        }
                    ],
                    'countFriends' => [
                        'type' => Types::int(),
                        'description' => 'Количество друзей пользователя',
                        'resolve' => function ($args) {
                            $id = (int)$args->id;
                            return DB::affectingStatement("SELECT u.* 
                                                                 FROM friendships f 
                                                                 JOIN users u ON u.id = f.friend_id 
                                                                 WHERE f.user_id = ".$id);
                        }
                    ]
                ];
            }
        ];
        parent::__construct($config);
    }
}
