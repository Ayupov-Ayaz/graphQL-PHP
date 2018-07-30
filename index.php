<?php
require_once __DIR__.'/vendor/autoload.php';
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Schema;

try {

    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];
    $variableValues = isset($input['variables']) ? $input['variables'] : null;
    $rootValue = ['prefix' => 'You said: '];

    $queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'echo' => [
            'type' => Type::string(),
            'args' => [
                'message' => Type::nonNull(Type::string()),
            ],
            'resolve' => function ($root, $args) {
                return $root['prefix'] . $args['message'];
            }
        ],
    ],
    ]);

    $schema = new Schema([
        'query' => $queryType
    ]);

    $result = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
    $output = $result->toArray();
    header('Content-Type: application/json');
    echo json_encode($output);
}  catch (\Exception $e) {
    $result = [
        'error' => [
            'message' => $e->getMessage()
        ]
    ];
}