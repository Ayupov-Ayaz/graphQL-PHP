<?php
/**
 * Created by PhpStorm.
 * User: tommy
 * Date: 01.08.18
 * Time: 15:38
 */

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
                            'id' => Types::int(),
                            'name' => Types::string()
                        ],
                        'resolve' => function ($root, $args) {
                            $id = (int)$args['id'];
                            $name = trim(htmlentities($args['name']));
                            DB::update('users', ['name' => $name] , "id = {$id}");
                            $user = DB::selectOne("SELECT * FROM users WHERE id = {$id}");
                            if(is_null($user)) {
                                throw new Exception('Пользователь не найден');
                            }
                            return $user;
                        }
                    ],
                    'changeUserEmail'
                ];
          }
        ];
        parent::__construct($config);
    }
}