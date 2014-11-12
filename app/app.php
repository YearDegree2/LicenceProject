<?php

$app = require_once __DIR__ . '/config/config.php';

use Symfony\Component\HttpFoundation\Request;

$app->get("/", function () use ($app) {
    return $app['twig']->render('home.html.twig', []);
});

$app->get("/login", function (Request $request) use ($app) {
    return $app['twig']->render('login.html.twig', array(
        'error' => $app['security.last_error']($request),
        'last_username' => $app['session']->get('_security.last_username'),
    ));
})->bind('login');

$app->get("/admin", function () use ($app) {
    return $app['twig']->render('secret.html.twig', []);
})->bind('admin');
return $app;
