<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

return $app;
