<?php

$app = require __DIR__ . '/config/config.php';

$app = require __DIR__ . '/controllers/Login.php';
$app = require __DIR__ . '/controllers/Categorie.php';
$app = require __DIR__ . '/controllers/Publication.php';
$app = require __DIR__ . '/controllers/Menu.php';
$app = require __DIR__ . '/controllers/Rubrique.php';

return $app;
