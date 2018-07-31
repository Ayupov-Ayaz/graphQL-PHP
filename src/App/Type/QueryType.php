<?php
namespace App\Type;
use App\DB;
use GraphQL\Type\Definition\ObjectType;
use App\Types;
class QueryType extends ObjectType
{
    public function __construct(array $config)
    {
        $config = [
            'fields' => function() {
                // все возможные комбинации запросов:
                return [
                    'echo' => [
                        'type' => Types::string(),
                        'description' => 'Возвращает message',
                        'args' => [
                            'message' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            return $root['prefix'] . $args['message'];
                        }
                    ],
                    'user' => [
                        'type' => Types::user(),
                        'description' => 'Возвращает пользователя по id',
                        'args' => [
                            'id' => Types::int()
                        ],
                        'resolve' => function($root, $args) {
                            $id = (int)$args['id'];
                            return DB::selecOne("SELECT * FROM users WHERE id = ".$id);
                        }
                    ],
                    'allUser' => [
                        'type' => Types::listOf(Types::user()),
                        'description' => 'Список пользователей',
                        'resolve' => function() {
                            return DB::select('SELECT * FROM users');
                        }
                    ]
                ];
            }
        ];
        parent::__construct($config);
    }
}
