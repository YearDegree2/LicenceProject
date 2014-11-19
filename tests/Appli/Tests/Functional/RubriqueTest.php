<?php
namespace Appli\Tests\Functional;

use Silex\WebTestCase;

class RubriqueTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../app/app.php';
        $app['debug'] = true;
        $app['session.test'] = true;

        $sqlRequest = 'ALTER TABLE menu AUTO_INCREMENT=0';
        $app['db']->executeUpdate($sqlRequest);

        $sqlRequest = 'ALTER TABLE rubrique AUTO_INCREMENT=0';
        $app['db']->executeUpdate($sqlRequest);

        return $app;
    }

    public function testGetAllRubriquesWithoutRubriques()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostRubriqueWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('POST', '/admin/rubrique');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testPostRubriqueWithoutContent()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin', array(), array(), array(
        'CONTENT_TYPE' => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
        '_username' => 'admin',
        '_password' => 'admin',
        ));
        $client->submit($form);
        $client->request('POST', '/admin/rubrique', array(), array(), array(), null);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostRubriqueWithoutTitreFR()
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

        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"titre_en":"Home","actif":1,"position":2}');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostRubriqueWithoutTitreEN()
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

        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"titre_fr":"Home","actif":1,"position":2}');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostRubriqueWithoutID()
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

    $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"titre_fr":"home","titre_en":"home","actif":1,"position":2}');

    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertEquals(null, $client->getResponse()->getContent());

    }

    public function testPostRubriqueWithID()
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

    $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"ID":2,"titre_fr":"Recherche","titre_en":"Research","actif":1,"position":3}');

    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetAllRubriques()
    {
        $expected = '[{"ID":"1","titre_fr":"home","titre_en":"home","actif":"1","position":"2","date_creation":"2014-11-19","date_modification":"2014-11-19","content_fr":"Bienvenue","content_en":"Welcome","menu_id":"1"},{"ID":"2","titre_fr":"Recherche","titre_en":"Research","actif":"1","position":"3","date_creation":"2014-11-19","date_modification":"2014-11-19","content_fr":"Bienvenue","content_en":"Welcome","menu_id":"2"}]';
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

    public function testGetRubriqueByIdWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/2');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
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

    public function testGetRubriquesByExistingId()
    {
        $expected = '{"ID":"2","titre_fr":"Recherche","titre_en":"Research","actif":"1","position":"3","date_creation":"2014-11-19","date_modification":"2014-11-19","content_fr":"Bienvenue","content_en":"Welcome","menu_id":"2"}';
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

        $client->request('GET', '/admin/rubriques/2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals($expected, $client->getResponse()->getContent());
    }

    public function testUpdateRubriqueWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('PUT', '/admin/rubriques/2');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testUpdateRubriqueWithoutContent()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $client->request('PUT', '/admin/rubriques/2', array(), array(), array(), null);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testUpdateRubriqueByNonExistingId()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $client->request('PUT', '/admin/rubriques/1000', array(), array(), array(), '{"titre_fr":"RechercheMaJ","titre_en":"ResearchMaj","actif":1,"position":3}');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testUpdateRubriqueWithoutTitreFR()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $client->request('PUT', '/admin/rubriques/2', array(), array(), array(), '{"titre_en":"ResearchMaj","actif":1,"position":3}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testUpdateRubriqueWithoutTitreEN()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $client->request('PUT', '/admin/rubriques/2', array(), array(), array(), '{"titre_fr":"RechercheMaJ","actif":1,"position":3');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testUpdateRubrique()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $client->request('PUT', '/admin/rubriques/2', array(), array(), array(), '{"titre_fr":"RechercheMaJ2","titre_en":"ResearchMaj2"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testDeleteRubriqueWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/rubriques/2');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testDeleteRubriqueByNonExistingId()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin', array(), array(), array(
        'CONTENT_TYPE' => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
        '_username' => 'admin',
        '_password' => 'admin',
        ));
        $client->submit($form);

        $client->request('DELETE', '/admin/rubriques/1000', array(), array(), array(), null);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testDeleteRubriquesByExistingId()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin', array(), array(), array(
        'CONTENT_TYPE' => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
        '_username' => 'admin',
        '_password' => 'admin',
        ));
        $client->submit($form);

        $client->request('DELETE', '/admin/rubriques/2', array(), array(), array(), null);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testDeleteAllRubriqueWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/rubriques');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testDeleteAllRubrique()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin', array(), array(), array(
        'CONTENT_TYPE' => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
        '_username' => 'admin',
        '_password' => 'admin',
        ));
        $client->submit($form);

        $client->request('DELETE', '/admin/rubriques', array(), array(), array(), null);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

}
