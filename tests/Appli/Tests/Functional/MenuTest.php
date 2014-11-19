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

    public function testGetMenuAllWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/menus');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetMenuAll()
    {
        $expected = '[{"ID":"8","titre_fr":"Home","titre_en":"Home","actif":"1","position":"2"},{"ID":"9","titre_fr":"Recherche","titre_en":"Research","actif":"1","position":"3"}]';

        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin', array(), array(), array(
            'CONTENT_TYPE'  => 'fr'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Envoyer');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"ID":8,"titre_fr":"Home","titre_en":"Home","actif":1,"position":2}');
        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"ID":9,"titre_fr":"Recherche","titre_en":"Research","actif":1,"position":3}');

        $client->request('GET', '/admin/menus');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $client->getResponse()->getContent());

        $client->request('DELETE', '/admin/rubriques/8', array(), array(), array(), null);
        $client->request('DELETE', '/admin/rubriques/9', array(), array(), array(), null);

    }

}
