<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RouteTest extends WebTestCase
{
    public function test()
    {
        $client = static::createClient();
        $client->request('GET', '/product2');
        //$this->assertResponseStatusCodeSame("200" );
        //$this->assertResponseIsSuccessful();
        $this->assertPageTitleContains("Product");
    }
}