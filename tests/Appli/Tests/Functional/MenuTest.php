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

    public function testAdminPageMenuAll()
    {
        $expected = '[{"ID":"1","titre_fr":"home","titre_en":"home","actif":"1","position":"2"},{"ID":"2","titre_fr":"Recherche","titre_en":"Research","actif":"1","position":"3"},{"ID":"3","titre_fr":"Publications","titre_en":"Publications","actif":"1","position":"7"},{"ID":"4","titre_fr":"Enseignement","titre_en":"Teaching","actif":"1","position":"4"},{"ID":"5","titre_fr":"Outils","titre_en":"Tools","actif":"1","position":"5"},{"ID":"6","titre_fr":"Liens","titre_en":"Links","actif":"1","position":"6"}]';
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

        $client->request('GET', '/admin/menus');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $client->getResponse()->getContent());

    }

    public function testAdminPageMenuAllWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/menus');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

}
