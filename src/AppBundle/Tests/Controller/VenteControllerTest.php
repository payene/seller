<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VenteControllerTest extends WebTestCase
{
    public function testNewvente()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/vente/create');
    }

    public function testNouvellevente()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/vente/create');
    }

    public function testListe()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/ventes');
    }

    public function testDetail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/vente/{id}');
    }

}
