<?php
require_once __DIR__.'/vendor/autoload.php';

use App\DB;
use App\Types;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;

try {
    $config = require (__DIR__.'/App/configs/web.php');
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
    DB::init($config['db']);
    DB::select('select * from users where id = 1');

    // получаем json запрос
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];

    $variableValues = isset($input['variables']) ? $input['variables'] : null;


    $queryType = Types::query(['name' => 'Query']);

    $schema = new Schema([
        'query' => $queryType
    ]);

    $result = GraphQL::executeQuery($schema, $query);
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