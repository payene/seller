<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CaisseControllerTest extends WebTestCase
{
    public function testEncours()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/vente/journee');
    }

}
