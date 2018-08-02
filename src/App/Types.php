<?php
namespace App;

use App\Type\Input\InputUserType;
use App\Type\MutationType;
use App\Type\QueryType;
use App\Type\Scalar\EmailType;
use App\Type\UserType;
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

    private static $user;

    private static $mutation;

    private static $emailType;

    private static $inputUser;
    public static function query() {
        return self::$query ?: (self::$query = new QueryType());
    }

    public static function string() {
        return Type::string();
    }

    public static function int() {
        return Type::int();
    }
    // массив типа
    public static function listOf($type) {
        return Type::listOf($type);
    }
    public static function user() {
        return self::$user ?: (self::$user = new UserType());
    }
    public static function mutation() {
        return self::$mutation ?: (self::$mutation = new MutationType());
    }
    public static function inputUser() {
        return self::$inputUser ?: (self::$inputUser = new InputUserType());
    }
    public static function nonNull($type) {
        return Type::nonNull($type);
    }
    public static function email() {
        return self::$emailType ?: (self::$emailType = new EmailType());
    }
}