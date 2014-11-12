<?php

$app = require_once __DIR__ . '/config/config.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get("/", function () use ($app) {
    return new Response(null, 200);
});

$app->get("/login", function (Request $request) use ($app) {
    /* A Enlever mais pour l'instant je laisse pour tester */
    $request->headers->add(array(
            'Content-language' => 'en',
        )
    );
    /* Fin a enlever */
    if ('fr' === $request->headers->get('Content-Language')) {
        $file = 'login.fr.html.twig';
    }
    if ('en' === $request->headers->get('Content-Language')) {
        $file = 'login.en.html.twig';
    }
    if (isset($file)) {
        return $app['twig']->render($file, array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }

    return new Response('Language needed: French or English', 400);
});

$app->get("/admin", function () use ($app) {
    return new Response('Admin connecte', 200);
})->bind('admin');

return $app;
