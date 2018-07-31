<?php

$configs = [
    'db' => [
        'host' => 'mysql_link', //this is a link in app service to mysql
        'database' => getenv('MYSQL_DATABASE'),
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'port' => getenv('MYSQL_PORT')
    ]
];

return $configs;