<?php
namespace App\Type\Scalar;
use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;

class EmailType extends ScalarType
{
    /**
     * Serializes an internal value to include in a response.
     *
     * @param mixed $value
     * @return mixed
     * @throws Error
     */
    public function serialize($value)
    {
        // TODO: Implement serialize() method.
        return $value;
    }
    
    /**
     * Parses an externally provided value (query variable) to use as an input
     *
     * In the case of an invalid value this method must throw an Exception
     *
     * @param mixed $value
     * @return mixed
     * @throws Error
     */
    public function parseValue($value)
    {
        if(!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new \Exception('Не корректный E-mail');
        }
        return $value;
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input
     *
     * In the case of an invalid node or value this method must throw an Exception
     *
     * @param Node $valueNode
     * @param array|null $variables
     * @return mixed
     * @throws \Exception
     */
    public function parseLiteral($valueNode, array $variables = null)
    {
        if(!filter_var($valueNode->value, FILTER_VALIDATE_EMAIL )) {
            throw new \Exception('Не корректный E-mail');
        }
        return $valueNode->value;
    }
}