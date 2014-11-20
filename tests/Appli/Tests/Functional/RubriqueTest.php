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

        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"titre_en":"Teaching","actif":1,"position":4}');
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

        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"titre_fr":"Enseignement","actif":1,"position":4}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostRubriqueWithoutActif()
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

        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"titre_fr":"Enseignement","titre_en":"Teaching","position":4}');
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

        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"titre_fr":"Enseignement","titre_en":"Teaching","actif":1,"position":4}');
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

        $client->request('POST', '/admin/rubrique', array(), array(), array(), '{"ID":5,"titre_fr":"Outils","titre_en":"Tools","actif":1,"position":5}');
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
        $this->assertContains('[{', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Enseignement","titre_en":"Teaching"', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Outils","titre_en":"Tools"', $client->getResponse()->getContent());
    }

    public function testGetRubriqueByIdWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/5');
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

        $client->request('GET', '/admin/rubriques/5');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('"titre_fr":"Outils","titre_en":"Tools"', $client->getResponse()->getContent());
    }

    public function testUpdateRubriqueWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('PUT', '/admin/rubriques/5');
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

        $client->request('PUT', '/admin/rubriques/5', array(), array(), array(), null);
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

        $client->request('PUT', '/admin/rubriques/1000', array(), array(), array(), '{"titre_fr":"OutilsMaJ","titre_en":"ToolsMaj","actif":1,"position":5}');
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

        $client->request('PUT', '/admin/rubriques/5', array(), array(), array(), '{"titre_en":"ToolsMaJ","actif":1,"position":5}');
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

        $client->request('PUT', '/admin/rubriques/5', array(), array(), array(), '{"titre_fr":"OutilsMaJ","actif":1,"position":5');
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

        $client->request('PUT', '/admin/rubriques/5', array(), array(), array(), '{"titre_fr":"OutilsMaJ2","titre_en":"ToolsMaJ2"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testDeleteRubriqueWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/rubriques/5');
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

        $client->request('DELETE', '/admin/rubriques/5', array(), array(), array(), null);
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
