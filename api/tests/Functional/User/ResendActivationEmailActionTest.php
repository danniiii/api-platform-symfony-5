<?php

namespace App\Tests\Functional\User;

use Symfony\Component\HttpFoundation\JsonResponse;

class ResendActivationEmailActionTest extends UserTestBase
{
    public function testResendActivationEmail(): void
    {
        $payload = ['email' => 'javier@api.com'];

        self::$javier->request(
            'POST',
            sprintf('%s/resend_activation_email', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$javier->getResponse();

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
    }

    public function testResendActivationEmailToActiveUser(): void
    {
        $payload = ['email' => 'pedro@api.com'];

        self::$pedro->request(
            'POST',
            sprintf('%s/resend_activation_email', $this->endpoint),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$pedro->getResponse();

        $this->assertEquals(JsonResponse::HTTP_CONFLICT, $response->getStatusCode());
    }
}