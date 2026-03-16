<?php
// config/config.php

return [
    'database' => [
        'host' => 'localhost',
        'dbname' => 'smart_revision_planner',
        'username' => 'postgres',
        'password' => 'faith', // Change this in production!
    ],
    'security' => [
        'csrf_token_name' => 'csrf_token',
    ]
];
