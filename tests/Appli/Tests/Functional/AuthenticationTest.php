<?php
namespace Appli\Tests\Functional;

use Silex\WebTestCase;

class AuthenticationTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../app/app.php';
        $app['debug'] = true;
        return $app;
    }

    public function testInitialPage() {
        $client = $this->createClient();
        $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
