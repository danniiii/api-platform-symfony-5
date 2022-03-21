<?php

namespace App\Tests\Functional;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Hautelook\AliceBundle\PhpUnit\RecreateDatabaseTrait;
use Liip\TestFixturesBundle\Test\FixturesTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class TestBase extends WebTestCase
{
    use FixturesTrait; //charge false data
    use RecreateDatabaseTrait; //create schema and populate database

    public static ?KernelBrowser $client = null;
    public static ?KernelBrowser $pedro = null;
    public static ?KernelBrowser $brian = null;
    public static ?KernelBrowser $javier = null;

    protected function setUp(): void
    {
        if (null === self::$client) {
            self::$client = static::createClient();
            self::$client->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/ld+json',
            ]);
        }

        if (null === self::$pedro) {
            self::$pedro = clone self::$client;
            $this->createAuthenticatedUser(self::$pedro, 'pedro@api.com');
        }

        if (null === self::$brian) {
            self::$brian = clone self::$client;
            $this->createAuthenticatedUser(self::$brian, 'brian@api.com');
        }

        if (null === self::$javier) {
            self::$javier = clone self::$client;
            $this->createAuthenticatedUser(self::$javier, 'javier@api.com');
        }
    }

    private function createAuthenticatedUser(KernelBrowser &$client, string $email): void
    {
        $user = $this->getContainer()->get('App\Repository\UserRepository')->findOneByEmailOrFail($email);
        $token = $this
            ->getContainer()
            ->get('Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface')
            ->create($user);
        $client->setServerParameters(
            [
            'HTTP_Authorization' => sprintf('Bearer %s', $token),
            'CONTENT_TYPE' => 'application/json',
            'HTTP_ACCEPT' => 'application/ld+json',
        ]);
    }

    protected function getResponseData(Response $response): array
    {
        return json_decode($response->getContent(), true);
    }

    /**
     * @return false|mixed
     *
     * @throws Exception
     */
    protected function getPedroId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM user WHERE email = "pedro@api.com"')->fetchFirstColumn()[0];
    }

    protected function initDbConnection(): Connection
    {
        return $this->getContainer()->get('doctrine')->getConnection();
    }

    /**
     * @return false|mixed
     *
     * @throws Exception
     */
    protected function getBrianId()
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM user WHERE email = "brian@api.com"')->fetchFirstColumn();
    }
}
