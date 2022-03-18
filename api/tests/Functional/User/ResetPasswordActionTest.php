<?php

namespace App\Tests\Functional\User;

use Doctrine\DBAL\Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

class ResetPasswordActionTest extends UserTestBase
{
    /**
     * @throws Exception
     */
    public function testResetPassword(): void
    {
        $pedroId = $this->getPedroId();

        $payload = [
            'resetPasswordToken' => '123456',
            'password' => 'new-password',
        ];

        self::$pedro->request(
            'PUT',
            sprintf('%s/%s/reset_password', $this->endpoint, $pedroId),
            [],
            [],
            [],
            json_encode($payload)
        );

        $response = self::$pedro->getResponse();
        $responseData = $this->getResponseData($response);

        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($pedroId, $responseData['id']);
    }
}