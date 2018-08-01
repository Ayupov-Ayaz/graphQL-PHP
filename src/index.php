<?php
require_once __DIR__.'/vendor/autoload.php';

use App\DB;
use App\Types;
use GraphQL\GraphQL;
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
    $configs = require_once(__DIR__.'/App/configs/web.php');
    $db = new DB();
    $db->init($configs['db']);

    // получаем json запрос
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];

    $variableValues = isset($input['variables']) ? $input['variables'] : null;
    $rootValue = ['prefix' => 'You said: '];

    $queryType = Types::query(['name' => 'Query']);
    $mutationType = Types::mutation();
    $schema = new Schema([
        'query' => $queryType,
        'mutation' => $mutationType
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