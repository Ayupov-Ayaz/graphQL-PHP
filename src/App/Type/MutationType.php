<?php
namespace App\Type;


use App\DB;
use App\Types;
use Exception;
use GraphQL\Type\Definition\ObjectType;

class MutationType extends ObjectType
{
    public function __construct() {
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
                            $id = (int)$args['id'];
                            $name = htmlspecialchars(trim($args['name']));
                            DB::update('users', ['name' => $name] , "id = {$id}");
                            return DB::selectOne("SELECT * FROM users WHERE id = {$id}");
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
                            $id = (int)$args['id'];
                            $email = trim(htmlentities($args['email']));
                            DB::update('users', ['email' => $email], "id={$id}");
                            return DB::selectOne("SELECT * FROM users WHERE id = {$id}");
                        }
                    ],
                    'addUser' => [
                        'type' => Types::user(),
                        'description' => 'Добавление пользователя',
                        'args' => [
                            'user' => Types::inputUser()
                        ],
                        'resolve' => function($root, $args) {
                            $userName = trim(htmlspecialchars($args['user']['name']));
                            $userEmail = trim(htmlspecialchars($args['user']['email']));
                            $userId = DB::insert("INSERT INTO users (name, email) VALUES ('$userName', '$userEmail')");
                            return DB::selectOne("SELECT * FROM users WHERE id = $userId");
                        }
                    ]
                ];
            }
        ];
        parent::__construct($config);
    }
}