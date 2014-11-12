<?php

$app = require_once __DIR__ . '/config/config.php';

$app->get('/', function () use ($app) {
    return 'Hello';
});

return $app;
