<?php
namespace App;

use App\Type\QueryType;
use GraphQL\Type\Definition\Type;

class Types
{
    // составной тип данных
    private static $query;

    public static function query() {
        return self::$query ?: (self::$query = new QueryType());
    }

    public static function string() {
        return Type::string();
    }
}