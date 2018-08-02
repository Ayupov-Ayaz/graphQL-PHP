<?php

$configs = [
    'db' => [
        'host' => 'mysql_link', //this is a link in app service to mysql
        'database' => getenv('MYSQL_DATABASE'),
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'port' => getenv('MYSQL_PORT')
    ],
    /**
     * Валидаторы запроса
     */
    'query' => [
        // сложность запроса, соответствует количеству полей в запросе
        'complexity' => 12,
        // глубина запроса, соответствует вложенности запроса
        'depth' => 6
    ],
];

return $configs;