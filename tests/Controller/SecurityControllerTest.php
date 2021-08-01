<?php

namespace App\Tests\Controller;

use App\Tests\Controller\AppWebTestCase;

class SecurityControllerTest extends AppWebTestCase
{
    public function testLogin(): void
    {
        $client = $this->client();

        // test with invalid credentials
        $client->jsonRequest('POST', '/api/login_check', [
            'username' => 'contact@example.com',
            'password' => '123456'
        ]);
        $this->assertResponseStatusCodeSame(401);
        $this->assertKeyContains('message', 'Invalid credentials.');

        // test with valid credentials
        $client->jsonRequest('POST', '/api/login_check', [
            'username' => $this->getUser()->getEmail(),
            'password' => '123456'
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertHasKey('token');
    }
}
