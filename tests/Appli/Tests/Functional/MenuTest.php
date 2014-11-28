<?php
namespace Appli\Tests\Functional;

use Silex\WebTestCase;

class MenuTest extends WebTestCase
{
    public function createApplication()

    {
        $app = require __DIR__ . '/../../../../app/app.php';
        $app['debug'] = true;
        $app['session.test'] = true;

        return $app;
    }

    /**
     * Test GET /menus sans menus.
     */
    public function testGetAllMenusWithoutMenus()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/menus');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('No menus', $client->getResponse()->getContent());
    }

    /**
     * Test GET /menus/actif sans menus.
     */
    public function testGetAllMenusActifWithoutMenusActif()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/menus/actif');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('No menus', $client->getResponse()->getContent());
    }

    /**
     * Test GET /menus ok.
     */
    public function testGetAllMenus()
    {
        $client = $this->createClient();
        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"ID":1,"titre_fr":"Home","titre_en":"Home","actif":1,"position":2}');
        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"ID":2,"titre_fr":"Recherche","titre_en":"Research","actif":0,"position":3}');
        $client->request('GET', '/admin/menus');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('[{', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Home","titre_en":"Home"', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Recherche","titre_en":"Research"', $client->getResponse()->getContent());
    }

    /**
     * Test GET /menus/actif ok.
     */
    public function testGetAllMenusActif()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/menus/actif');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('[{', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Home","titre_en":"Home"', $client->getResponse()->getContent());
        $this->assertNotContains('"titre_fr":"Recherche","titre_en":"Research"', $client->getResponse()->getContent());
    }

    /**
     * Test GET /menus/id avec un id inexistant.
     */
    public function testGetMenuByIDWithoutExistingID()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/menus/1000');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Menu don\'t exists', $client->getResponse()->getContent());
    }

    /**
     * Test GET /menus/id avec un id existant
     */
    public function testGetMenuByIDWithExistingID()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/menus/1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"ID":"1","titre_fr":"Home","titre_en":"Home","actif":"1","position":"2"}', $client->getResponse()->getContent());

        $client->request('DELETE', '/admin/rubriques/1', array(), array(), array(), null);
        $client->request('DELETE', '/admin/rubriques/2', array(), array(), array(), null);
    }

}
