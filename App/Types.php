<?php
namespace App;

use GraphQL\Type\Definition\Type;
use QueryType;

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