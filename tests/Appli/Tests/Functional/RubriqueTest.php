<?php
namespace Appli\Tests\Functional;

use Silex\WebTestCase;
use Appli\PasswordEncoder;

class RubriqueTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__ . '/../../../../app/app.php';
        $app['debug'] = true;
        $app['session.test'] = true;

        return $app;
    }

    /**
     * Test POST /rubrique sans contenu.
     */
    public function testPostRubriqueWithoutContent()
    {
        $client = $this->createClient();
        $client->request('POST', '/admin/rubrique');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('No content', $client->getResponse()->getContent());
    }

    /**
     * Test POST /rubrique sans l'attribut a (pour la connexion).
     */
    public function testPostRubriqueWithoutAAttribute()
    {
        $client = $this->createClient();
        $client->request('POST', '/admin/rubrique', array(), array(), array(),
            '{"titre_fr":"Enseignement","titre_en":"Teaching","actif":1}');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertEquals('Admin not connected', $client->getResponse()->getContent());
    }

    /**
     * Test POST /rubrique avec l'attribut a faux.
     */
    public function testPostRubriqueWithAAttributeFalse()
    {
        $client = $this->createClient();
        $client->request('POST', '/admin/rubrique', array(), array(), array(),
            '{"a":"toto","titre_fr":"Enseignement","titre_en":"Teaching","actif":1}');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertEquals('Admin not connected', $client->getResponse()->getContent());
    }

    /**
     * Test POST /rubrique sans l'attribut titre_fr.
     */
    public function testPostRubriqueWithoutTitreFrAttribute()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('POST', '/admin/rubrique', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'","titre_en":"Teaching","actif":1}');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('Attributes titre_fr, titre_en or actif not here', $client->getResponse()->getContent());
    }

    /**
    * Test POST /rubrique sans l'attribut titre_en.
    */
    public function testPostRubriqueWithoutTitreEnAttribute()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('POST', '/admin/rubrique', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'","titre_fr":"Enseignement","actif":1}');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('Attributes titre_fr, titre_en or actif not here', $client->getResponse()->getContent());
    }

    /**
     * Test POST /rubrique sans l'attribut actif.
     */
    public function testPostRubriqueWithoutActifAttribute()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('POST', '/admin/rubrique', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'","titre_fr":"Enseignement","titre_en":"Teaching"}');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('Attributes titre_fr, titre_en or actif not here', $client->getResponse()->getContent());
    }


    /**
     * Test POST /rubrique sans l'attribut ID.
     */
    public function testPostRubriqueWithoutID()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('POST', '/admin/rubrique', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'","titre_fr":"Enseignement","titre_en":"Teaching","actif":1,"content_fr":"Contenu","content_en":"Content"}');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Rubrique created', $client->getResponse()->getContent());
    }

    /**
     * Test POST /rubrique avec l'attribut ID.
     */
    public function testPostRubriqueWithID()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('POST', '/admin/rubrique', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'","ID":1,"titre_fr":"Enseignement","titre_en":"Teaching","actif":1,"content_fr":"Contenu","content_en":"Content"}');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Rubrique created', $client->getResponse()->getContent());
    }

    /**
     * Test PUT /rubriques/id sans contenu.
     */
    public function testPutRubriqueByIdWithoutContent()
    {
        $client = $this->createClient();
        $client->request('PUT', '/admin/rubriques/1');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('No content', $client->getResponse()->getContent());
    }

    /**
     * Test PUT /rubriques/id sans l'attribut a (pour la connexion).
     */
    public function testPutRubriqueByIdWithoutAAttribute()
    {
        $client = $this->createClient();
        $client->request('PUT', '/admin/rubriques/1', array(), array(), array(),
            '{}');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertEquals('Admin not connected', $client->getResponse()->getContent());
    }

    /**
     * Test PUT /rubriques/id avec l'attribut a faux.
     */
    public function testPutRubriqueByIdWithAAttributeFalse()
    {
        $client = $this->createClient();
        $client->request('PUT', '/admin/rubriques/1', array(), array(), array(),
            '{"a":"toto"}');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertEquals('Admin not connected', $client->getResponse()->getContent());
    }

    /**
     * Test PUT /rubriques/id avec un ID inexistant.
     */
    public function testPutRubriqueByIdWithoutExistingId()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('PUT', '/admin/rubriques/1000', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'","titre_fr":"OutilsMaJ","titre_en":"ToolsMaj","actif":1,"position":5}');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Menu don\'t exists', $client->getResponse()->getContent());
    }

    /**
     * Test PUT /rubriques/id sans l'attribut titre_fr.
     */
    public function testPutRubriqueByIdWithoutTitreFr()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('PUT', '/admin/rubriques/1', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'","titre_en":"ToolsMaj","actif":1,"position":5}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('Attributes titre_fr or titre_en not here', $client->getResponse()->getContent());
    }

    /**
     * Test PUT /rubriques/id sans l'attribut titre_en.
     */
    public function testPutRubriqueByIdWithoutTitreEn()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('PUT', '/admin/rubriques/1', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'","titre_fr":"OutilsMaJ","actif":1,"position":5}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('Attributes titre_fr or titre_en not here', $client->getResponse()->getContent());
    }

    /**
     * Test PUT /rubriques/id ok.
     */
    public function testPutRubriqueById()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('PUT', '/admin/rubriques/1', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'","titre_fr":"OutilsMaJ2","titre_en":"ToolsMaJ2"}');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Rubrique updated', $client->getResponse()->getContent());
    }

    /**
     * Test PUT /rubriques/id avec attributs content_fr et content_en ok.
     */
    public function testPutRubriqueByIdWithContentFrEn()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('PUT', '/admin/rubriques/1', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'","titre_fr":"MaJContentFr","titre_en":"MaJContentEn", "content_fr":"update content_fr", "content_en":"update content_en"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Rubrique updated', $client->getResponse()->getContent());
    }

    /**
     * Test DELETE /rubriques/id sans contenu.
     */
    public function testDeleteRubriqueByIdWithoutContent()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/rubriques/1');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('No content', $client->getResponse()->getContent());
    }

    /**
     * Test DELETE /rubriques/id sans l'attribut a (pour la connexion).
     */
    public function testDeleteRubriqueByIdWithoutAAttribute()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/rubriques/1', array(), array(), array(),
            '{}');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertEquals('Admin not connected', $client->getResponse()->getContent());
    }

    /**
     * Test DELETE /rubriques/id avec l'attribut a faux.
     */
    public function testDeleteRubriqueByIdWithAAttributeFalse()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/rubriques/1', array(), array(), array(),
            '{"a":"toto"}');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertEquals('Admin not connected', $client->getResponse()->getContent());
    }

    /**
     * Test DELETE /rubriques/id avec un ID inexistant.
     */
    public function testDeleteRubriqueByIdWithoutExistingID()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('DELETE', '/admin/rubriques/1000', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'"}');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Menu don\'t exists', $client->getResponse()->getContent());
    }

    /**
     * Test DELETE /rubriques/id avec un ID existant.
     */
    public function testDeleteRubriqueByIdWithExistingID()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('DELETE', '/admin/rubriques/1', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'"}');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Rubrique deleted', $client->getResponse()->getContent());
    }

    /**
     * Test DELETE /rubriques sans contenu.
     */
    public function testDeleteAllRubriquesWithoutContent()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/rubriques');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals('No content', $client->getResponse()->getContent());
    }

    /**
     * Test DELETE /rubriques sans l'attribut a (pour la connexion).
     */
    public function testDeleteAllRubriquesWithoutAAttribute()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/rubriques', array(), array(), array(),
            '{}');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertEquals('Admin not connected', $client->getResponse()->getContent());
    }

    /**
     * Test DELETE /rubriques avec l'attribut a faux.
     */
    public function testDeleteAllRubriquesWithAAttributeFalse()
    {
        $client = $this->createClient();
        $client->request('DELETE', '/admin/rubriques', array(), array(), array(),
            '{"a":"toto"}');

        $this->assertEquals(403, $client->getResponse()->getStatusCode());
        $this->assertEquals('Admin not connected', $client->getResponse()->getContent());
    }

    /**
     * Test DELETE /rubriques ok.
     */
    public function testDeleteAllCategories()
    {
        $client = $this->createClient();
        $encoder = new PasswordEncoder();
        $client->request('DELETE', '/admin/rubriques', array(), array(), array(),
            '{"a":"' . $encoder->encodePassword('Admin connected') .'"}');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('Rubriques deleted', $client->getResponse()->getContent());
    }
}
    /*
    public function testGetAllRubriquesWithoutRubriques()
    {
       // assertTrue(true);
    }}/*
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

    public function testCountRubriqueWithoutRubriques()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques/count', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"COUNT(*)":"0"}', $client->getResponse()->getContent());
    }

    public function testGetRubriquesOrderByAttributeWithColumnAttributeWithoutRubriques()
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

        $client->request('GET', '/admin/rubriques/asc', array(), array(), array(), '{"column":"titre_en"}');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetRubriquesOrderByAttributeDESCWithColumnAttributeWithoutRubriques()
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

        $client->request('GET', '/admin/rubriques/desc', array(), array(), array(), '{"column":"titre_en"}');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetFirstRubriqueByPositionWithoutRubrique()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques/first', array(), array(), array(
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
        $crawler = $client->request('GET', '/admin/rubriques', array(), array(), array(
            'CONTENT_TYPE'  => 'fr'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Envoyer');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('[{', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Enseignement","titre_en":"Teaching"', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Outils","titre_en":"Tools"', $client->getResponse()->getContent());
        $this->assertContains('"content_fr":"Blabla content_fr without id","content_en":"Blabla content_en"', $client->getResponse()->getContent());
        $this->assertContains('"content_fr":"Blabla content_fr and id","content_en":"Blabla content_en and id"', $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesByTitreFRWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/titre_fr', array(), array(), array(), '{"titre_fr":"Enseignement"}');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesByTitreFRWithoutContent()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques/titre_fr', array(), array(), array(
            'CONTENT_TYPE'  => 'fr'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Envoyer');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesByTitreFRWithoutTitreFR()
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
        $client->request('GET', '/admin/rubriques/titre_fr', array(), array(), array(), '{"titre_en":"Teaching"}');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesByTitreFRWithNonExistingTitreFR()
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
        $client->request('GET', '/admin/rubriques/titre_fr', array(), array(), array(), '{"titre_fr":"Teaching"}');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesByTitreFRWithExistingTitreFR()
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
        $client->request('GET', '/admin/rubriques/titre_fr', array(), array(), array(), '{"titre_fr":"Enseignement"}');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('[{', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Enseignement","titre_en":"Teaching"', $client->getResponse()->getContent());
        $this->assertContains('"actif":"1","position":"4"', $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesByTitreENWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/titre_en', array(), array(), array(), '{"titre_en":"Teaching"}');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesByTitreENWithoutContent()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques/titre_en', array(), array(), array(
            'CONTENT_TYPE'  => 'fr'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Envoyer');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesByTitreENWithoutTitreEN()
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
        $client->request('GET', '/admin/rubriques/titre_en', array(), array(), array(), '{"titre_fr":"Enseignement"}');

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesByTitreENWithNonExistingTitreEN()
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
        $client->request('GET', '/admin/rubriques/titre_en', array(), array(), array(), '{"titre_en":"Enseignement"}');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetAllRubriquesByTitreENWithExistingTitreEN()
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
        $client->request('GET', '/admin/rubriques/titre_en', array(), array(), array(), '{"titre_en":"Teaching"}');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('[{', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Enseignement","titre_en":"Teaching"', $client->getResponse()->getContent());
        $this->assertContains('"actif":"1","position":"4"', $client->getResponse()->getContent());
    }

    public function testCountRubriqueWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/count');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testCountRubrique()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques/count', array(), array(), array(
            'CONTENT_TYPE'  => 'fr'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Envoyer');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals('{"COUNT(*)":"4"}', $client->getResponse()->getContent());
    }

    public function testGetRubriquesOrderByAttributeWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/asc');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetRubriquesOrderByAttributeWithoutContent()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques/asc', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetRubriquesOrderByAttributeWithoutColumnAttribute()
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

        $client->request('GET', '/admin/rubriques/asc', array(), array(), array(), '{"column2":"titre_en"}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetRubriquesOrderByAttribute()
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
        $client->request('GET', '/admin/rubriques/asc', array(), array(), array(), '{"column":"titre_en"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('[{', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Enseignement","titre_en":"Teaching"', $client->getResponse()->getContent());
    }

    public function testGetRubriquesOrderByAttributeDESCWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/desc');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetRubriquesOrderByAttributeDESCWithoutContent()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques/desc', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetRubriquesOrderByAttributeDESCWithoutColumnAttribute()
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

        $client->request('GET', '/admin/rubriques/desc', array(), array(), array(), '{"column2":"titre_en"}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetRubriquesOrderByAttributeDESC()
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
        $client->request('GET', '/admin/rubriques/desc', array(), array(), array(), '{"column":"titre_en"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('[{', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Enseignement","titre_en":"Teaching"', $client->getResponse()->getContent());
    }

    public function testGetFirstRubriqueByPositionWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/first');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetFirstRubriqueByPosition()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques/first', array(), array(), array(
            'CONTENT_TYPE'  => 'en'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Submit');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('"titre_fr":"Test with content","titre_en":"Test with content","actif":"1"', $client->getResponse()->getContent());
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
        $crawler = $client->request('GET', '/admin/rubriques/1000', array(), array(), array(
            'CONTENT_TYPE'  => 'fr'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Envoyer');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetRubriquesByExistingId()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques/5', array(), array(), array(
            'CONTENT_TYPE'  => 'fr'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Envoyer');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('"titre_fr":"Outils","titre_en":"Tools"', $client->getResponse()->getContent());
    }

    public function testGetRubriquesByExistingIdWithContentFrEn()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/admin/rubriques/6', array(), array(), array(
            'CONTENT_TYPE'  => 'fr'
        ), null);
        $buttonCrawlerNode = $crawler->selectButton('Envoyer');
        $form = $buttonCrawlerNode->form(array(
            '_username' => 'admin',
            '_password' => 'admin',
        ));
        $client->submit($form);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('"titre_fr":"Test id content","titre_en":"Test id content"', $client->getResponse()->getContent());
        $this->assertContains('"content_fr":"Blabla content_fr and id","content_en":"Blabla content_en and id"', $client->getResponse()->getContent());
    }

    public function testGetFilterRubriquesWithoutConnection()
    {
        $client = $this->createClient();
        $client->request('GET', '/admin/rubriques/filter');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals('Language needed: French or English', $client->getResponse()->getContent());
    }

    public function testGetFilterRubriquesWithoutContent()
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

        $client->request('GET', '/admin/rubriques/filter', array(), array(), array(), null);
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetFilterRubriquesWithoutTitre_fr()
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

        $client->request('GET', '/admin/rubriques/filter', array(), array(), array(), '{"titre_en":null,"actif":"1","position":"6","date_creation":"2014-11-24","date_modification":null,"content_fr":"impossible","content_en":null}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetFilterRubriquesWithoutOptionalFields()
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

        $client->request('GET', '/admin/rubriques/filter', array(), array(), array(), '{"titre_fr":"test","titre_en":null,"actif":"1"}');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetFilterRubriquesNull()
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

        $client->request('GET', '/admin/rubriques/filter', array(), array(), array(), '{"titre_fr":"impossible","titre_en":null,"actif":"1","position":"6","date_creation":"2014-11-24","date_modification":null,"content_fr":"impossible","content_en":null}');
        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testGetFilterRubriques()
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

        $client->request('GET', '/admin/rubriques/filter', array(), array(), array(), '{"titre_fr":"test","titre_en":null,"actif":"1","position":"6","date_creation":null,"date_modification":null,"content_fr":"Blabla","content_en":null}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('[{', $client->getResponse()->getContent());
        $this->assertContains('"titre_fr":"Test id content","titre_en":"Test id content","actif":"1","position":"6"', $client->getResponse()->getContent());
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

        $client->request('PUT', '/admin/rubriques/1', array(), array(), array(),'{"titre_fr":"OutilsMaJ","actif":1,"position":5');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPutRubriqueById()
    {
        $client = $this->createClient();
        $client->request('PUT', '/admin/rubriques/1', array(), array(), array(), '{"titre_fr":"OutilsMaJ2","titre_en":"ToolsMaJ2"}');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

    public function testPutRubriqueByIdWithContentFrEn()
    {
        $client = $this->createClient();
        $client->request('PUT', '/admin/rubriques/1', array(), array(), array(), '{"titre_fr":"MaJContentFr","titre_en":"MaJContentEn", "content_fr":"update content_fr", "content_en":"update content_en"}');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(null, $client->getResponse()->getContent());
    }

}
*/
