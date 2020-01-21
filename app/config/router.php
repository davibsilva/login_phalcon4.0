<?php

$router = $di->getRouter();

// Define your routes here

$router->add(
    '/login',
    [
        'controller' => 'login',
        'action'     => 'autentication',
    ]
);

$router->add(
    '/register',
    [
        'controller' => 'register',
        'action'     => 'index',
    ]
);


$router->handle($_SERVER['REQUEST_URI']);
