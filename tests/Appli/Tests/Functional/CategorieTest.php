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

    public function testGetAllCategoriesWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/categories');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetCategorieByIdWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/categories/1');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }
}
