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

    public function testGetMenuAll()
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

    public function testGetMenuAllWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/menus');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetRubriquesAll()
    {
        $expected = '[{"ID":"1","titre_fr":"home","titre_en":"home","actif":"1","position":"2","date_creation":"2014-11-14","date_modification":"2014-11-14","content_fr":"Bienvenue","content_en":"Welcome","menu_id":"1"},{"ID":"2","titre_fr":"Recherche","titre_en":"Research","actif":"1","position":"3","date_creation":"2014-11-14","date_modification":"2014-11-14","content_fr":"Bienvenue sur mes recherches","content_en":"Welcome","menu_id":"2"},{"ID":"3","titre_fr":"Publications","titre_en":"Publications","actif":"1","position":"7","date_creation":"2014-11-14","date_modification":"2014-11-14","content_fr":"Bienvenue sur mes publications","content_en":"Welcome","menu_id":"3"},{"ID":"4","titre_fr":"Enseignement","titre_en":"Teaching","actif":"1","position":"4","date_creation":"2014-11-14","date_modification":"2014-11-14","content_fr":"Bienvenue sur mes enseignements","content_en":"Welcome","menu_id":"4"},{"ID":"5","titre_fr":"Outils","titre_en":"Tools","actif":"1","position":"5","date_creation":"2014-11-14","date_modification":"2014-11-14","content_fr":"Bienvenue sur mes outils","content_en":"Welcome","menu_id":"5"},{"ID":"6","titre_fr":"Liens","titre_en":"Links","actif":"1","position":"6","date_creation":"2014-11-14","date_modification":"2014-11-14","content_fr":"Bienvenue sur mes liens","content_en":"Welcome","menu_id":"6"}]';
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

        $client->request('GET', '/admin/rubriques');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $client->getResponse()->getContent());
    }

    public function testGetRubriquesAllWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetRubriquesById()
    {
        $expected = '{"ID":"3","titre_fr":"Publications","titre_en":"Publications","actif":"1","position":"7","date_creation":"2014-11-14","date_modification":"2014-11-14","content_fr":"Bienvenue sur mes publications","content_en":"Welcome","menu_id":"3"}';
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

        $client->request('GET', '/admin/rubriques/3');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $client->getResponse()->getContent());
    }

    public function testGetRubriqueByNonExistingId()
    {
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

        $client->request('GET', '/admin/rubriques/1000');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
    }

    public function testGetRubriquesByIdWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/3');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

}
