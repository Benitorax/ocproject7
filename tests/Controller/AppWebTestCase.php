<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Entity\Phone;
use App\Entity\Customer;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTManager;

class AppWebTestCase extends WebTestCase
{
    private static KernelBrowser $appClient;
    private User $user;

    /**
     * Create client.
     */
    public static function client(): KernelBrowser
    {
        return self::$appClient = self::createClient();
    }

    /**
     * Return the first User object.
     */
    public function getUser(): User
    {
        return $this->user = $this->getEntityManager()->getRepository(User::class)->findAll()[0];
    }

    /**
     * Return the first Phone object.
     */
    public function getPhone(): Phone
    {
        return $this->getEntityManager()->getRepository(Phone::class)->findAll()[0];
    }

    /**
     * Return the first Customer object.
     */
    public function getCustomer(): Customer
    {
        return $this->getEntityManager()->getRepository(Customer::class)
            ->findBy(['user' => $this->getUser()])[0];
    }

    /**
     * Load service from container with given id.
     */
    protected function getService(string $id): ?object
    {
        return static::getContainer()->get($id);
    }

    /**
     * Assert response contains given key and text.
     * @param mixed $text
     */
    public function assertKeyContains(string $key, $text): void
    {
        $this->assertSame(static::getResponse()[$key], $text);
    }

    /**
     * Assert response has key which has a given key.
     */
    public function assertKeyHasKey(string $key, string $subKey): void
    {
        $this->assertContains($subKey, array_keys(static::getResponse()[$key]));
    }

    /**
     * Assert response contains given key.
     */
    public function assertHasKey(string $key): void
    {
        $this->assertContains($key, array_keys(static::getResponse()));
    }

    /**
     * Return the response.
     */
    private static function getResponse(): array
    {
        $response = json_decode((string) self::$appClient->getResponse()->getContent(), true);

        if (is_string($response)) {
            return json_decode($response, true);
        }

        return $response;
    }

    /**
     * Return entity manager.
     */
    private function getEntityManager(): ObjectManager
    {
        /** @var Registry */
        $doctrine = $this->getService('doctrine');

        return $doctrine->getManager();
    }

    /**
     * Return the JWT of User object.
     */
    public function getUserJWT(): string
    {
        /** @var JWTManager */
        $jwtEncoder = $this->getService('lexik_jwt_authentication.jwt_manager');

        return $jwtEncoder->create($this->getUser());
    }
}
