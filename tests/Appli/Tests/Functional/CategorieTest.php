<?php
namespace Appli\Tests\Functional;

use Silex\WebTestCase;

class CategorieTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../app/app.php';
        $app['debug'] = true;
        $app['session.test'] = true;

        return $app;
    }

}
