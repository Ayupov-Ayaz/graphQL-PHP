<?php
namespace App\Type;
use App\Models\User;
use App\Types;
use GraphQL\Type\Definition\ObjectType;
class UserType extends ObjectType
{
    private static $userModel = null;

    public function __construct() {
        if(is_null(self::$userModel)) {
            self::$userModel = new User();
        }
        $config = [
            'description' => 'Пользователь',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::int(),
                        'description' => 'Идентификатор пользователя'
                    ],
                    'name' => [
                        'type' => Types::string(),
                        'description' => 'Имя пользователя'
                    ],
                    'email' => [
                        'type' => Types::email(),
                        'description' => 'E-mail пользователя'
                    ],
                    'friends' => [
                        'type' => Types::listOf(Types::user()),
                        'description' => 'Друзья пользователя',
                        'resolve' => function ($args) {
                            $id = (int)$args->id;
                            return self::$userModel->getFriends($id);
                        }
                    ],
                    'countFriends' => [
                        'type' => Types::int(),
                        'description' => 'Количество друзей пользователя',
                        'resolve' => function ($args) {
                            return self::$userModel->getCountFriends($args);
                        }
                    ]
                ];
            }
        ];
        parent::__construct($config);
    }
}
