<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

$app->get('/admin/publications/filter', function (Request $request) use ($app) {
    if (null == $request->getContent()) {
        return new Response(null, 404);
    }
    $publicationArray = json_decode($request->getContent());
    if (!array_key_exists('reference', $publicationArray) || !array_key_exists('auteurs', $publicationArray) || !array_key_exists('titre', $publicationArray) || !array_key_exists('journal', $publicationArray) || !array_key_exists('volume', $publicationArray) || !array_key_exists('number', $publicationArray) || !array_key_exists('pages', $publicationArray) || !array_key_exists('note', $publicationArray) || !array_key_exists('abstract', $publicationArray) || !array_key_exists('keywords', $publicationArray) || !array_key_exists('series', $publicationArray) || !array_key_exists('localite', $publicationArray) || !array_key_exists('publisher', $publicationArray) || !array_key_exists('editor', $publicationArray) || !array_key_exists('pdf', $publicationArray) || !array_key_exists('date_display', $publicationArray) ) {
        return new Response(null, 404);
    }
    $sqlRequest = 'SELECT * FROM publication WHERE 1';
    $values = array();
    if (null != $publicationArray->{'reference'}) {
        $sqlRequest .= " AND (reference LIKE ?)";
        array_push($values, '%'. $publicationArray->{'reference'} .'%');
    }
    if (null != $publicationArray->{'auteurs'}) {
        $sqlRequest .= ' AND (auteurs LIKE ?)';
        array_push($values, '%'. $publicationArray->{'auteurs'} .'%');
    }
    if (null != $publicationArray->{'titre'}) {
        $sqlRequest .= ' AND (titre LIKE ?)';
        array_push($values, '%'. $publicationArray->{'titre'} .'%');
    }
    if (null != $publicationArray->{'journal'}) {
        $sqlRequest .= ' AND (journal LIKE ?)';
        array_push($values, '%'. $publicationArray->{'journal'} .'%');
    }
    if (null != $publicationArray->{'volume'}) {
        $sqlRequest .= ' AND (volume LIKE ?)';
        array_push($values, '%'. $publicationArray->{'volume'} .'%');
    }
    if (null != $publicationArray->{'number'}) {
        $sqlRequest .= ' AND (number LIKE ?)';
        array_push($values, '%'. $publicationArray->{'number'} .'%');
    }
    if (null != $publicationArray->{'pages'}) {
        $sqlRequest .= ' AND (pages LIKE ?)';
        array_push($values, '%'. $publicationArray->{'pages'} .'%');
    }
    if (null != $publicationArray->{'note'}) {
        $sqlRequest .= ' AND (note LIKE ?)';
        array_push($values, '%'. $publicationArray->{'note'} .'%');
    }
    if (null != $publicationArray->{'abstract'}) {
        $sqlRequest .= ' AND (abstract LIKE ?)';
        array_push($values, '%'. $publicationArray->{'abstract'} .'%');
    }
    if (null != $publicationArray->{'keywords'}) {
        $sqlRequest .= ' AND (keywords LIKE ?)';
        array_push($values, '%'. $publicationArray->{'keywords'} .'%');
    }
    if (null != $publicationArray->{'series'}) {
        $sqlRequest .= ' AND (series LIKE ?)';
        array_push($values, '%'. $publicationArray->{'series'} .'%');
    }
    if (null != $publicationArray->{'localite'}) {
        $sqlRequest .= ' AND (localite LIKE ?)';
        array_push($values, '%'. $publicationArray->{'localite'} .'%');
    }
    if (null != $publicationArray->{'publisher'}) {
        $sqlRequest .= ' AND (publisher LIKE ?)';
        array_push($values, '%'. $publicationArray->{'publisher'} .'%');
    }
    if (null != $publicationArray->{'editor'}) {
        $sqlRequest .= ' AND (editor LIKE ?)';
        array_push($values, '%'. $publicationArray->{'editor'} .'%');
    }
    if (null != $publicationArray->{'pdf'}) {
        $sqlRequest .= ' AND (pdf LIKE ?)';
        array_push($values, '%'. $publicationArray->{'pdf'} .'%');
    }
    if (null != $publicationArray->{'date_display'}) {
        $sqlRequest .= ' AND (date_display LIKE ?)';
        array_push($values, '%'. $publicationArray->{'date_display'} .'%');
    }
    $query = $app['db']->executeQuery($sqlRequest, $values);
    $results = $query->fetchAll();
    if (null == $results) {
        return new Response(null, 400);
    }
    $publications = array();
    foreach ($results as $publication) {
        array_push($publications, $publication);
    }
    $jsonPublications = json_encode($publications);

    return new Response($jsonPublications, 200);
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

$app->get('/admin/publications/asc', function (Request $request) use ($app) {
    if (null == $request->getContent()) {
        return new Response(null, 404);
    }
    $attributeArray = json_decode($request->getContent());
    if (!isset($attributeArray->{'column'})) {
        return new Response(null, 404);
    }
    $sqlRequest = 'SELECT * FROM publication ORDER BY ' . $attributeArray->{'column'};
    $query = $app['db']->executeQuery($sqlRequest);
    $results = $query->fetchAll();
    if (null == $results) {
        return new Response(null, 400);
    }
    $publications = array();
    foreach ($results as $row) {
        array_push($publications, $row);
    }
    $jsonPublications = json_encode($publications);

    return new Response($jsonPublications, 200);
});

$app->get('/admin/publications/desc', function (Request $request) use ($app) {
    if (null == $request->getContent()) {
        return new Response(null, 404);
    }
    $attributeArray = json_decode($request->getContent());
    if (!isset($attributeArray->{'column'})) {
        return new Response(null, 404);
    }
    $sqlRequest = 'SELECT * FROM publication ORDER BY ' . $attributeArray->{'column'} . ' DESC';
    $query = $app['db']->executeQuery($sqlRequest);
    $results = $query->fetchAll();
    if (null == $results) {
        return new Response(null, 400);
    }
    $publications = array();
    foreach ($results as $row) {
        array_push($publications, $row);
    }
    $jsonPublications = json_encode($publications);

    return new Response($jsonPublications, 200);
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

return $app;
