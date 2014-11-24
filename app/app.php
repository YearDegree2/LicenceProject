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

// Categorie

$app->post('/admin/categorie', function (Request $request) use ($app) {
    if (null == $request->getContent()) {
        return new Response(null, 404);
    }
    $categorieArray = json_decode($request->getContent());
    if (!isset($categorieArray->{'name_fr'}) || !isset($categorieArray->{'name_en'})) {
        return new Response(null, 404);
    }
    if (!isset($categorieArray->{'ID'})) {
        $sqlRequest = 'INSERT INTO categorie VALUES (null, ?, ?)';
        $app['db']->executeUpdate($sqlRequest, array($categorieArray->{'name_fr'}, $categorieArray->{'name_en'}));

        return new Response(null, 200);
    }
    $sqlRequest = 'INSERT INTO categorie VALUES (?, ?, ?)';
    $app['db']->executeUpdate($sqlRequest, array($categorieArray->{'ID'}, $categorieArray->{'name_fr'}, $categorieArray->{'name_en'}));

    return new Response(null, 200);
});

$app->get('/admin/categories', function () use ($app) {
    $sqlRequest = 'SELECT * FROM categorie';
    $query = $app['db']->executeQuery($sqlRequest);
    $results = $query->fetchAll();
    if (null == $results) {
        return new Response(null, 400);
    }
    $categories = array();
    foreach ($results as $row) {
        array_push($categories, $row);
    }
    $jsonCategories = json_encode($categories);

    return new Response($jsonCategories, 200);
});

$app->get('/admin/categories/{id}', function ($id) use ($app) {
    $sqlRequest = 'SELECT * FROM categorie WHERE ID = ?';
    $result = $app['db']->fetchAssoc($sqlRequest, array((int) $id));
    if (null == $result) {
        return new Response(null, 400);
    }
    $jsonCategorie = json_encode($result);

    return new Response($jsonCategorie, 200);
});

$app->put('/admin/categories/{id}', function (Request $request, $id) use ($app) {
    if (null == $request->getContent()) {
        return new Response(null, 404);
    }
    $sqlRequest = 'SELECT * FROM categorie WHERE ID = ?';
    $result = $app['db']->fetchAssoc($sqlRequest, array((int) $id));
    if (null == $result) {
        return new Response(null, 400);
    }
    $categorieArray = json_decode($request->getContent());
    if (!isset($categorieArray->{'name_fr'}) || !isset($categorieArray->{'name_en'})) {
        return new Response(null, 404);
    }
    $sqlRequest = 'UPDATE categorie SET name_fr = ?, name_en = ? WHERE ID = ?';
    $app['db']->executeUpdate($sqlRequest, array($categorieArray->{'name_fr'}, $categorieArray->{'name_en'},  $id));

    return new Response(null, 200);
});

$app->delete('/admin/categories/{id}', function ($id) use ($app) {
    $sqlRequest = 'SELECT * FROM categorie WHERE ID = ?';
    $result = $app['db']->fetchAssoc($sqlRequest, array((int) $id));
    if (null == $result) {
        return new Response(null, 400);
    }
    $sqlRequest = 'DELETE FROM categorie WHERE ID = ?';
    $app['db']->executeUpdate($sqlRequest, array((int) $id));

    return new Response(null, 200);
});

$app->delete('/admin/categories', function () use ($app) {
    $sqlRequest = 'DELETE FROM categorie WHERE 1';
    $app['db']->executeUpdate($sqlRequest);

    return new Response(null, 200);
});

// Menu

