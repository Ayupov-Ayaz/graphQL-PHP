<?php
namespace App\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Types;


class QueryType extends ObjectType
{
    public function __construct(array $config)
    {
        $config = [
            'name' => $config['name'],
            'fields' => function() {
                    return [
                    'echo' => [
                        'type' => Types::string(),
                        'description' => 'Возвращает приветствие',
                        'args' => [
                            'message' => Types::string(),
                        ],
                        'resolve' => function ($root, $args) {
                            return $root['prefix'] . $args['message'];
                        }
                        ]
                    ];
            }
        ];
        parent::__construct($config);
    }
}