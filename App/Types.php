<?php
namespace App;

use App\Type\QueryType;
use GraphQL\Type\Definition\Type;

/**
 * Реестр типов
 * Class Types
 * @package App
 */
class Types
{
    // составной тип данных
    private static $query;

    public static function query(array $config) {
        return self::$query ?: (self::$query = new QueryType($config));
    }

    public static function string() {
        return Type::string();
    }
}