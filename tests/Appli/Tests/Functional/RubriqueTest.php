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

    /*public function testGetAllCategoriesWithoutCategories()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/categories', array(), array(), array(
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
    }*/

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
            'CONTENT_TYPE'  => 'fr'
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
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"titre_en":"test1en","actif":1,"position":7}');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    /*public function testPostCategorieWithoutTitreEN()
    {

    }*/

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

    $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"titre_fr":"test1fr","titre_en":"test1en","actif":1,"position":7}');

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

    $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"ID":7,"titre_fr":"test2fr","titre_en":"test2en","actif":1,"position":7}');

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

    public function testGetRubriqueByIdWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/1');
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

    /*public function testUpdateCategorieWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('PUT', '/admin/categories/2');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testUpdateCategorieWithoutContent()
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

        $client->request('PUT', '/admin/categories/2', array(), array(), array(), null);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testUpdateCategorieByNonExistingId()
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

        $client->request('PUT', '/admin/categories/1000', array(), array(), array(), '{"name_fr":"Conferences nationales","name_en":"National conferences"}');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testUpdateCategorieWithoutNameFR()
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

        $client->request('PUT', '/admin/categories/2', array(), array(), array(), '{"name_en":"National conferences"}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testUpdateCategorieWithoutNameEN()
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

        $client->request('PUT', '/admin/categories/2', array(), array(), array(), '{"name_fr":"Conferences nationales"}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testUpdateCategorie()
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

        $client->request('PUT', '/admin/categories/2', array(), array(), array(), '{"name_fr":"Conferences nationales","name_en":"National conferences"}');
        //$this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testDeleteCategorieWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/categories/2');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testDeleteCategorieByNonExistingId()
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

        $client->request('DELETE', '/admin/categories/1000', array(), array(), array(), null);
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testDeleteCategorieByExistingId()
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

        $client->request('DELETE', '/admin/categories/2', array(), array(), array(), null);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testDeleteAllCategorieWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/categories');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testDeleteAllCategorie()
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

        $client->request('DELETE', '/admin/categories', array(), array(), array(), null);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }
    */
}
