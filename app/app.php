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

$app->post('/admin/rubrique', function (Request $request) use ($app) {
    if (null == $request->getContent()) {
        return new Response(null, 404);
    }
    $rubriqueArray = json_decode($request->getContent());
    if (!isset($rubriqueArray->{'titre_fr'}) || !isset($rubriqueArray->{'titre_en'}) || !isset($rubriqueArray->{'actif'})) {
        return new Response(null, 404);
    }
    if (!isset($rubriqueArray->{'ID'})) {
        $sqlRequest = 'INSERT INTO menu VALUES (null, ?, ?, ?, ?)';
        $app['db']->executeUpdate($sqlRequest, array(
            $rubriqueArray->{'titre_fr'},
            $rubriqueArray->{'titre_en'},
            $rubriqueArray->{'actif'},
            isset($rubriqueArray->{'position'}) ? $rubriqueArray->{'position'} : null,
            ));

        $sqlRequest = "INSERT INTO rubrique VALUES (null, NOW(), NOW(), 'Bienvenue', 'Welcome', (SELECT max(ID) FROM menu))";
        $app['db']->executeUpdate($sqlRequest);

        return new Response(null, 200);
    }
    $sqlRequest = 'INSERT INTO menu VALUES (?, ?, ?, ?, ?)';
    $app['db']->executeUpdate($sqlRequest, array(
        $rubriqueArray->{'ID'},
        $rubriqueArray->{'titre_fr'},
        $rubriqueArray->{'titre_en'},
        isset($rubriqueArray->{'actif'}) ? $rubriqueArray->{'actif'} : 0,
        isset($rubriqueArray->{'position'}) ? $rubriqueArray->{'position'} : null,
        ));

    $sqlRequest = "INSERT INTO rubrique VALUES (null, NOW(), NOW(), 'Bienvenue', 'Welcome', ?)";
    $app['db']->executeUpdate($sqlRequest, array($rubriqueArray->{'ID'}));

    return new Response(null, 200);
});

$app->get('/admin/rubriques', function () use ($app) {
    $query = $app['db']->executeQuery('SELECT * FROM menu, rubrique WHERE rubrique.menu_id = menu.ID');
    $results = $query->fetchAll();
    if (null == $results) {
        return new Response(null, 400);
    }
    $menus = array();
    foreach ($results as $menu) {
        array_push($menus, $menu);
    }
    $jsonMenus = json_encode($menus);

    return new Response($jsonMenus, 200);
});

$app->get('/admin/rubriques/{id}', function ($id) use ($app) {
    $req = 'SELECT * FROM menu, rubrique WHERE rubrique.menu_id = menu.ID AND menu.ID = ?';
    $result = $app['db']->fetchAssoc($req, array((int) $id));
    if (null == $result) {
        return new Response(null, 400);
    }
    $jsonMenu = json_encode($result);

    return new Response($jsonMenu, 200);
});

$app->put('/admin/rubriques/{id}', function (Request $request, $id) use ($app) {
    if (null == $request->getContent()) {
        return new Response(null, 404);
    }
    $sqlRequest = 'SELECT * FROM menu WHERE ID = ?';
    $result = $app['db']->fetchAssoc($sqlRequest, array((int) $id));
    if (null == $result) {
        return new Response(null, 400);
    }
    $rubriqueArray = json_decode($request->getContent());
    if (!isset($rubriqueArray->{'titre_fr'}) || !isset($rubriqueArray->{'titre_en'})) {
        return new Response(null, 404);
    }
    $sqlRequest = 'UPDATE menu SET titre_fr = ?, titre_en = ?';
    $values = array($rubriqueArray->{'titre_fr'}, $rubriqueArray->{'titre_en'});
    if (isset($rubriqueArray->{'actif'})) {
        $sqlRequest .= ', actif = ?';
        array_push($values, $rubriqueArray->{'actif'});
    }
    if (isset($rubriqueArray->{'position'})) {
        $sqlRequest .= ', position = ?';
        array_push($values, $rubriqueArray->{'position'});
    }
    $sqlRequest .= ' WHERE ID = ?';
    array_push($values, $id);
    $app['db']->executeUpdate($sqlRequest, $values);
    $sqlRequest = 'UPDATE rubrique SET date_modification = NOW() WHERE menu_id = ?';
    $app['db']->executeUpdate($sqlRequest, array((int) $id));

    return new Response(null, 200);
});

$app->delete('/admin/rubriques/{id}', function ($id) use ($app) {
    $sqlRequest = 'SELECT * FROM menu WHERE ID = ?';
    $result = $app['db']->fetchAssoc($sqlRequest, array((int) $id));
    if (null == $result) {
        return new Response(null, 400);
    }
    $sqlRequest = 'DELETE FROM rubrique WHERE menu_id = ?';
    $app['db']->executeUpdate($sqlRequest, array((int) $id));
    $sqlRequest = 'DELETE FROM menu WHERE ID = ?';
    $app['db']->executeUpdate($sqlRequest, array((int) $id));

    return new Response(null, 200);
});

$app->delete('/admin/rubriques', function () use ($app) {
    $sqlRequest = 'DELETE FROM rubrique WHERE 1';
    $app['db']->executeUpdate($sqlRequest);
    $sqlRequest = 'DELETE FROM menu WHERE 1';
    $app['db']->executeUpdate($sqlRequest);

    return new Response(null, 200);
});

$app->get('/admin/menus', function () use ($app) {
    $query = $app['db']->executeQuery('SELECT * FROM menu ');
    $results = $query->fetchAll();
    if (null == $results) {
        return new Response(null, 400);
    }
    $menus = array();
    foreach ($results as $menu) {
        array_push($menus, $menu);
    }
    $jsonMenus = json_encode($menus);

    return new Response($jsonMenus, 200);
});

$app->get('/admin/menus/{id}', function ($id) use ($app) {
    $req = 'SELECT * FROM menu WHERE ID = ?';
    $result = $app['db']->fetchAssoc($req, array((int) $id));
    if (null == $result) {
        return new Response(null, 400);
    }
    $jsonMenu = json_encode($result);

    return new Response($jsonMenu, 200);
});

return $app;
