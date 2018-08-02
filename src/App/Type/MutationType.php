<?php
namespace App\Type;


use App\DB;
use App\Models\User;
use App\Types;
use Exception;
use GraphQL\Type\Definition\ObjectType;

class MutationType extends ObjectType
{
    private static $userModel = null;

    public function __construct() {
       if(is_null(self::$userModel)) {
           self::$userModel = new User();
       }
        $config = [
            'fields' => function() {
                return [
                    'changeUserName' => [
                        'type' => Types::user(),
                        'description' => 'Изменение имени пользователя',
                        'args' => [
                            'id' => Types::nonNull(Types::int()),
                            'name' => Types::nonNull(Types::string())
                        ],
                        'resolve' => function ($root, $args) {
                           return self::$userModel->updateUser($args);
                        }
                    ],
                    'changeUserEmail' => [
                        'type' => Types::user(),
                        'description' => 'Изменить e-mail пользователя',
                        'args' => [
                            'id' => Types::nonNull(Types::int()),
                            'email' => Types::nonNull(Types::email())
                        ],
                        'resolve' => function($root, $args) {
                            return self::$userModel->updateUser($args);
                        }
                    ],
                    'addUser' => [
                        'type' => Types::user(),
                        'description' => 'Добавление пользователя',
                        'args' => [
                            'user' => Types::inputUser()
                        ],
                        'resolve' => function($root, $args) {
                            return self::$userModel->addUser($args['user']);
                        }
                    ]
                ];
            }
        ];
        parent::__construct($config);
    }
}