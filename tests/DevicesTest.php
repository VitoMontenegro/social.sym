<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;

class DevicesTest extends ApiTestCase
{
    public function testSomething(): void
    {

        $client = static::createClient();
        $response = $client->request('POST','/device', array('body' => array( "uuid"=>'b0508c1a-ac4f-45db-b495-1e7d33c2f5a4' )));
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains(['message' => 'Welcome to your new controller!']);
    }
}
