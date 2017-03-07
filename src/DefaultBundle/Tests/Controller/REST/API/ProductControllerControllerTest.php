<?php

namespace DefaultBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class REST\API\ProductControllerControllerTest extends WebTestCase
{
    public function testProductlist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/productList');
    }

}
