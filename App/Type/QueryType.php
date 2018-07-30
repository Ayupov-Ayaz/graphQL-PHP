<?php
namespace App\Type;
use GraphQL\Type\Definition\ObjectType;
use App\Types;


class QueryType extends ObjectType
{
    public function __construct(array $config)
    {
        $config = [
            'fields' => function() {
                    return [
                        'hello' => Types::string(),
                        'description' => 'Возвращает приветствие',
                        'resolve' => function() {
                            return 'Hello all!';
                        }
                    ];
            }
        ];
        parent::__construct($config);
    }
}