<?php

namespace App\Tests\Controller;

use App\Tests\Controller\AppWebTestCase;

class PhoneControllerTest extends AppWebTestCase
{
    public function testPhonesIndexInvalidJWT(): void
    {
        $client = $this->client();

        // test without JWT
        $client->jsonRequest('GET', '/api/phones', []);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'JWT Token not found');

        // test with wrong JWT
        $client->jsonRequest('GET', '/api/phones', [], [
            'HTTP_Authorization' => 'Bearer wrong.jwt'
        ]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'Invalid JWT Token');
    }

    public function testPhonesIndex(): void
    {
        $client = $this->client();
        $client->jsonRequest('GET', '/api/phones', [], [
            'HTTP_Authorization' => 'Bearer ' . $this->getUserJWT()
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertHasKey('count');
        $this->assertHasKey('total');
        $this->assertHasKey('embedded');
        $this->assertKeyHasKey('links', 'self');
        $this->assertKeyHasKey('links', 'first');
        $this->assertKeyHasKey('links', 'next');
        $this->assertKeyHasKey('links', 'last');
    }

    public function testPhonesShowInvalidJWT(): void
    {
        $client = $this->client();
        $phoneId = $this->getPhone()->getId();

        // test without JWT
        $client->jsonRequest('GET', '/api/phones/' . $phoneId, []);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'JWT Token not found');

        // test with wrong JWT
        $client->jsonRequest('GET', '/api/phones/' . $phoneId, [], [
            'HTTP_Authorization' => 'Bearer wrong.jwt'
        ]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'Invalid JWT Token');
    }

    public function testPhonesShow(): void
    {
        $client = $this->client();
        $phoneId = $this->getPhone()->getId();

        $client->jsonRequest('GET', '/api/phones/' . $phoneId, [], [
            'HTTP_Authorization' => 'Bearer ' . $this->getUserJWT()
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertKeyContains('id', $phoneId);
        $this->assertKeyContains('type', 'Phone');
        $this->assertHasKey('ressource');
        $this->assertKeyHasKey('links', 'self');
        $this->assertKeyHasKey('links', 'list');
    }
}
