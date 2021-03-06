<?php
require_once __DIR__.'/vendor/autoload.php';

use App\DB;
use App\Types;
use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use GraphQL\Validator\DocumentValidator;
use GraphQL\Validator\Rules\QueryComplexity;
use GraphQL\Validator\Rules\QueryDepth;

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
    // инициализация с бд
    DB::init($configs['db']);

    // получаем json запрос
    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);
    $query = $input['query'];
    // получение переменных запроса
    $variableValues = isset($input['variables']) ? $input['variables'] : null;

    $schema = new Schema([
        'query' => Types::query(),
        'mutation' => Types::mutation()
    ]);

    // Устанавливаем правила валидации запроса
    DocumentValidator::addRule( new QueryComplexity($configs['query']['complexity']));
    DocumentValidator::addRule( new QueryDepth($configs['query']['depth']));

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