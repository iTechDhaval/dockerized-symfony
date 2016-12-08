<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testGet()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product');
    }

    public function testId()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/{id}');
    }

    public function testPost()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product');
    }

    public function testUpdate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/{id}');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/{id}');
    }

}
