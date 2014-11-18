<?php
namespace Appli\Tests\Functional;

use Silex\WebTestCase;

class PublicationTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../app/app.php';
        $app['debug'] = true;
        $app['session.test'] = true;

        return $app;
    }

    public function testPostPublicationWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('POST', '/admin/publication');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testPostPublicationWithoutContent()
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

        $client->request('POST', '/admin/publication', array(), array(), array(), null);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostPublicationWithoutReference()
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

        $client->request('POST', '/admin/publication', array(), array(), array(), '{"auteurs":"S. Salva, A. Rollet", "titre":"Testabilite des services web", "date": "2008-05-01"}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostPublicationWithoutAuteurs()
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

        $client->request('POST', '/admin/publication', array(), array(), array(), '{"reference":"SR08a", "titre":"Testabilite des services web", "date": "2008-05-01"}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostPublicationWithoutTitre()
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

        $client->request('POST', '/admin/publication', array(), array(), array(), '{"reference":"SR08a", "auteurs":"S. Salva, A. Rollet", "date": "2008-05-01"}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostPublicationWithoutDate()
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

        $client->request('POST', '/admin/publication', array(), array(), array(), '{"reference":"SR08a", "auteurs":"S. Salva, A. Rollet", "titre":"Testabilite des services web"}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostPublicationWithoutID()
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

        $client->request('POST', '/admin/publication', array(), array(), array(), '{"reference":"SR08a", "auteurs":"S. Salva, A. Rollet", "titre":"Testabilite des services web", "date": "2008-05-01"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostPublicationWithID()
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

    $client->request('POST', '/admin/publication', array(), array(), array(), '{"ID":2, "reference":"SR08a", "auteurs":"S. Salva, A. Rollet", "titre":"Testabilite des services web", "date": "2008-05-01"}');
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertEquals(null, $client->getResponse()->getContent());
}

    public function testPostPublicationWithNonExistingCategorieID()
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

        $client->request('POST', '/admin/publication', array(), array(), array(), '{"reference":"SR08a", "auteurs":"S. Salva, A. Rollet", "titre":"Testabilite des services web", "date": "2008-05-01", "categorie_id":1000}');
        $this->assertEquals(500, $client->getResponse()->getStatusCode());
    }

    public function testPostPublicationWithExistingCategorieID()
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

        $client->request('POST', '/admin/categorie', array(), array(), array(), '{"ID":2,"name_fr":"Conferences nationales et internationales","name_en":"International and national conferences"}');

        $client->request('POST', '/admin/publication', array(), array(), array(), '{"reference":"SR08a", "auteurs":"S. Salva, A. Rollet", "titre":"Testabilite des services web", "date": "2008-05-01", "categorie_id":2}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }
}
