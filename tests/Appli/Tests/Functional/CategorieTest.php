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

    public function testPostCategorieWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('POST', '/admin/categorie');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testPostCategorieWithoutContent()
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

        $client->request('POST', '/admin/categorie', array(), array(), array(), null);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostCategorieWithoutNameFR()
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

        $client->request('POST', '/admin/categorie', array(), array(), array(), '{"name_en":"International and national conferences"}');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostCategorieWithoutNameEN()
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

        $client->request('POST', '/admin/categorie', array(), array(), array(), '{"name_fr":"Conferences nationales et internationales"}');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPostCategorieWithoutID()
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

        $client->request('POST', '/admin/categorie', array(), array(), array(), '{"name_fr":"Conferences nationales et internationales","name_en":"International and national conferences"}');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

     public function testPostCategorieWithID()
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

         $this->assertEquals(200, $client->getResponse()->getStatusCode());
         $this->assertEquals(null, $client->getResponse()->getContent());
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

    public function testGetAllCategories()
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

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('[{', $client->getResponse()->getContent());
        $this->assertContains('International and national conferences', $client->getResponse()->getContent());
    }

    public function testGetCategorieByExistingId()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/categories/2', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"ID":"2","name_fr":"Conferences nationales et internationales","name_en":"International and national conferences"}',
                    $client->getResponse()->getContent());
    }

    public function testGetCategorieByNonExistingId()
        {
            $client = $this->createClient();
            $crawler = $client->request('GET', '/admin/categories/1000', array(), array(), array(
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
}
