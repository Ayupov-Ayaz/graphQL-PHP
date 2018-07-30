<?php
require_once __DIR__.'/vendor/autoload.php';
use App\Types;
use GraphQL\GraphQL;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;

try {

    spl_autoload_register(
        function($className)
        {
            $className = str_replace("_", "\\", $className);
            $className = ltrim($className, '\\');
            $fileName = '';
            $namespace = '';
            if ($lastNsPos = strripos($className, '\\'))
            {
                $namespace = substr($className, 0, $lastNsPos);
                $className = substr($className, $lastNsPos + 1);
                $fileName = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

            require $fileName;
        }
    );


    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];
    $variableValues = isset($input['variables']) ? $input['variables'] : null;
    $rootValue = ['prefix' => 'You said: '];

    $queryType = new ObjectType([
    'name' => 'Query',
    'fields' => [
        'echo' => [
            'type' => Types::string(),
            'args' => [
                'message' => Types::string(),
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