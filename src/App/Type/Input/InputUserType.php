<?php
namespace App\Type\Input;

use App\Types;
use GraphQL\Type\Definition\InputObjectType;

class InputUserType extends InputObjectType
{
    public function __construct() {
        $config = [
            'description' => 'Добавление пользователя',
            'fields' => function() {
                return [
                    'name' => [
                        'type' => Types::string(),
                        'description' => 'Имя пользователя'
                    ],
                    'email' => [
                        'type' => Types::string(),
                        'description' => 'E-mail пользователя'
                    ]
                ];
            }
        ];
        parent::__construct($config);
    }
}