<?php

namespace App\Tests\Controller;

use App\Tests\Controller\AppWebTestCase;

class CustomerControllerTest extends AppWebTestCase
{
    public function testPhonesIndexInvalidJWT(): void
    {
        $client = $this->client();

        // test without JWT
        $client->jsonRequest('GET', '/api/customers', []);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'JWT Token not found');

        // test with wrong JWT
        $client->jsonRequest('GET', '/api/customers', [], [
            'HTTP_Authorization' => 'Bearer wrong.jwt'
        ]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'Invalid JWT Token');
    }

    public function testPhonesIndex(): void
    {
        $client = $this->client();
        $client->jsonRequest('GET', '/api/customers', [], [
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

    public function textPhonesShowInvalidJWT(): void
    {
        $client = $this->client();
        $phoneId = $this->getCustomer()->getId();

        // test without JWT
        $client->jsonRequest('GET', '/api/customers/' . $phoneId, []);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'JWT Token not found');

        // test with wrong JWT
        $client->jsonRequest('GET', '/api/customers/' . $phoneId, [], [
            'HTTP_Authorization' => 'Bearer wrong.jwt'
        ]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'Invalid JWT Token');
    }

    public function testPhonesShow(): void
    {
        $client = $this->client();
        $phoneId = $this->getCustomer()->getId();

        $client->jsonRequest('GET', '/api/customers/' . $phoneId, [], [
            'HTTP_Authorization' => 'Bearer ' . $this->getUserJWT()
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertKeyContains('id', $phoneId);
        $this->assertKeyContains('type', 'Customer');
        $this->assertHasKey('ressource');
        $this->assertKeyHasKey('links', 'self');
        $this->assertKeyHasKey('links', 'list');
    }

    public function testPhonesAddInvalidJWT(): void
    {
        $client = $this->client();

        // test without JWT
        $client->jsonRequest('POST', '/api/customers', []);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'JWT Token not found');

        // test with wrong JWT
        $client->jsonRequest('POST', '/api/customers', [], [
            'HTTP_Authorization' => 'Bearer wrong.jwt'
        ]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'Invalid JWT Token');
    }

    public function testPhonesAdd(): void
    {
        $client = $this->client();
        $client->jsonRequest('POST', '/api/customers', [
            'gender' => 'Mr.',
            'firstName' => 'Chandler',
            'lastName' => 'Bing',
            'phoneNumber' => '+14409630378',
            'email' => 'chandler.bing@example.com',
            'address' => [
                'address' => '774 Schmeler Vista Suite 270',
                'city' => 'Russelport',
                'zipCode' => '58871',
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $this->getUserJWT()
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertKeyContains('type', 'Customer');
        $this->assertHasKey('ressource');
        $this->assertKeyHasKey('links', 'self');
        $this->assertKeyHasKey('links', 'list');
    }

    public function testPhonesDeleteInvalidJWT(): void
    {
        $client = $this->client();
        $phoneId = $this->getCustomer()->getId();

        // test without JWT
        $client->jsonRequest('DELETE', '/api/customers/' . $phoneId, []);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'JWT Token not found');

        // test with wrong JWT
        $client->jsonRequest('DELETE', '/api/customers/' . $phoneId, [], [
            'HTTP_Authorization' => 'Bearer wrong.jwt'
        ]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'Invalid JWT Token');
    }

    public function testPhonesDelete(): void
    {
        $client = $this->client();
        $phoneId = $this->getCustomer()->getId();

        $client->jsonRequest('DELETE', '/api/customers/' . $phoneId, [], [
            'HTTP_Authorization' => 'Bearer ' . $this->getUserJWT()
        ]);
        $this->assertResponseStatusCodeSame(200);
        $this->assertKeyContains('id', $phoneId);
        $this->assertKeyContains('type', 'Customer');
        $this->assertHasKey('ressource');
        $this->assertKeyHasKey('links', 'self');
        $this->assertKeyHasKey('links', 'list');
    }
}