$app->get("/admin/menus", function () use ($app) {
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

// Publication

$app->post('/admin/publication', function (Request $request) use ($app) {
    if (null == $request->getContent()) {
        return new Response(null, 404);
    }
    $categorieArray = json_decode($request->getContent());
    if (!isset($categorieArray->{'reference'}) || !isset($categorieArray->{'auteurs'})
        || !isset($categorieArray->{'titre'}) || !isset($categorieArray->{'date'})) {
        return new Response(null, 404);
    }
    if (!isset($categorieArray->{'ID'})) {
        $sqlRequest = 'INSERT INTO publication VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
        $app['db']->executeUpdate($sqlRequest, array(
            $categorieArray->{'reference'},
            $categorieArray->{'auteurs'},
            $categorieArray->{'titre'},
            $categorieArray->{'date'},
            isset($categorieArray->{'journal'}) ? $categorieArray->{'journal'} : null,
            isset($categorieArray->{'volume'}) ? $categorieArray->{'volume'} : null,
            isset($categorieArray->{'number'}) ? $categorieArray->{'number'} : null,
            isset($categorieArray->{'pages'}) ? $categorieArray->{'pages'} : null,
            isset($categorieArray->{'note'}) ? $categorieArray->{'note'} : null,
            isset($categorieArray->{'abstract'}) ? $categorieArray->{'abstract'} : null,
            isset($categorieArray->{'keywords'}) ? $categorieArray->{'keywords'} : null,
            isset($categorieArray->{'series'}) ? $categorieArray->{'series'} : null,
            isset($categorieArray->{'localite'}) ? $categorieArray->{'localite'} : null,
            isset($categorieArray->{'publisher'}) ? $categorieArray->{'publisher'} : null,
            isset($categorieArray->{'editor'}) ? $categorieArray->{'editor'} : null,
            isset($categorieArray->{'pdf'}) ? $categorieArray->{'pdf'} : null,
            isset($categorieArray->{'date_display'}) ? $categorieArray->{'date_display'} : null,
            isset($categorieArray->{'categorie_id'}) ? $categorieArray->{'categorie_id'} : null,
        ));

        return new Response(null, 200);
    }
    $sqlRequest = 'INSERT INTO publication VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
    $app['db']->executeUpdate($sqlRequest, array(
        $categorieArray->{'ID'},
        $categorieArray->{'reference'},
        $categorieArray->{'auteurs'},
        $categorieArray->{'titre'},
        $categorieArray->{'date'},
        isset($categorieArray->{'journal'}) ? $categorieArray->{'journal'} : null,
        isset($categorieArray->{'volume'}) ? $categorieArray->{'volume'} : null,
        isset($categorieArray->{'number'}) ? $categorieArray->{'number'} : null,
        isset($categorieArray->{'pages'}) ? $categorieArray->{'pages'} : null,
        isset($categorieArray->{'note'}) ? $categorieArray->{'note'} : null,
        isset($categorieArray->{'abstract'}) ? $categorieArray->{'abstract'} : null,
        isset($categorieArray->{'keywords'}) ? $categorieArray->{'keywords'} : null,
        isset($categorieArray->{'series'}) ? $categorieArray->{'series'} : null,
        isset($categorieArray->{'localite'}) ? $categorieArray->{'localite'} : null,
        isset($categorieArray->{'publisher'}) ? $categorieArray->{'publisher'} : null,
        isset($categorieArray->{'editor'}) ? $categorieArray->{'editor'} : null,
        isset($categorieArray->{'pdf'}) ? $categorieArray->{'pdf'} : null,
        isset($categorieArray->{'date_display'}) ? $categorieArray->{'date_display'} : null,
        isset($categorieArray->{'categorie_id'}) ? $categorieArray->{'categorie_id'} : null,
    ));

    return new Response(null, 200);
});

$app->get('/admin/publications', function () use ($app) {
    $sqlRequest = 'SELECT * FROM publication';
    $query = $app['db']->executeQuery($sqlRequest);
    $results = $query->fetchAll();
    if (null == $results) {
        return new Response(null, 400);
    }
    $categories = array();
    foreach ($results as $row) {
        array_push($categories, $row);
    }
    $jsonCategories = json_encode($categories);

    return new Response($jsonCategories, 200);
});

// Tri de la plus recente a la plus ancienne
$app->get('/admin/publications/date', function () use ($app) {
    $sqlRequest = 'SELECT * FROM publication ORDER BY date DESC';
    $query = $app['db']->executeQuery($sqlRequest);
    $results = $query->fetchAll();
    if (null == $results) {
        return new Response(null, 400);
    }
    $categories = array();
    foreach ($results as $row) {
        array_push($categories, $row);
    }
    $jsonCategories = json_encode($categories);

    return new Response($jsonCategories, 200);
});

$app->get('/admin/publications/categorie', function () use ($app) {
    $sqlRequest = 'SELECT * FROM publication ORDER BY categorie_id';
    $query = $app['db']->executeQuery($sqlRequest);
    $results = $query->fetchAll();
    if (null == $results) {
        return new Response(null, 400);
    }
    $categories = array();
    foreach ($results as $row) {
        array_push($categories, $row);
    }
    $jsonCategories = json_encode($categories);

    return new Response($jsonCategories, 200);
});

$app->get('/admin/publications/count', function () use ($app) {
    $req = 'SELECT COUNT(*) FROM publication';
    $result = $app['db']->fetchAssoc($req);
    $countValue = json_encode($result);

    return new Response($countValue, 200);
});

$app->get('/admin/publications/{id}', function ($id) use ($app) {
    $sqlRequest = 'SELECT * FROM publication WHERE ID = ?';
    $result = $app['db']->fetchAssoc($sqlRequest, array((int) $id));
    if (null == $result) {
        return new Response(null, 400);
    }
    $jsonCategorie = json_encode($result);

    return new Response($jsonCategorie, 200);
});

$app->put('/admin/publications/{id}', function (Request $request, $id) use ($app) {
    if (null == $request->getContent()) {
        return new Response(null, 404);
    }
    $sqlRequest = 'SELECT * FROM publication WHERE ID = ?';
    $result = $app['db']->fetchAssoc($sqlRequest, array((int) $id));
    if (null == $result) {
        return new Response(null, 400);
    }
    $categorieArray = json_decode($request->getContent());
    if (!isset($categorieArray->{'reference'})) {
        return new Response(null, 400);
    }

    $sqlRequest = 'UPDATE publication SET reference = ?';
    $values = array($categorieArray->{'reference'});

    if (isset($categorieArray->{'auteurs'})) {
        $sqlRequest .= ', auteurs = ?';
        array_push($values, $categorieArray->{'auteurs'});
    }
    if (isset($categorieArray->{'titre'})) {
        $sqlRequest .= ', titre = ?';
        array_push($values, $categorieArray->{'titre'});
    }
    if (isset($categorieArray->{'date'})) {
        $sqlRequest .= ', date = ?';
        array_push($values, $categorieArray->{'date'});
    }
    if (isset($categorieArray->{'journal'})) {
        $sqlRequest .= ', journal = ?';
        array_push($values, $categorieArray->{'journal'});
    }
    if (isset($categorieArray->{'volume'})) {
        $sqlRequest .= ', volume = ?';
        array_push($values, $categorieArray->{'volume'});
    }
    if (isset($categorieArray->{'number'})) {
        $sqlRequest .= ', number = ?';
        array_push($values, $categorieArray->{'number'});
    }
    if (isset($categorieArray->{'pages'})) {
        $sqlRequest .= ', pages = ?';
        array_push($values, $categorieArray->{'pages'});
    }
    if (isset($categorieArray->{'note'})) {
        $sqlRequest .= ', note = ?';
        array_push($values, $categorieArray->{'note'});
    }
    if (isset($categorieArray->{'abstract'})) {
        $sqlRequest .= ', abstract = ?';
        array_push($values, $categorieArray->{'abstract'});
    }
    if (isset($categorieArray->{'keywords'})) {
        $sqlRequest .= ', keywords = ?';
        array_push($values, $categorieArray->{'keywords'});
    }
    if (isset($categorieArray->{'series'})) {
        $sqlRequest .= ', series = ?';
        array_push($values, $categorieArray->{'series'});
    }
    if (isset($categorieArray->{'localite'})) {
        $sqlRequest .= ', localite = ?';
        array_push($values, $categorieArray->{'localite'});
    }
    if (isset($categorieArray->{'publisher'})) {
        $sqlRequest .= ', publisher = ?';
        array_push($values, $categorieArray->{'publisher'});
    }

    if (isset($categorieArray->{'editor'})) {
        $sqlRequest .= ', editor = ?';
        array_push($values, $categorieArray->{'editor'});
    }
    if (isset($categorieArray->{'pdf'})) {
        $sqlRequest .= ', pdf = ?';
        array_push($values, $categorieArray->{'pdf'});
    }
    if (isset($categorieArray->{'date_display'})) {
        $sqlRequest .= ', date_display = ?';
        array_push($values, $categorieArray->{'date_display'});
    }
    if (isset($categorieArray->{'categorie_id'})) {
        $sqlRequest .= ', categorie_id = ?';
        array_push($values, $categorieArray->{'categorie_id'});
    }
    $sqlRequest .= ' WHERE ID = ?';
    array_push($values, $id);

    $app['db']->executeUpdate($sqlRequest, $values);

    return new Response(null, 200);
});

$app->delete('/admin/publications/{id}', function ($id) use ($app) {
    $sqlRequest = 'SELECT * FROM publication WHERE ID = ?';
    $result = $app['db']->fetchAssoc($sqlRequest, array((int) $id));
    if (null == $result) {
        return new Response(null, 400);
    }
    $sqlRequest = 'DELETE FROM publication WHERE ID = ?';
    $app['db']->executeUpdate($sqlRequest, array((int) $id));

    return new Response(null, 200);
});

$app->delete('/admin/publications', function () use ($app) {
    $sqlRequest = 'DELETE FROM publication WHERE 1';
    $app['db']->executeUpdate($sqlRequest);

    return new Response(null, 200);
});

// Rubrique

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
        $sqlRequest = "INSERT INTO rubrique VALUES (null, NOW(), NOW(), ?, ?, (SELECT max(ID) FROM menu))";
        $app['db']->executeUpdate($sqlRequest, array(
            isset($rubriqueArray->{'content_fr'}) ? $rubriqueArray->{'content_fr'} : null,
            isset($rubriqueArray->{'content_en'}) ? $rubriqueArray->{'content_en'} : null,
        ));

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
    $sqlRequest = "INSERT INTO rubrique VALUES (null, NOW(), NOW(), ?, ?, ?)";
    $app['db']->executeUpdate($sqlRequest, array(
        isset($rubriqueArray->{'content_fr'}) ? $rubriqueArray->{'content_fr'} : null,
        isset($rubriqueArray->{'content_en'}) ? $rubriqueArray->{'content_en'} : null,
        $rubriqueArray->{'ID'},
    ));

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

$app->get('/admin/rubriques/count', function () use ($app) {
    $req = 'SELECT COUNT(*) FROM rubrique';
    $result = $app['db']->fetchAssoc($req);
    $countValue = json_encode($result);

    return new Response($countValue, 200);
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
    $sqlRequest = 'UPDATE rubrique SET date_modification = NOW()';
    $values2 = array();
    if (isset($rubriqueArray->{'content_fr'})) {
        $sqlRequest .= ', content_fr = ?';
        array_push($values2, $rubriqueArray->{'content_fr'});
    }
    if (isset($rubriqueArray->{'content_en'})) {
        $sqlRequest .= ', content_en = ?';
        array_push($values2, $rubriqueArray->{'content_en'});
    }
    $sqlRequest .= ' WHERE menu_id = ?';
    array_push($values2, $id);
    $app['db']->executeUpdate($sqlRequest, $values2);

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

return $app;
