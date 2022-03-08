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
        if (self::$client === null) {
            self::$client = static::createClient();
            self::$client->setServerParameters([
                'CONTENT_TYPE' => 'application/json',
                'HTTP_ACCEPT' => 'application/ld+json',
            ]);
        }

        if(self::$pedro === null){
            self::$pedro = clone self::$client;
            $this->createAuthenticatedUser(self::$pedro, 'pedro@api.com');
        }

        if(self::$brian === null){
            self::$brian = clone self::$client;
            $this->createAuthenticatedUser(self::$brian, 'brian@api.com');
        }

        if(self::$javier === null){
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

    protected function initDbConnection(): Connection
    {
        return $this->getContainer()->get('doctrine')->getConnection();
    }

    /**
     * @throws Exception
     */
    protected function getPeterId(): array
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM user WHERE email = "peter@api.com"')->fetchFirstColumn();
    }

    /**
     * @throws Exception
     */
    protected function getBrianId(): array
    {
        return $this->initDbConnection()->executeQuery('SELECT id FROM user WHERE email = "brian@api.com"')->fetchFirstColumn();
    }
}
