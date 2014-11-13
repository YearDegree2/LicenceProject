<?php

$app = require __DIR__ . '/config/config.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app->get('/', function () use ($app) {
    return new Response(null, 200);
});

$app->get('/login', function (Request $request) use ($app) {
    /* A Enlever mais pour l'instant je laisse pour tester avec localhost, si on veut tester phpunit, l'enlever*/
    $request->headers->add(array(
            'Content-Type' => 'fr',
        )
    );
    /* Fin a enlever */
    if ('fr' === $request->headers->get('Content-Type')) {
        $file = 'login.fr.html.twig';
    }
    if ('en' === $request->headers->get('Content-Type')) {
        $file = 'login.en.html.twig';
    }
    if (isset($file)) {
        return new Response($app['twig']->render($file, array(
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username'),
        )), 200);
    }

    return new Response('Language needed: French or English', 400);
});

$app->get('/admin', function () use ($app) {
    return new Response(null, 200);
});

$app->get("/admin/menus", function () use ($app) {
    $query = $app['db']->executeQuery('SELECT * FROM menu ');
    $results = $query->fetchAll();
    if (null == $results) {
        return new Response(null, 400);
    }
    $menus = array();
    foreach ($results as $unMenu) {
        array_push($menus, $unMenu);
    }
    //var_dump($menus);
    $jsonMenus = json_encode($menus);

    return new Response($jsonMenus, 200);
})->bind('menus');

return $app;
